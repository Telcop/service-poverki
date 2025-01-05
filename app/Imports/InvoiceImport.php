<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Orchid\Support\Facades\Toast;

class InvoiceImport implements WithMultipleSheets, SkipsUnknownSheets
{
   
    public function sheets(): array
    {
        return [
            0 => new FirstSheetInvoiceImport()
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        Toast::warning("Необходимый лист отсутствует");
    }
}