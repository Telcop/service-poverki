<?php

namespace App\Orchid\Screens\Verifications;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\Layouts\Modal;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Color;
use App\Models\Verifications\Working;
use App\Models\Verifications\Sut;
use App\Models\Verifications\Status;
use Orchid\Platform\Dashboard;
use Illuminate\Support\Facades\Log;
use App\Orchid\Layouts\Verifications\VerificationTabMenu;
use App\Orchid\Layouts\Verifications\VerificationIndexCommandBarRows;
use App\Orchid\Layouts\Verifications\VerificationIndexCommandBar2Rows;
use App\Orchid\Layouts\Verifications\VarificationIndexTable;
use App\Orchid\Layouts\Verifications\CreateOrUpdateIndexModalRows;
use App\Orchid\Layouts\Verifications\ImportInvoicesIndexModalRows;
use Illuminate\Support\Carbon;
use App\Orchid\Selections\VerificationIndexOperatorSelection;
use App\Imports\InvoiceImport;
use Maatwebsite\Excel\Facades\Excel;
use Orchid\Attachment\Models\Attachment;
use App\Models\Logging;
use Illuminate\Support\Facades\Auth;

class VerificationIndexScreen extends Screen
{
    protected const TAB_NUMBER = 1; // 1 вкладка
    protected $paginate = 50;
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
        $this->table = Working::where('status_id', $this->tab->id)->with('vendor')
            ->filtersApplySelection(VerificationIndexOperatorSelection::class)
            ->filters()
            ->paginate($this->paginate); //$this->paginate); 

        return [
            'tab' => $this->tab,
            'table1' => $this->table, 
            'disable_entering' => $this->disable_entering,
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
            VerificationIndexCommandBarRows::class,
            VerificationIndexOperatorSelection::class,
            VarificationIndexTable::class,

            // ============================================= МОДАЛЬНЫЕ ОКНА ============================================
            //
            // Модальное окно Создание записи
            Layout::modal('CreateItemModal', CreateOrUpdateIndexModalRows::class)
                ->applyButton('Создать'),

            // Модальное окно Редактирование записи
            Layout::modal('UpdateItemModal', CreateOrUpdateIndexModalRows::class)
                ->applyButton('Сохранить')
                ->async('asyncUpdateItem'),
                        
            // Модальное окно восстановление удаленной записи
            Layout::modal('RestoreModal', Layout::rows([
                Input::make('id', 'id')
                    ->mask('9{1,10}')
            ]))
                ->title('Введите id записи для восстановления')
                ->size(Modal::SIZE_SM),

            // Модальное окно Экспорт инвойсов из таблицы Excel
            Layout::modal('ImportInvoicesModal', ImportInvoicesIndexModalRows::class)
                ->title('Импорт инвойсов из таблицы xls')
                ->applyButton('Импорт')
                ->size(Modal::SIZE_LG),
        ];
    }

    // ========================================================= ОБРАБОТКА ================================================================
    //
    // Обработка Создание записи
    public function createItem(Request $request)
    {
        $this->tab = Status::orderBy('weight', 'asc')->offset(self::TAB_NUMBER - 1)->limit(1)->get()->first();
        $working = array_merge(self::correctionFieldsItem($request), self::statusItem($this->tab->id));
        if ($working['quantity'] >= 1) {
            $working = Working::create(array_merge($request->item, $working));
            Toast::info("Добавлена новая запись c id = " . $working->id);
        } else {
            Toast::warning("Не корректные данные");
        }
        Logging::setAction(Auth::user()->name, Logging::ACTION_CREATE_INVOICE, [
            'id' => $working->id
           ]);
    }

    // Асинхонный метод Редактирование записи
    public function asyncUpdateItem($id)
    {
        Log::info('Async method called with ID: ' . $id);
        $this->tab = Status::orderBy('weight', 'asc')->offset(self::TAB_NUMBER - 1)->limit(1)->get()->first();
        $item = Working::with('vendor')->find($id);
        if (!$item) {
            throw new \Exception("Item with ID {$id} not found.");
        }
        return [
            'item' => $item
        ];
    }

    // Обработка Редатирование записи
    public function updateItem(Request $request)
    {   
        $working = self::correctionFieldsItem($request);
        if ($working['quantity'] >= 1) {
            Working::find($request->input('item.id'))->update(array_merge($request->item, $working));
            Toast::info("Запись c id = " . $request->input('item.id') . " обновлена");
            Logging::setAction(Auth::user()->name, Logging::ACTION_UPDATE_INVOICE_1, [
                'id' => $request->input('item.id')
            ]);
        } else {
            Toast::warning("Не корректные данные");
            Logging::setAction(Auth::user()->name, Logging::ACTION_UPDATE_INVOICE_1, [
                'Error update ' . $request->input('item.id')
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

    public function importFormExcelInvoices(Request $request): void
    {
        $attachment = Attachment::find($request->input('file_upload'));
        $name = $attachment->path . $attachment->name . '.' . $attachment->extension;

        Excel::import(new InvoiceImport, $name, $attachment->disk);

        Logging::setAction(Auth::user()->name, Logging::ACTION_IMPORT_INVOICES, [
            'attachment' => [
                'disk' => $attachment->disk,
                'url' => $name
            ]
           ]);
    }
    
    // Обработка Ввод даты поставки в РФ
    public function enteringTheDate(Request $request): void
    {
        $date_entering = Carbon::parse($request->input('dateEntering')); 
        $error = [];
        $ok = [];

        $this->tab = Status::orderBy('weight', 'asc')->offset(self::TAB_NUMBER)->limit(1)->get()->first();

        foreach ($request->input('working') as $id) {
            $item_working = Working::find($id);
            $sut = Sut::query()->where([
                    ['vendor_id', '=', $item_working->vendor_id],
                    ['date_from', '<=',  date_format($date_entering, 'Y-m-d')],
                    ['date_to', '>=', date_format($date_entering, 'Y-m-d')],
                ])
                ->orderBy('date_to', 'DESC')
                ->orderBy('id', 'DESC')
                ->first();

            if (!$sut) {
                $error[] = $id;
                continue;  
            } else {
                $ok[] = $id;
            }

            Working::find($id)->update(array_merge([
                'date_import' => $request->input('dateEntering'),
                'sut_id' => $sut->id],
                self::statusItem($this->tab->id) //'status_id' => 
            ));
        }

        if (empty($error)) {
            Toast::info(self::sutMessage($ok))->delay(10000);
            Logging::setAction(Auth::user()->name, Logging::ACTION_NEXT_STATUS, [
                'new_status_id' => $this->tab->id,
                'ids' => $request->input('working'),
                'date_import' => $request->input('dateEntering'),
            ]);
        } elseif (empty($ok)) {
            Toast::warning(self::sutMessage($error, true))->delay(10000);
            Logging::setAction(Auth::user()->name, Logging::ACTION_NEXT_STATUS, [
                'Error next status Index ids' => $request->input('working')
            ]);
        } else {
            Toast::error(self::SutMessage($ok) . self::sutMessage($error, true))->delay(10000);
            Logging::setAction(Auth::user()->name, Logging::ACTION_NEXT_STATUS, [
                'new_status_id' => $this->tab->id,
                'ids_ok' => $ok,
                'date_import' => $request->input('dateEntering'),
                'ids_error' => $error,
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
    
    private function sutMessage(Array $ids, $error = false)
    {
        $subject = count($ids) > 1 ? 'Для записей ' : 'Для записи ';
        $subject .= 'c id = [' . implode(', ', $ids) . '] ';
        $subject .= $error ? 'не ' : 'успешно ';
        $subject .= count($ids) > 1 ? 'присвоены регистрационные номера ' : 'присвоен регистрационный номер ';
        $subject .= $error ? 'из-за остутствия в справочнике СУТ.' : 'из справочника СУТ. '; 
        return $subject;
    }

}
