<?php

namespace App\Orchid\Layouts\Verifications;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\DateTimer;

class VerificationPreparedCommandBarRows extends Rows
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
                    ModalToggle::make('Восстановление')
                        ->modal('ResoreModal')
                        ->method('restoreItem')
                        ->align(TD::ALIGN_LEFT)
                        ->class('btn icon-link btn-secondary rounded')
                        // ->disabled(true)
                        ->canSee(true),

                    DateTimer::make('dateRequest')
                        ->title('Дата заявки:')
                        ->format('d.m.Y')
                        ->required()
                        ->allowInput()
                        ->allowEmpty()
                        ->noTime()
                        // ->help('Введите дату заявки')
                        ->align(TD::ALIGN_RIGHT)
                        ->disabled($this->query->has('disable_entering'))
                        ->id('date-input'),

                    Button::make('Создать заявку')
                        ->method('createRequest')
                        ->class('btn icon-link btn-secondary rounded')
                        ->icon('bs.calendar-check')
                        ->align(TD::ALIGN_LEFT)
                        ->disabled($this->query->has('disable_entering'))
                        ->id('date-button')
            ])
            ->widthColumns('15% 18% 18%')
            ->alignEnd(),
        ];
    }
}
