<?php

namespace App\Orchid\Layouts\Verifications;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Attach;
use Orchid\Screen\Fields\DateTimer;

class AddVerificationRequestModalRows extends Rows
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
                Input::make('item.number_poverki')
                    // ->mask('9{10}')    
                    ->required()
                    ->title('Номер письма поверки:'),
                DateTimer::make('item.date_poverki')
                    ->title('Дата письма поверки:')
                    // ->format('d.m.Y')
                    ->altFormat('d.m.Y')
                    ->required()
                    ->allowInput()
                    ->allowEmpty()
                    ->noTime(),
            ]),

            Attach::make('item.attachment')
                ->title('Загрузить письмо о поверке в pdf формате:')
                ->storage('ftp_poverki')
                ->accept('.pdf')
                ->maxSize(5)
                ->help('Загрузите информационное письмо о поверке в pdf формате'),
        ];
    }
}
