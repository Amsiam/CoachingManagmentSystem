<?php

namespace App\Exports;

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

class ExpenseExport implements FromView,ShouldAutoSize,WithStyles,WithDefaultStyles
{

    protected $from;
    protected $to ;
    protected $types = [] ;


    public function __construct(
       $from,$to,$types
        )
    {
        $this->from = $from;
        $this->to=$to;
        $this->types=$types;
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

            ],

            3    => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => ['bold' => true],

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

        $dates = Expense::whereBetween("date",[$this->from,$this->to])
        ->select("date")
        ->groupBy("date")
        ->get();

        $expenses =  Expense::when(
            $this->types!=[],function($q) {
                return $q->whereIn("type",$this->types);
            }
        )->whereBetween("date",[$this->from,$this->to])
        ->get();


        $expenseCategories = ExpenseCategory::where("active",1)->get();



        return view('exports.expense', compact("expenses","expenseCategories","dates"));
    }
}
