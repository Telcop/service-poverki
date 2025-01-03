<?php

namespace App\Orchid\Layouts\References;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\DropDown;
use App\Models\Verifications\Vendor as ModelVendor;


class ReferenceVendorTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'vendors';

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
        return __('No records were found. Try selecting filters with different values');
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

            TD::make('vendore_code', 'Модель')
                ->sort()
                // ->filter(Input::make())
                // ->filter(TD::FILTER_TEXT)
                ->cantHide()
                ->width('100'),

            TD::make('vendore_name', 'Название')
                ->cantHide()
                ->width('300'),

            TD::make('created_at', 'Дата создания')
                ->sort()
                ->render(function ($model) {
                    return $model->created_at->format('d.m.Y H:i');
                })
                ->width('150')
                ->align(TD::ALIGN_RIGHT),

            TD::make('updated_at', 'Дата обновления')
                ->sort()
                ->render(function ($model) {
                    return $model->updated_at->format('d.m.Y H:i');
                })
                ->width('160')
                ->align(TD::ALIGN_RIGHT),

            // DropDown: кнопки редактирования и удаления
            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(ModelVendor $item) =>
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
