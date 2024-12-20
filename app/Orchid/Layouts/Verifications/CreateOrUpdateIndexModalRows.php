<?php

namespace App\Orchid\Layouts\Verifications;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Relation;
use App\Models\Verifications\Vendor;

class CreateOrUpdateIndexModalRows extends Rows
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
            Input::make('item.id')
                ->type('hidden'),
            Group::make([
                Input::make('item.inv_no')
                    ->mask('9{10}')    
                    ->required()
                    ->title('№ инвойса'),
                Relation::make('item.vendor_id')
                    ->fromModel(Vendor::class, 'vendore_code')
                    ->required()
                    ->title('Модель'),
            ]),
            Group::make([
                Input::make('item.serial_start')
                    ->mask('A{0,3}9{0,1}9{9}')
                    ->required()
                    ->title('Начало серии'),
                Input::make('item.serial_end')
                    ->mask('A{0,3}9{0,1}9{9}')
                    ->required()
                    ->title('Конец серии'),
            ])
        ];
    }
}
