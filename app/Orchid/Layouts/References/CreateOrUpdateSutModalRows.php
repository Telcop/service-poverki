<?php

namespace App\Orchid\Layouts\References;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\DateRange;
use App\Models\Verifications\Vendor as ModelVendor;

class CreateOrUpdateSutModalRows extends Rows
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
                Relation::make('item.vendor_id')
                    ->fromModel(ModelVendor::class, 'vendore_code')
                    ->required()
                    ->title('Модель'),
                Input::make('item.number')
                    ->required()
                    ->title('Регистрационный № СУТ:')
            ]),

            Group::make([
                DateTimer::make('item.date_from')
                    ->title('Дата действия СУТ от:')
                    ->altFormat('d.m.Y')
                    ->required()
                    ->allowInput()
                    ->allowEmpty()
                    ->noTime(),
                DateTimer::make('item.date_to')
                    ->title('Дата действия СУТ от:')
                    ->altFormat('d.m.Y')
                    ->required()
                    ->allowInput()
                    ->allowEmpty()
                    ->noTime(),
            ]),

        ];
    }
}
