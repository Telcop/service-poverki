<?php

namespace App\Orchid\Layouts\Verifications;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\TD;
use Illuminate\Support\Facades\Auth;

class VerificationPoverkiCommandBarRows extends Rows
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
                    Button::make(__('Activate verifications'))
                        ->method('activateVerifications')
                        ->class('btn icon-link btn-secondary rounded')
                        ->icon('bs.clipboard-check')
                        ->align(TD::ALIGN_LEFT)
                        ->disabled($this->query->has('disable_entering'))
                        ->id('date-button'),

                    ModalToggle::make('Восстановление')
                        ->modal('RestoreModal')
                        ->method('restoreItem')
                        ->class('btn icon-link btn-secondary rounded')
                        ->canSee(Auth::user()->hasAccess('platform.admin.logging')),
            ])
            ->widthColumns('19% 13%')
            ->alignEnd(),
        ];
    }
}
