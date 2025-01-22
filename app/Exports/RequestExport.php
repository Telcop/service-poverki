<?php

namespace App\Exports;

use App\Models\Verifications\Request as ModelRequest;
use App\Models\Verifications\Working;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;



class RequestExport implements FromView, WithColumnWidths , WithStyles //  FromArray //FromCollection
{
    
    protected $data;
    protected $count_rows;

    public function __construct($id)
    {
        $data['rows'] = Working::with(['sut', 'sut.vendor'])
            ->where('request_id', $id)
            ->get()
            ->map(function($working) { 
                return [
                    'vendore_name' => $working->sut->vendor->vendore_name ?? null,
                    'serial_start' => $working->serial_start,
                    'serial_end' => $working->serial_end,
                    'sut_number' => $working->sut->number ?? null,
                    'date_import' => date('d.m.Y', strtotime($working->date_import)),
                    'quantity' => $working->quantity,
                ];
            });

        // Номер и дата заказа
        $data['req'] = ModelRequest::where('id', $id)
            ->get([
                'number',
                'date_from'
            ])
            ->first();
        $this->data = $data;
        $this->count_rows = count($data['rows']);
    }

    // Вызов шаблона письма
    public function view(): View
    {
        return view('export.request', [  
            'data' => $this->data 
        ]);
    }

    // Ширина столбцов
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 19,
            'C' => 3,
            'D' => 15,
            'E' => 14,
            'F' => 14,
            'G' => 17,
            'H' => 17,
            'I' => 13,            
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // logo
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath('../storage/app/private/logo.jpg');
        $drawing->setOffsetX(2);
        $drawing->setOffsetY(2);
        $drawing->setHeight(34);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);

        for ($i = 15; $i < (15 + $this->count_rows);  $i++) {
            // Высота строки в позиции
            $sheet->getRowDimension($i)->setRowHeight(58);
            // Форматирование строки позиции
            $result[$i] = [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]; 
            $range = 'B' . $i . ':D' . $i;
            $result[$range] = [
                'alignment' => [
                    'wrapText' => true,
                ],
            ];
        }
        $result['B10']  = [
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => [Color::COLOR_BLACK]
                            ],
                        ]
                        ];
        $result['D10']  = [
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => [Color::COLOR_BLACK]
                            ],
                        ]
                        ];
        $result[14]     = [
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ]
                        ];
        return $result;
    }
}

