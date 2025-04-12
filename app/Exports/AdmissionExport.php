<?php

namespace App\Exports;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Payment;
use App\Models\Student;
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

class AdmissionExport implements FromView,ShouldAutoSize,WithStyles,WithDefaultStyles
{

    protected $from;
    protected $to ;

    protected $package_id;
    protected $filterGroup ;
    protected $filterCourse ;
    protected $filterBatch ;
    protected $filterAcademicYear;

    protected $filterAddedBy;

    public function __construct(
       $from,$to,
       $package_id,
        $filterGroup,
        $filterCourse,
        $filterBatch,
        $filterAcademicYear,
        $filterAddedBy
    )
    {
        $this->from = $from;
        $this->to=$to;

        $this->package_id = $package_id;

        $this->filterGroup =$filterGroup;
        $this->filterCourse =$filterCourse;
        $this->filterBatch =$filterBatch;
        $this->filterAcademicYear=$filterAcademicYear;

        $this->filterAddedBy=$filterAddedBy;
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

        $students = Student::with(["batches","courses"])
        ->when($this->from,function($q){
            return $q->whereDate("created_at",">=",$this->from);
        })
        ->when($this->to,function($q){
            return $q->whereDate("created_at","<=",$this->to);
        })
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
        })->when($this->filterAddedBy!=[],function($q) {
            return $q->whereIn("user_id",$this->filterAddedBy);
        })
            ->latest()
            ->get();

        return view('exports.admission', compact("students"));
    }
}
