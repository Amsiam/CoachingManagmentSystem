<?php

namespace App\Exports;

use App\Models\BookSell;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookSellExport implements FromView,ShouldAutoSize,WithStyles,WithDefaultStyles
{

    protected $from;
    protected $to ;


    public function __construct(
       $from,$to
        )
    {
        $this->from = $from;
        $this->to=$to;
    }


    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => ['bold' => true,"size"=>18],
                'fill' => [
                    'fillType' =>Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'FFFFFF00',
                    ],
                    'endColor' => [
                        'argb' => 'FFFFFF00',
                    ],
                ],
            ],

            3    => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'FFADFF2F',
                    ],
                    'endColor' => [
                        'argb' => 'FFADFF2F',
                    ],
                ],
            ],

        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'borders' => [
                'allBorders' => [
                     'borderStyle' => Border::BORDER_THIN,
                     'color' => [
                         'rgb' => '808080'
                     ]
                 ],
             ],
             'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            ];

    }

    public function view(): View
    {

        $sells = BookSell::whereBetween("date",[$this->from,$this->to])
        ->get();



        return view('exports.sells', compact("sells"));
    }
}