<?php

namespace App\Orchid\Layouts\References;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\DateTimer;

class ReferenceVendorCommandBarRows extends Rows
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
                        ->canSee(true),
            ])
            ->widthColumns('15% 15%')
            ->alignEnd(),
        ];
    }
}
