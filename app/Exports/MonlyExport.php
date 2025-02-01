<?php

namespace App\Exports;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Payment;
use App\Models\Student;
use DateInterval;
use DatePeriod;
use DateTime;
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

class MonlyExport implements FromView,ShouldAutoSize,WithStyles,WithDefaultStyles
{

    protected $from;
    protected $to ;


    protected $start ;
    protected $end ;

    protected $package_id;
    protected $filterGroup ;
    protected $filterCourse ;
    protected $filterBatch ;
    protected $filterAcademicYear;

    public function __construct(
       $from,$to,
       $package_id,
        $filterGroup,
        $filterCourse,
        $filterBatch,
        $filterAcademicYear
    )
    {
        $this->from = $from;
        $this->to=$to;

        $this->package_id = $package_id;

        $this->filterGroup =$filterGroup;
        $this->filterCourse =$filterCourse;
        $this->filterBatch =$filterBatch;
        $this->filterAcademicYear=$filterAcademicYear;

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

            ],

        ];
    }

    public function view(): View
    {
        $this->start = new DateTime($this->from);
        $this->start->modify('first day of this month');
        $this->end    = new DateTime($this->to);
        $this->end->modify('first day of next month');

        $interval = DateInterval::createFromDateString('1 month');
        $periods   = new DatePeriod($this->start, $interval, $this->end);



        $students = Student::with(["personalDetails","payments"=>fn($q)=>$q->where("month",">=",$this->start->format("Y-m-d"))->where("month","<",$this->end->format("Y-m-d"))])
        ->where("active",1)
        ->when($this->package_id,function($q) {
            return $q->where("package_id",$this->package_id);
        })
        ->when($this->filterGroup,function($q) {
            return $q->whereHas("personalDetails",function($qq) {
                return $qq->where("group",$this->filterGroup);
            });
        })
        ->when($this->filterBatch,function($q) {
            return $q->whereHas("batches",function($qq) {
                return $qq->where("id",$this->filterBatch);
            });
        })
        ->when($this->filterCourse,function($q) {
            return $q->whereHas("courses",function($qq) {
                return $qq->where("id",$this->filterCourse);
            });
        })->when($this->filterAcademicYear,function($q) {
            return $q->where("year",$this->filterAcademicYear);
        })
            ->withSum(["payments" => function ($q) {
                return $q->where("paymentType", 2)->limit(1);
        }], "total")
            ->withSum(["payments" => function ($q) {
                return $q->where("paymentType", 2)->limit(1);
            }], "paid")
            ->withSum("payments", "due")
            ->latest()
            ->get();

        return view('exports.montly', compact("students","periods"));
    }
}
