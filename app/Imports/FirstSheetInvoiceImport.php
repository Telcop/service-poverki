<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Verifications\Working;
use App\Models\Verifications\Status;
use App\Models\Verifications\Vendor as ModelVendor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Color;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as DateExcel;

class FirstSheetInvoiceImport implements ToCollection, WithStartRow, SkipsEmptyRows, WithValidation, WithProgressBar 
{
    use Importable;

    protected const START_ROW = 3;

    public function collection(Collection $rows)
    {
        $data = array();
        $success = array();
        $inv_no = null;
        foreach ($rows as $num => $row) 
        {
            if (!empty($row[7])) {
                $inv_no = $row[7];
            }
            if (isset($row[2])) {
                $vendor = ModelVendor::where('vendore_code', $row[2])->first();
                if (isset($vendor)) {
                    $working_exit = Working::where([
                        ['vendor_id', $vendor->id],
                        ['inv_no', $inv_no],
                        ['serial_start', $row[3]],
                        ['serial_end', $row[4]],
                    ])->get()->first();
                    if (empty($working_exit)) {
                        $serial_start = trim($row[3]);
                        $serial_end = trim($row[4]);
                        $serial_start_int = (int)substr($serial_start, -9);
                        $serial_end_int = (int)substr($serial_end, -9);
                        Working::create([
                            'etd' => date_format(Carbon::instance(DateExcel::excelToDateTimeObject($row[1])), 'Y-m-d'),
                            'vendor_id' => $vendor->id,
                            'status_id' => 1,
                            'inv_no' => $inv_no,
                            'serial_start' => $serial_start,
                            'serial_end' => $serial_end,
                            'serial_start_int' => $serial_start_int,
                            'serial_end_int' => $serial_end_int,
                            'quantity' => $serial_end_int - $serial_start_int + 1,
                        ]);
                        $success[] = $num + self::START_ROW; 
                    } else {
                        $data['unique'][] = [
                            'num' => $num + self::START_ROW,
                            'id_exist' => $working_exit->id,
                            'model' => $row[2],
                            'inv_no' => $inv_no,
                            'serial_start' => $row[3],
                            'serial_end' => $row[4],
                        ];
                    }
                } else {
                    $data['model'][] = [
                        'num' => $num + self::START_ROW,
                        'model' => $row[2]
                    ];
                }
            }
        }
        if (!empty($data)) {
            Alert::view('import.alerterror', Color::INFO(), ['data' => $data]); // WARNING DARK
            if (!empty($success)) {
                 Toast::warning("Импортированы строки " .  implode(', ', $success) . ".");
            }
        } else {
            Toast::warning("Импорт завершен успешно");
        }
    }

    // Логика пропуска не совсем пустых строк
    public function isEmptyWhen(array $row): bool
    {
        return 
            $row[2] === null ||
            $row[3] === null ||
            $row[4] === null;
    }

    // С какой строки начинать импорт
    public function startRow(): int
    {
        return self::START_ROW;
    }

    // Правила валидации
    public function rules(): array
    {
        return [
            '2' => [
                'required',
                'string'
            ],
        ];
    }
}
