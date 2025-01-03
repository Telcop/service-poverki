<?php

namespace App\Orchid\Layouts\Reports;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\TD;
use App\Orchid\Layouts\ReestrOperatorSelection;

class ReportReestrCommandBarRows extends Rows
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
            ReestrOperatorSelection::class,
            // Group::make([
            //         Button::make(__('Deactivate verifications'))
            //             ->method('deactivateVerifications')
            //             ->class('btn icon-link btn-secondary rounded')
            //             ->icon('bs.clipboard-check')
            //             ->align(TD::ALIGN_LEFT)
            //             ->disabled($this->query->has('disable_entering'))
            //             ->id('date-button'),

            //         ModalToggle::make('Восстановление')
            //             ->modal('RestoreModal')
            //             ->method('restoreItem')
            //             ->class('btn icon-link btn-secondary rounded')
            //             ->canSee(true),
            // ])
            // ->widthColumns('19% 13%')
            // ->alignEnd(),
        ];
    }
}
