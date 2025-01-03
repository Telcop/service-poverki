<?php

namespace App\Orchid\Layouts\References;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Fields\Relation;
use App\Models\Verifications\Vendor as ModelVendor;
use App\Models\Verifications\Sut;


class ReferenceSutTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'sut';

    /**
     * @return bool
     */
    protected function striped(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    protected function iconNotFound(): string
    {
        return 'table';
    }

    /**
     * @return string
     */
    protected function textNotFound(): string
    {
        return __('There are no entries in this tab');
    }

    /**
     * @return string
     */
    protected function subNotFound(): string
    {
        return '';
    }

    /**
     * The number of links to display on each side of current page link.
     *
     * @return int
     */
    protected function onEachSide(): int
    {
        return 3;
    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')
                ->sort()
                // ->filter(TD::FILTER_NUMERIC)
                ->align(TD::ALIGN_LEFT)
                ->width('50'),

            // TD::make('vendor.vendore_code', 'Модель')
            //     ->sort()
            //     ->filter(TD::FILTER_TEXT)
            //     ->cantHide()
            //     ->width('100'),

            TD::make('vendor.vendore_code', 'Модель')
                // ->sort()
                // ->filter(Relation::make('vendor.vendor_id')
                //     ->fromModel(ModelVendor::class, 'vendore_code')
                // )
                // ->render(fn($model) => $model->vendor->venfore_code)
                ->cantHide()
                ->width('100'),

            TD::make('vendor.vendore_name', 'Наименование')
                ->defaultHidden()
                ->width('300'),

            TD::make('number', 'Рег.№ СУТ')
                ->sort()
                ->cantHide()
                ->width('100'),

            TD::make('date_from', 'Дата действия СУТ от')
                ->sort()
                ->render(function ($model) {
                    return $model->date_from->format('d.m.Y');
                })
                ->cantHide()
                ->width('130'),

            TD::make('date_to', 'Дата действия СУТ до')
                ->sort()
                ->render(function ($model) {
                    return $model->date_to->format('d.m.Y');
                })
                ->cantHide()
                ->width('130'),

            TD::make('created_at', 'Дата создания')
                ->sort()
                ->render(function ($model) {
                    return $model->created_at->format('d.m.Y H:i');
                })
                ->width('150')
                ->defaultHidden()
                ->align(TD::ALIGN_RIGHT),

            TD::make('updated_at', 'Дата обновления')
                ->sort()
                ->render(function ($model) {
                    return $model->updated_at->format('d.m.Y H:i');
                })
                ->width('160')
                ->defaultHidden()
                ->align(TD::ALIGN_RIGHT),

            // DropDown: кнопки редактирования и удаления
            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(Sut $item) =>
                    DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            ModalToggle::make(__('Edit'))
                                ->modal('UpdateItemModal')
                                ->method('updateItem')
                                ->modalTitle('Редактирование ID = ' . $item->id)
                                ->asyncParameters(['id' => $item->id])
                                ->icon('bs.pencil'),
                            Button::make(__('Delete'))
                                ->method('deleteItem', ['id' => $item->id])
                                ->icon('bs.trash3')
                                ->confirm('После удаления Вы потеряете эту запись')
                        ]))
                // ->cantHide(),
        ];
    }
}
