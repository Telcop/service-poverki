<?php

namespace App\Orchid\Screens\References;

use Orchid\Screen\Screen;
use App\Models\Verifications\Vendor as ModelVendor;
use Illuminate\Http\Request;
use App\Http\Requests\VendorRequest;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Modal;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use App\Models\Verifications\Working;
use App\Models\Verifications\Sut;
use App\Models\Verifications\Status;
use Orchid\Platform\Dashboard;
use Illuminate\Support\Facades\Log;
use App\Orchid\Layouts\References\ReferenceVendorCommandBarRows;
use App\Orchid\Layouts\References\ReferenceVendorTable;
use App\Orchid\Layouts\References\CreateOrUpdateVendorModalRows;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Orchid\Selections\VendorOperatorSelection;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\TD;
use Illuminate\Support\Facades\Auth;

class VendorsScreen extends Screen
{

    protected $paginate = 50;
    protected $table;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $this->table = ModelVendor::filters()
        ->filtersApplySelection(VendorOperatorSelection::class)
        ->defaultSort('vendore_code', 'asc')
            ->paginate($this->paginate); 
 
        return ['vendors' => $this->table];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Справочник моделей';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return '';
    }



    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Создать модель')
                ->modal('CreateItemModal')
                ->method('createItem')
                ->modalTitle('Создать модель')
                ->align(TD::ALIGN_LEFT)
                ->class('btn icon-link btn-secondary rounded')
                ->icon('bs.plus-circle'),

            ModalToggle::make('Восстановление')
                ->modal('RestoreModal')
                ->method('restoreItem')
                ->align(TD::ALIGN_LEFT)
                ->class('btn icon-link btn-secondary rounded')
                // ->disabled(true)
                ->canSee(Auth::user()->hasAccess('platform.admin.logging')),
            ];
    }

    public function permission(): ?iterable
    {
        return [
            'platform.modules.references',
        ];
    }


    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            // ReferenceVendorCommandBarRows::class,
            VendorOperatorSelection::class,
            ReferenceVendorTable::class,

            // ============================================= МОДАЛЬНЫЕ ОКНА ============================================
            //
            // Модальное окно Создание записи
            Layout::modal('CreateItemModal', CreateOrUpdateVendorModalRows::class)
                ->applyButton('Создать'),

            // Модальное окно Редактирование записи
            Layout::modal('UpdateItemModal', CreateOrUpdateVendorModalRows::class)
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
    // Обработка Создание модели
    public function createItem(VendorRequest $request)
    {
        $item = ModelVendor::create($request->item); //->validated()
        if (isset($item)) {
            Toast::info("Добавлена новая модель c id = " . $item->id);
        } else {
            Toast::warning("Не корректные данные");
        }
    }

    // Асинхонный метод Редактирование записи
    public function asyncUpdateItem($id)
    {
        Log::info('Async method (ModelVendor) called with ID: ' . $id);
        // $this->tab = Status::orderBy('weight', 'asc')->offset(self::TAB_NUMBER - 1)->limit(1)->get()->first();
        $item = ModelVendor::find($id);
        if (!$item) {
            throw new \Exception("Item with ID {$id} not found (ModelVendor).");
        }
        return [
            'item' => $item
        ];
    }

    // Обработка Редатирование записи
    public function updateItem(Request $request)
    {   
        try{
            DB::transaction(function () use ($request) {
                ModelVendor::find($request->input('item.id'))->update($request->item);
                Toast::info("Модель c id = " . $request->input('item.id') . " обновлена");
            });
    } catch (Exception $e) { 
            Toast::warning("Модель c id = " . $request->input('item.id') . " не обновлена");
        }
    }

    // Обработка Удаление записи
    public function deleteItem($id)
    {
        ModelVendor::destroy($id);
        Toast::warning("Запись c id = " . $id . " удалена");
    }

    // Обработка Восстановление удаленной записи
    public function restoreItem(Request $request)
    {
        $record = ModelVendor::withTrashed()->find($request->input('id'));
        if (!$record) {
            Toast::warning("Запись c id = " . $request->input('id') . " не найдена среди удаленных");
        } else {
            $record->restore();
            Toast::info("Запись c id = " . $request->input('id') . " восстановлена");
        }
    }
}
