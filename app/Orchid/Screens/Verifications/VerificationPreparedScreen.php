<?php

namespace App\Orchid\Screens\Verifications;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\Layouts\Modal;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\DB;
use App\Models\Verifications\Working;
use App\Models\Verifications\Request as Req;
use App\Models\Verifications\Status;
use Orchid\Platform\Dashboard;
use App\Models\Settings as ModelSettings;
use Illuminate\Support\Facades\Log;
use App\Orchid\Layouts\Verifications\VerificationTabMenu;
use App\Orchid\Layouts\Verifications\VerificationPreparedCommandBarRows;
use App\Orchid\Layouts\Verifications\VarificationPreparedTable;
use App\Orchid\Layouts\Verifications\CreateOrUpdatePreparedModalRows;
use App\Orchid\Selections\VerificationPreparedOperatorSelection;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RequestExport;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Orchid\Support\Color;
use App\Models\Logging;
use Illuminate\Support\Facades\Auth;

class VerificationPreparedScreen extends Screen
{
    protected const TAB_NUMBER = 2; // 1 вкладка
    protected $paginate = 50;
    protected $tab;
    protected $table;
    protected $ids;
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
        $this->table = Working::where('status_id', $this->tab->id)->with('vendor')->with('sut')
            ->filtersApplySelection(VerificationPreparedOperatorSelection::class)
            ->filters()
            ->paginate($this->paginate); //config('custom.paginate') //->orderBy('id', 'desc')->get()

        return [
            'tab' => $this->tab,
            'table2' => $this->table, //$this->table->
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
            VerificationPreparedCommandBarRows::class,
            VerificationPreparedOperatorSelection::class,
            VarificationPreparedTable::class,

            // ============================================= МОДАЛЬНЫЕ ОКНА ============================================
            //
            // Модальное окно Редактирование записи
            Layout::modal('UpdateItemModal', CreateOrUpdatePreparedModalRows::class)
                ->applyButton('Сохранить')
                ->async('asyncUpdateItem'),
                        
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

    // Асинхонный метод Редактирование записи
    public function asyncUpdateItem($id)
    {
        Log::info('Async method called with ID: ' . $id);
        $this->tab = Status::orderBy('weight', 'asc')->offset(self::TAB_NUMBER - 1)->limit(1)->get()->first();
        $item = Working::with('vendor')->with('sut')->find($id);
        if (!$item) {
            throw new \Exception("Item with ID {$id} not found.");
        }

        return [
            'item' => $item,
        ];
    }

    // Обработка Редатирование записи
    public function updateItem(Request $request)
    {   
        $working = self::correctionFieldsItem($request);
        if ($working['quantity'] >= 1) {
            Working::find($request->input('item.id'))->update(array_merge($request->item, $working));
            Toast::info("Запись c id = " . $request->input('item.id') . " обновлена");
            Logging::setAction(Auth::user()->name, Logging::ACTION_UPDATE_INVOICE_2, [
                'id' => $request->input('item.id')
            ]);
        } else {
            Toast::warning("Не корректные данные");
            Logging::setAction(Auth::user()->name, Logging::ACTION_UPDATE_INVOICE_2, [
                'Error update id ' . $request->input('item.id')
            ]);
        }
    }

    public function returnInvoice(Request $request)
    {
        $id = $request->input('id');
        try{
            DB::transaction(function () use ($id) {
                $working = Working::find($id);
                $working->sut_id = null;
                $working->date_import = null;
                $working->status_id = self::statusId(-1);
                $working->save();
                Toast::info("Инвойс №" . $working->inv_no . " с моделью " . $working->vendor->vendore_code . " (ID = " . $working->id . ") возвращен на доработку");
                Logging::setAction(Auth::user()->name, Logging::ACTION_PREV_STATUS, [
                    'status_id' => self::statusId(-1),
                    'id' => $id
                ]);
                });
        } catch (Exception $e) {
            Toast::error("Операция не может быть выполнена");
            Logging::setAction(Auth::user()->name, Logging::ACTION_PREV_STATUS, [
                'Error prev status Prepared ids' => $id
            ]);
        }
   }

    // Групповое удаление 
    public function deleteGroup(Request $request)
    {   
        $ids = $request->input('working');
        Working::destroy($ids);
        Toast::warning("Записи c id " .  implode(', ', $ids) . " удалены");
        Logging::setAction(Auth::user()->name, Logging::ACTION_DELETE_INVOICES, [
            'id' => $ids
        ]);
    }

    // Обработка Удаление записи
    public function deleteItem($id)
    {
        Working::destroy($id);
        Toast::warning("Запись c id = " . $id . " удалена");
        Logging::setAction(Auth::user()->name, Logging::ACTION_DELETE_INVOICE, [
            'id' => $id
        ]);
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

    // Обработка Создание заявки
    public function createRequest(Request $request) //: void
    {
        $ids = $request->input('working');
        $error = [];
        $ok = [];
        $this->tab = Status::orderBy('weight', 'asc')->offset(self::TAB_NUMBER)->limit(1)->get()->first();
        $number = ModelSettings::incrementNmberRequest();
        $date_from = $request->dateRequest;
        $url_request = date('Y/m/d/') . (string)Str::uuid() . '.xlsx';
        $req = Req::create([
            'date_from' => $date_from,
            'number' => $number,
            'url_request' => $url_request
        ]);
        if (!empty($req)) {
            $ok = $ids;
            foreach ($ids as $id) {
                Working::find($id)->update(array_merge(
                    ['request_id' => $req->id],
                    self::statusItem($this->tab->id)  
                ));
            }
            // Создаем заявку в excel
            Excel::store(new RequestExport($req->id), $url_request, 'ftp_requests');
            $filename = 'Запрос ' . $number . config('custom.verification_number_request_mask') . ' на поверку.xlsx';
            // Сообщение о новой заявки и ссылка для скачивания
            $filename_arr = explode('/', $url_request);
            $data = [
                'storage' => 'requests',
                'num' => $number,
                'y' => $filename_arr[0],
                'm' => $filename_arr[1],
                'd' => $filename_arr[2],
                'filename' => $filename_arr[3]
            ];
            Alert::view('export.alertsuccess', Color::INFO(), [
                'data' => $data,
                'date' => $date_from,
           ]); // WARNING DARK

            Toast::info(self::requestMessage($ok))->delay(10000);
            Logging::setAction(Auth::user()->name, Logging::ACTION_NEXT_STATUS, [
                'new_status_id' => $this->tab->id,
                'ids' => $ids,
                'number' => $number,
                'date_from' => $date_from,
                'request' => [
                    'disk' => 'ftp_requests',
                    'url' => $url_request
                ]
            ]);
        } else {
            $error = $ids;
            Toast::warning(self::requestMessage($error, true))->delay(10000);
            Logging::setAction(Auth::user()->name, Logging::ACTION_NEXT_STATUS, [
                'Error next status Prepared ids' => $ids
            ]);
        }
    }

    // Корректировка записи
    private function correctionFieldsItem($request)
    {
        $serial_start = trim(str_replace('_', '', $request->input('item.serial_start')));
        $serial_end = trim(str_replace('_', '', $request->input('item.serial_end')));
        $serial_start_int = (int)substr($serial_start, -9);
        $serial_end_int = (int)substr($serial_end, -9);

        return [
            'serial_start' => $serial_start,
            'serial_end' => $serial_end,
            'serial_start_int' => $serial_start_int,
            'serial_end_int' => $serial_end_int,
            'quantity' => $serial_end_int - $serial_start_int + 1
        ];
    }

    // Добавление/корректировка статуса
    private function statusItem($id)
    {
        return ['status_id' => $id];
    }
    
    private function requestMessage(Array $ids, $error = false)
    {
        $subject = count($ids) > 1 ? 'Для записей ' : 'Для записи ';
        $subject .= 'c id = [' . implode(', ', $ids) . '] ';
        $subject .= $error ? 'не ' : 'успешно ';
        $subject .= 'создана заявка.'; 
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
