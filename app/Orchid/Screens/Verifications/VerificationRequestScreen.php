<?php

namespace App\Orchid\Screens\Verifications;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\Layouts\Modal;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Facades\DB;
use App\Models\Verifications\Working;
use App\Models\Verifications\Request as Req;
use App\Models\Verifications\Status;
use Orchid\Platform\Dashboard;
use Illuminate\Support\Facades\Log;
use App\Orchid\Layouts\Verifications\VerificationTabMenu;
use App\Orchid\Layouts\Verifications\VerificationRequestCommandBarRows;
use App\Orchid\Layouts\Verifications\VarificationRequestTable;
use App\Orchid\Layouts\Verifications\AddVerificationRequestModalRows;
use Exception;
use Illuminate\Support\Carbon;
use App\Orchid\Selections\VerificationRequestOperatorSelection;

class VerificationRequestScreen extends Screen
{
    protected const TAB_NUMBER = 3; // 3 вкладка
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
            ->filtersApplySelection(VerificationRequestOperatorSelection::class)
            ->filters()->paginate($this->paginate); //->orderBy('id', 'desc')->get()

        return [
            'tab' => $this->tab,
            'table3' => $this->table, //$this->table->
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
            // VerificationRequestCommandBarRows::class,
            VerificationRequestOperatorSelection::class,
            VarificationRequestTable::class,

            // ============================================= МОДАЛЬНЫЕ ОКНА ============================================
            //
            // Модальное окно Добавление поверки
            Layout::modal('AddVerification', AddVerificationRequestModalRows::class)
                ->applyButton('Добавить')
                ->async('asyncAddVerification')
                ->size(Modal::SIZE_LG), //XL
                        
            // Модальное окно восстановление удаленной записи
            Layout::modal('RestoreModal', Layout::rows([
                Input::make('id', 'id')
                    ->mask('9{1,10}')
            ]))
                ->title('Введите id записи для восстановления')
                ->size(Modal::SIZE_SM),
        ];
    }

    // ========================================================= ОБРАБОТКА ================================================================
    //
    // Асинхонный метод Добавление поверки
    public function asyncAddVerification($id)
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

    // Обработка Добавление поверки
    public function addVerification(Request $request)
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
                $working->status_id = self::statusId(1);

                $working->attachment()->syncWithoutDetaching(
                    $request->input('item.attachment', [])
                );
                $working->url_poverki = $working->attachments()->first()->url();
                $working->save();
                Toast::info("Добавлено " . $working->name_poverki)
                ->delay(6000);
            });
        } catch (Exception $e) {
            Toast::error("Операция не может быть выполнена");
        }
    }

    // Обработка Возврат заявки
    public function returnRequest(Request $request)
    {
        $request_id = $request->input('id');
        try{
            DB::transaction(function () use ($request_id) {
                // $status = self::statusId(-1);
                $req = Req::findOrFail($request_id);
                $num = $req->number;
                $date = $req->date_from;
                Working::where('request_id', $request_id)
                    ->update([
                        'request_id' => null,  
                        'status_id' => self::statusId(-1) //$status  
                    ]);
                $req->delete();
                Toast::info("Заявка №" . $num . " от " . date_format($date, 'd.m.Y') . " расформирована");
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

    // Вычисление id этапа (статуса): текущий 0, предыдущий -1, следующий +1
    private function statusId($step = 0)
    {
        $statuses = Status::select('id')->orderBy('weight', 'asc')->get()->toArray();
        if (self::TAB_NUMBER > 1) {
            $result = $statuses[self::TAB_NUMBER - 1 + $step];
        } 
        return $result['id'] ?? null;
    }

}
