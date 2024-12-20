<?php

namespace App\Orchid\Layouts\Verifications;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\DateTimer;
use App\Models\Verifications\Vendor;
use App\Models\Verifications\Sut;


class CreateOrUpdatePreparedModalRows extends Rows
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
                    ->title('Модель')
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
            ]),

                DateTimer::make('item.date_import')
                    ->title('Дата ввоза в РФ:')
                    ->altFormat('d.m.Y')
                    ->required()
                    ->allowInput()
                    ->allowEmpty()
                    ->noTime(),

                Relation::make('item.sut_id')
                    ->fromModel(Sut::class, 'number')
                    ->applyScope('active', $this->query->get('item.vendor_id')) 
                    ->displayAppend('full')
                    ->required()
                    ->title('СУТ'),

        ];
    }
}
