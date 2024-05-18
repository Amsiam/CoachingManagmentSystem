<?php

namespace App\Exports;

use App\Models\BookSell;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Payment;
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

class IncomeExport implements FromView,ShouldAutoSize,WithStyles,WithDefaultStyles
{

    protected $from;
    protected $to ;


    public $filterRecievedBy ;
    public $filterPaymentType ;
    public $filterPayType ;


    public function __construct(
       $from,$to,$filterRecievedBy,$filterPayType,$filterPaymentType
        )
    {
        $this->from = $from;
        $this->to=$to;

        $this->filterRecievedBy =$filterRecievedBy;
        $this->filterPayType =$filterPayType;
        $this->filterPaymentType=$filterPaymentType;
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


    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => ['bold' => true,"size"=>18],
                'borders' => [
                                'allBorders' => [
                                     'borderStyle' => Border::BORDER_DASHDOT,
                                     'color' => [
                                         'rgb' => '808080'
                                     ]
                                 ],
                             ],
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
                'borders' => [
                    'allBorders' => [
                         'borderStyle' => Border::BORDER_THIN,
                         'color' => [
                             'rgb' => '808080'
                         ]
                     ],
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

    public function view(): View
    {

        $payments = Payment::with("student")
        ->when($this->from,function($q){
            return $q->whereDate("created_at",">=",$this->from);
        })
        ->when($this->to,function($q){
            return $q->whereDate("created_at","<=",$this->to);
        })
        ->when($this->filterRecievedBy!=[],function($q){
            return $q->whereIn("recieved_by",$this->filterRecievedBy);
        })
        ->when($this->filterPayType,function($q){
            return $q->where("payType",$this->filterPayType);
        })
        ->when($this->filterPaymentType,function($q){
            return $q->where("paymentType",$this->filterPaymentType);
        })
        ->latest()->get();

        $bookSells = BookSell::when($this->from,function($q){
            return $q->whereDate("created_at",">=",$this->from);
        })
        ->when($this->to,function($q){
            return $q->whereDate("created_at","<=",$this->to);
        })
        ->when($this->filterRecievedBy!=[],function($q){
            return $q->whereIn("added_by",$this->filterRecievedBy);
        })->get();

        return view('exports.income', compact("payments","bookSells"));
    }
}
