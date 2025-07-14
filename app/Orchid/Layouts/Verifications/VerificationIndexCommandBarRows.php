<?php

namespace App\Orchid\Layouts\Verifications;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\DateTimer;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Fields\CheckBox;

class VerificationIndexCommandBarRows extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Group::make([
                    ModalToggle::make('Импорт инвойсов')
                        ->modal('ImportInvoicesModal')
                        ->method('importFormExcelInvoices')
                        ->modalTitle('Импорт инвойсов из таблицы xls')
                        ->align(TD::ALIGN_LEFT)
                        ->class('btn icon-link btn-secondary rounded')
                        ->icon('bs.table'),
    
                    ModalToggle::make('Создать инвойс')
                        ->modal('CreateItemModal')
                        ->method('createItem')
                        ->modalTitle('Создать инвойс')
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

                    Button::make('Удаление')
                        ->method('deleteGroup')
                        ->class('btn icon-link btn-secondary rounded')
                        ->icon('bs.trash3-fill')
                        ->align(TD::ALIGN_RIGHT)
                        ->disabled($this->query->has('disable_entering'))
                        ->id('button-del')
                        ->confirm('Вы действительно хотите удалить выделенные записи?'),
                    // CheckBox::make('selectAll')
                    //     ->value(0)
                    //     ->placeholder('Выделить все')
                    //     ->align(TD::ALIGN_RIGHT)
                    //     ->id('select-all'),

                    DateTimer::make('dateEntering')
                        ->title('Дата ввоза в РФ:')
                        ->format('d.m.Y')
                        ->allowInput()
                        ->allowEmpty()
                        ->noTime()
                        ->align(TD::ALIGN_RIGHT)
                        ->disabled($this->query->has('disable_entering'))
                        ->id('date-input'),

                    Button::make('Ввод даты')
                        ->method('enteringTheDate')
                        ->class('btn icon-link btn-secondary rounded')
                        ->icon('bs.calendar-check')
                        ->align(TD::ALIGN_LEFT)
                        ->disabled($this->query->has('disable_entering'))
                        ->id('date-button')

            ])
            ->widthColumns('16% 15% 16% 10% 17% 17%')
            ->alignEnd(),
        ];
    }
}
