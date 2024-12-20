<?php

namespace App\Orchid\Screens\Verifications;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\Layouts\Modal;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use App\Models\Verifications\Verification;
use App\Models\Verifications\Working;
use App\Models\Verifications\Sut;
use App\Models\Verifications\Status;
use Orchid\Platform\Dashboard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Orchid\Layouts\Verifications\VerificationTabMenu;
use App\Orchid\Layouts\Verifications\VerificationPoverkiCommandBarRows;
use App\Orchid\Layouts\Verifications\VarificationPoverkiTable;
use App\Orchid\Layouts\Verifications\EditVerificationPoverkiModalRows;
use Exception;
use Illuminate\Support\Carbon;

class VerificationPoverkiScreen extends Screen
{
    protected const TAB_NUMBER = 4; // 1 вкладка
    protected $paginate = 10;
    protected $tab;
    protected $table;
    protected $disable_entering = true;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Dashboard $dashboard): iterable //
    {
        $dashboard->registerResource('scripts', '/js/custom.js');
        $this->tab = Status::orderBy('weight', 'asc')->offset(self::TAB_NUMBER - 1)->limit(1)->get()->first();
        $this->table = Working::where('status_id', $this->tab->id)
            ->with('vendor')
            ->with('sut')
            ->with('request')
            ->filters()->paginate($this->paginate); //->orderBy('id', 'desc')->get()

        return [
            'tab' => $this->tab,
            'table4' => $this->table, //$this->table->
            'disable_entering' => $this->disable_entering,
            // 'item' => $this->item, 
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->tab->name;
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return $this->tab->description;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    public function permission(): ?iterable
    {
        return [
            'platform.modules.automation',
        ];
    }


    /**
     * The screen's layout elements.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    { 
        return [
            VerificationTabMenu::class,
            VerificationPoverkiCommandBarRows::class,
            VarificationPoverkiTable::class,

            // ============================================= МОДАЛЬНЫЕ ОКНА ============================================
            //
            // Модальное окно Редактирование поверки
            Layout::modal('EditVerification', EditVerificationPoverkiModalRows::class)
                ->applyButton('Сохранить')
                // ->size(Modal::SIZE_LG)
                ->async('asyncEditVerification')
                ->size(Modal::SIZE_LG), //XL
                                        
            // Модальное окно восстановление удаленной записи
            Layout::modal('ResoreModal', Layout::rows([
                Input::make('id', 'id')
                    ->mask('9{1,10}')
            ]))
                ->title('Введите id записи для восстановления')
                ->size(Modal::SIZE_SM),
        ];
    }

    // ========================================================= ОБРАБОТКА ================================================
    //
    // Асинхонный метод Редактирование поверки
    public function asyncEditVerification($id)
    {
        Log::info('Async method asyncAddVerification() called with ID: ' . $id);
        $this->tab = Status::orderBy('weight', 'asc')->offset(self::TAB_NUMBER - 1)->limit(1)->get()->first();
        $item = Working::with('vendor')->with('sut')->with('request')->find($id);
        if (!$item) {
            throw new \Exception("Item with ID {$id} not found.");
        }
        $item->load('attachment'); //->allowDuplicates()
        return [
            'item' => $item,
        ];
    }

    // Обработка Редактирование поверки
    public function editVerification(Request $request)
    {
        try{
            DB::transaction(function () use ($request) {
                $num = $request->input('item.number_poverki');
                $date = Carbon::parse($request->input('item.date_poverki'));

                $working = Working::find($request->input('item.id')); 
                $attributes = [
                    '#1' => $num,
                    '#2' => date_format($date, 'd.m.Y'),
                    '#3' => $working->vendor->vendore_code,
                    '#4' => $working->serial_start,
                    '#5' => $working->serial_end
                ];
                $working->number_poverki = $num;
                $working->date_poverki = $date;
                $working->name_poverki = str_replace(array_keys($attributes), array_values($attributes), ENV('NAME_TEMPLATE_POVERKI'));
                $working->status_id = self::statusId();
                $working->attachment()->delete();
                $working->attachment()->syncWithoutDetaching(
                    $request->input('item.attachment', [])
                );
                $working->url_poverki = $working->attachments()->first()->url();
                $working->save();
                Toast::info("Изменено " . $working->name_poverki)
                ->delay(6000);
            });
        } catch (Exception $e) {
            Toast::error("Операция не может быть выполнена");
        }
    }

    // Обработка Вернуть поверку
    public function returnVerification(Request $request)
    {
        $id = $request->input('id');
        try{
            DB::transaction(function () use ($id) {
                $working = Working::find($id);
                $poverka = $working->number_poverki;
                $working->number_poverki = null;
                $working->date_poverki = null;
                $working->name_poverki = null;
                $working->url_poverki = null;
                $working->status_id = self::statusId(-1);
                $working->attachments()->delete();
                $working->save();
                Toast::info("Поверка №" . $poverka . " по модели " . $working->vendor->vendore_code . " (ID = " . $working->id . ") возвращен на доработку");
            });
        } catch (Exception $e) {
            Toast::error("Операция не может быть выполнена");
        }
   }

    // Обработка Восстановление удаленной записи
    public function restoreItem(Request $request)
    {
        $record = Working::withTrashed()->find($request->input('id'));
        if (!$record) {
            Toast::warning("Запись c id = " . $request->input('id') . " не найдена среди удаленных");
        } else {
            $record->restore();
            Toast::info("Запись c id = " . $request->input('id') . " восстановлена");
        }
    }

    // Обработка Активация поверок на сайте
    public function activateVerifications(Request $request): void
    {
        $error = [];
        $ok = [];
        foreach ($request->input('working') as $id) {
            $working = Working::find($id); //->with('vendor')
            $verification = Verification::create([
                'vendor_code' => $working->vendor->vendore_code,
                'serial_start'=> $working->serial_start,
                'serial_end' => $working->serial_end,
                'pdf_name' => $working->name_poverki,
                'file_path'=> $working->url_poverki,
                'serial_start_int' => $working->serial_start_int,
                'serial_end_int' => $working->serial_end_int
            ]);
            $working->verification_id = $verification->id;
            $working->date_publication = date('Y-m-d');
            $working->status_id = self::statusId(1);
            $working->save();
            if (!$working) {
                $error[] = $id;
                continue;  
            } else {
                $ok[] = $id;
            }
        }
        if (empty($error)) {
            Toast::info(self::uploadedMessage($ok))->delay(10000);
        } elseif (empty($ok)) {
            Toast::warning(self::uploadedMessage($error, true))->delay(10000);
        } else {
            Toast::error(self::uploadedMessage($ok) . self::uploadedMessage($error, true))->delay(10000);
        }       
    }

    private function uploadedMessage(Array $ids, $error = false)
    {
        $subject = count($ids) > 1 ? 'Поверки ' : 'Поверка ';
        $subject .= 'c id = [' . implode(', ', $ids) . '] ';
        $subject .= $error ? 'не ' : 'успешно ';
        $subject .= count($ids) > 1 ? 'активированы и ' : 'активирована и ';
        $subject .= $error ? 'не ' : '';
        $subject .= count($ids) > 1 ? 'доступны для поиска на сайте.' : 'доступна для поиска на сайте '; 
        return $subject;
    }

    private function statusId($step = 0)
    {
        $statuses = Status::select('id')->orderBy('weight', 'asc')->get()->toArray();
        if (self::TAB_NUMBER > 1) {
            $result = $statuses[self::TAB_NUMBER - 1 + $step];
        } 
        return $result['id'] ?? null;
    }

}
