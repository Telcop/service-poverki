<?php

namespace App\Orchid\Layouts\Verifications;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\TD;

class VerificationRequestCommandBarRows extends Rows
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
                        ->modal('RestoreModal')
                        ->method('restoreItem')
                        ->align(TD::ALIGN_LEFT)
                        ->class('btn icon-link btn-secondary rounded')
                        // ->disabled(true)
                        ->canSee(true),
            ])
            ->widthColumns('15%')
            ->alignEnd(),
        ];
    }
}
