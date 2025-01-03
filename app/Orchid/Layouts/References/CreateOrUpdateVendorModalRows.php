<?php

namespace App\Orchid\Layouts\References;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;

class CreateOrUpdateVendorModalRows extends Rows
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
            Input::make('item.vendore_code') //
                ->required()
                // ->disabled('disable')
                ->title('Модель'),
            Input::make('item.vendore_name')
                ->required()
                ->title('Название'),
        ];
    }
}
