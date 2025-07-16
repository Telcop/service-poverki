<?php

namespace App\Orchid\Layouts\Verifications;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Attach;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Relation;
use App\Models\Verifications\Vendor;

class ImportInvoicesIndexModalRows extends Rows
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

            Attach::make('file_upload')
                ->title('Загрузить файл xls')
                ->storage('import')
                ->accept('.xls, .xlsx')
                ->required()
                ->maxSize(5)
                ->help('Загрузите файл с расширением xls/xlsx для импорта инвойсов.'),

            // Input::make('file_upload')
            //     ->type('file')
            //     ->title('Загрузить файл xls')
            //     ->horizontal(),
        ];
    }
}
