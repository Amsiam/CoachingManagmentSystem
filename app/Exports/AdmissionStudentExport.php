<?php

namespace App\Exports;

use App\Models\Student;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdmissionStudentExport implements FromView, ShouldAutoSize, WithStyles, WithDefaultStyles
{

    protected $package_id;
    protected $filterGroup;
    protected $filterCourse;
    protected $filterBatch;
    protected $filterAcademicYear;



    public function __construct(
        $package_id,
        $filterGroup,
        $filterCourse,
        $filterBatch,
        $filterAcademicYear,
        protected $filterStatus,
        protected $filterShift
    ) {
        $this->package_id = $package_id;

        $this->filterGroup = $filterGroup;
        $this->filterCourse = $filterCourse;
        $this->filterBatch = $filterBatch;
        $this->filterAcademicYear = $filterAcademicYear;
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'borders' => [
                'bottom' => ['borderStyle' => 'hair', 'color' => ['argb' => 'FFFF0000']],
                'top' => ['borderStyle' => 'hair', 'color' => ['argb' => 'FFFF0000']],
                'right' => ['borderStyle' => 'hair', 'color' => ['argb' => 'FF00FF00']],
                'left' => ['borderStyle' => 'hair', 'color' => ['argb' => 'FF00FF00']],
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
                'font' => ['bold' => true, "size" => 18],

            ],

            3    => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => ['bold' => true],

            ],

        ];
    }

    public function view(): View
    {

        $students = Student::with(["personalDetails", "personalDetails.shiftD"])
            ->when($this->package_id, function ($q) {
                return $q->where("package_id", $this->package_id);
            })
            ->when($this->filterGroup, function ($q) {
                return $q->whereHas("personalDetails", function ($qq) {
                    return $qq->where("group", $this->filterGroup);
                });
            })
            ->when($this->filterShift, function ($q) {
                return $q->whereHas("personalDetails", function ($qq) {
                    return $qq->where("shift", $this->filterShift);
                });
            })
            ->when($this->filterBatch, function ($q) {
                return $q->whereHas("batches", function ($qq) {
                    return $qq->where("id", $this->filterBatch);
                });
        })
            ->when($this->filterCourse, function ($q) {
                return $q->whereHas("courses", function ($qq) {
                    return $qq->where("id", $this->filterCourse);
                });
            })->when($this->filterAcademicYear, function ($q) {
                return $q->where("year", $this->filterAcademicYear);
            })
            ->when($this->filterStatus, function ($q) {
                $status = $this->filterStatus - 1;
                return $q->where("active", $status);
            })
            ->withSum("payments", "total")
            ->withSum("payments", "paid")
            ->withSum("payments", "discount")
            ->withSum("payments", "due")
            ->get();

        return view('exports.students', compact("students"));
    }
}
