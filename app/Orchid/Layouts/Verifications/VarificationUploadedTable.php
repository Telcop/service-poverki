<?php

namespace App\Orchid\Layouts\Verifications;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use App\Models\Verifications\Working;

class VarificationUploadedTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'table5';


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
            TD::make()
                ->render(function (Working $working){
                    return CheckBox::make('working[]')
                        ->value($working->id)
                        ->checked(false)
                        ->id("checkbox-{$working->id}");
                })
                ->cantHide()
                ->align(TD::ALIGN_LEFT)
                ->width('50'),

            TD::make('id', 'ID')
                ->sort()
                ->align(TD::ALIGN_LEFT)
                ->width('50'),

            TD::make('request.number', 'Номер заявки')
                ->render(function ($model) {
                    return $model->request->number . config('custom.verification_number_request_mask');
                })
                ->align(TD::ALIGN_CENTER)
                ->width('120')
                ->defaultHidden(),

            TD::make('request.date_from', 'Дата заявки')
                ->render(function ($model) {
                    return $model->request->date_from->format('d.m.Y');
                })
                ->width('120')
                ->defaultHidden(),

            TD::make('inv_no', '№ инвойса')
                ->sort()
                ->width('120')
                ->defaultHidden(),

            TD::make('vendor.vendore_code', 'Модель')
                ->cantHide()
                ->width('100'),

            TD::make('vendor.vendore_name', 'Название')
                ->defaultHidden(),

            TD::make('serial_start', 'Начало серии')
                ->cantHide()
                ->width('130'),

            TD::make('serial_end', 'Конец серии')
                ->cantHide()
                ->width('130'),

            TD::make('quantity', 'Кол-во')
                ->width('90'),

            TD::make('date_import', 'Дата ввоза в РФ')
                ->sort()
                ->render(function ($model) {
                    return $model->date_import->format('d.m.Y');
                })
                ->width('120')
                ->defaultHidden(),

            TD::make('sut.date_from', 'Дата действия СУТ от')
                ->sort()
                ->render(function ($model) {
                    return $model->sut->date_from->format('d.m.Y');
                })
                ->width('130')
                ->defaultHidden(),

            TD::make('sut.date_to', 'Дата действия СУТ до')
                ->sort()
                ->render(function ($model) {
                    return $model->sut->date_to->format('d.m.Y');
                })
                ->width('130')
                ->defaultHidden(),

            TD::make('sut.number', 'Номер СУТ')
                ->sort()
                ->width('100')
                ->defaultHidden(),

            TD::make('number_poverki', 'Номер письма')
                ->sort()
                ->width('120'),

            TD::make('date_poverki', 'Дата письма')
                ->sort()
                ->render(function ($model) {
                    return $model->date_poverki->format('d.m.Y');
                })
                ->width('150'),

            TD::make('name_poverki', 'Название поверки')
                ->sort()
                ->width('250')
                ->defaultHidden(),

            TD::make('date_publication', 'Дата публикации')
                ->sort()
                ->render(function ($model) {
                    return $model->date_publication->format('d.m.Y');
                })
                ->width('150'),

            TD::make('created_at', 'Дата создания')
                ->sort()
                ->render(function ($model) {
                    return $model->created_at->format('d.m.Y H:i');
                })
                ->width('150')
                ->align(TD::ALIGN_RIGHT)
                ->defaultHidden(),

            TD::make('updated_at', 'Дата обновления')
                ->sort()
                ->render(function ($model) {
                    return $model->updated_at->format('d.m.Y H:i');
                })
                ->width('160')
                ->align(TD::ALIGN_RIGHT)
                ->defaultHidden(),
            
            // DropDown: кнопки редактирования и удаления
            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(Working $working) =>
                    DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make(__('Open the verification'))
                                ->href(env('PATH_FTP') . env('FTP_ROOT_POVERKI') . DIRECTORY_SEPARATOR . $working->url_poverki)
                                ->icon('bs.file-earmark-pdf')
                                ->target('_blank'),
                        ]))
        ];
    }
}
