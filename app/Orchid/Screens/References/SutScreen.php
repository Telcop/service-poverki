<?php

namespace App\Orchid\Screens\References;

use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use App\Http\Requests\VendorRequest;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Modal;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use App\Models\Verifications\Working;
use App\Models\Verifications\Vendor as ModelVendor;
use App\Models\Verifications\Sut;
use App\Models\Verifications\Status;
use Orchid\Platform\Dashboard;
use Illuminate\Support\Facades\Log;
use App\Orchid\Layouts\References\ReferenceSutCommandBarRows;
use App\Orchid\Layouts\References\ReferenceSutTable;
use App\Orchid\Layouts\References\CreateOrUpdateSutModalRows;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Orchid\Selections\SutOperatorSelection;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\TD;
use App\Models\Logging;
use Illuminate\Support\Facades\Auth;

class SutScreen extends Screen
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
        $this->table = Sut::with('vendor')
            ->filters()
            ->filtersApplySelection(SutOperatorSelection::class)
            // ->defaultSort('vendor.vendore_code', 'asc')
            ->paginate($this->paginate); 
 
        return ['sut' => $this->table];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Справочник СУТ';
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
            // Group::make([
                ModalToggle::make('Создать СУТ')
                    ->modal('CreateItemModal')
                    ->method('createItem')
                    ->modalTitle('Создать СУТ')
                    ->align(TD::ALIGN_LEFT)
                    ->class('btn icon-link btn-secondary rounded')
                    ->icon('bs.plus-circle'),

                ModalToggle::make('Восстановление')
                    ->modal('RestoreModal')
                    ->method('restoreItem')
                    ->align(TD::ALIGN_LEFT)
                    ->class('btn icon-link btn-secondary rounded')
                    // ->disabled(true)
                    // ->permission('platform.admin.logging')
                    ->canSee(Auth::user()->hasAccess('platform.admin.logging')),
            // ])
            // ->widthColumns('12% 15%')
            // ->alignEnd(),
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
            // ReferenceSutCommandBarRows::class,
            SutOperatorSelection::class,
            ReferenceSutTable::class,

            // ============================================= МОДАЛЬНЫЕ ОКНА ============================================
            //
            // Модальное окно Создание записи
            Layout::modal('CreateItemModal', CreateOrUpdateSutModalRows::class)
                ->applyButton('Создать'),

            // Модальное окно Редактирование записи
            Layout::modal('UpdateItemModal', CreateOrUpdateSutModalRows::class)
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
    public function createItem(Request $request) //SutRequest
    {
        $item = Sut::create($request->item); //->validated()
        if (isset($item)) {
            Toast::info("Добавлена новая модель c id = " . $item->id);
        } else {
            Toast::warning("Не корректные данные");
        }
        Logging::setAction(Auth::user()->name, Logging::ACTION_CREATE_SUT, [
            'id' => $item->id
        ]);
    }

    // Асинхонный метод Редактирование записи
    public function asyncUpdateItem($id)
    {
        Log::info('Async method (Sut) called with ID: ' . $id);
        // $this->tab = Status::orderBy('weight', 'asc')->offset(self::TAB_NUMBER - 1)->limit(1)->get()->first();
        $item = Sut::find($id);
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
                Sut::find($request->input('item.id'))->update($request->item);
                Toast::info("Модель c id = " . $request->input('item.id') . " обновлена");
                Logging::setAction(Auth::user()->name, Logging::ACTION_UPDATE_SUT, [
                    'id' => $request->input('item.id')
                ]);
            });
    } catch (Exception $e) { 
            Toast::warning("Модель c id = " . $request->input('item.id') . " не обновлена");
            Logging::setAction(Auth::user()->name, Logging::ACTION_UPDATE_SUT, [
                'Error update ' . $request->input('item.id')
            ]);
        }
    }

    // Обработка Удаление записи
    public function deleteItem($id)
    {
        Sut::destroy($id);
        Toast::warning("Запись c id = " . $id . " удалена");
        Logging::setAction(Auth::user()->name, Logging::ACTION_DELETE_SUT, [
            'id' => $id
        ]);
    }

    // Обработка Восстановление удаленной записи
    public function restoreItem(Request $request)
    {
        $record = Sut::withTrashed()->find($request->input('id'));
        if (!$record) {
            Toast::warning("Запись c id = " . $request->input('id') . " не найдена среди удаленных");
        } else {
            $record->restore();
            Toast::info("Запись c id = " . $request->input('id') . " восстановлена");
        }
    }
}
