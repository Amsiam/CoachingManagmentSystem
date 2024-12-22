<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title, Computed, Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Student;
use App\Models\Expense;
use App\Models\Package;
use App\Models\Group;
use App\Models\Course;
use App\Models\Batch;
use App\Models\AcademicYear;

use App\Models\Payment;


use App\SMS\PaymentSMS;


use App\Exports\AdmissionStudentExport;
use App\Models\PersonalDetail;
use Livewire\WithFileUploads;
use Xenon\LaravelBDSms\Facades\SMS;

new
    #[Layout('layouts.app')]
    #[Title("Groups")]
    class extends Component
    {
        use Toast, WithPagination, WithFileUploads;

        public $filterPackage;
        public $filterGroup;
        public $filterCourse;
        public $filterBatch;
        public $filterDue;
        public $filterAcademicYear;

        public $filterSendTo = "Student";
        public $number;
        public $message;

        public $excelFile;



        public function rules()
        {
            return [
                "number" => "required",
                "message" => "required"
            ];
        }



        #[Computed]
        public function packages()
        {
            return Package::get();
        }

        #[Computed]
        public function groups()
        {
            return  Group::all();
        }

        #[Computed]
        public function courses()
        {
            return  Course::when($this->filterPackage, function ($q) {
                $q->where("package_id", $this->filterPackage);
            })->get();
        }

        #[Computed]
        public function batches()
        {
            return  Batch::when($this->filterGroup, function ($q) {
                $q->where("group_id", $this->filterGroup);
            })->when($this->filterCourse, function ($q) {
                $q->where("course_id", $this->filterCourse);
            })->get();
        }

        public function setnumber()
        {
            $this->number = $this->students;
        }


        #[Computed]
        public function students()
        {
            $stud =   PersonalDetail::whereHas("student", function ($qq) {
                return $qq->when($this->filterPackage, function ($q) {
                    return $q->where("package_id", $this->filterPackage);
                })
                    ->when($this->filterGroup, function ($q) {
                        return $q->whereHas("personalDetails", function ($qq) {
                            return $qq->where("group", $this->filterGroup);
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
                    })->when($this->filterDue == "Yes", function ($q) {
                        return $q->whereDoesntHave("payments", function ($qq) {
                            return $qq->whereBetween("month", [now()->firstOfMonth(), now()->lastOfMonth()]);
                        });
                    });
            })->get();

            if ($this->filterSendTo == "Student") {
                $stud = $stud->pluck("smobile")->implode(",");
            } else {

                $stud = $stud->pluck("gmobile")->implode(",");
            }
            return $stud;
        }

        public function updatedExcelFile()
        {
            if ($this->excelFile) {
                $this->excelFile = $this->excelFile->store("temp");

                //read excel file
                $reader  = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/' . $this->excelFile));
                $sheet    = $reader->getActiveSheet();
                $rows     = $sheet->toArray();

                $numArray = [];

                foreach ($rows as $row) {
                    $numArray[] = $row[0];
                }

                $this->number = implode(",", $numArray);

                if ($this->excelFile) {
                    unlink(storage_path('app/' . $this->excelFile));
                }
            }
        }


        function sms_send()
        {
            $this->validate();
            SMS::shoot($this->number, $this->message);
            $this->number = "";
            $this->message = "";
        }




        #[Computed]
        public function academics_years()
        {
            return AcademicYear::where("active", true)->latest()->get();
        }
    };

?>




<x-card title="Student List" separator progress-indicator>



    <div>
        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-choices label="Package" :options="$this->packages" single searchable wire:model.live="filterPackage" />
            </div>
            <div class="lg:w-1/2">
                <x-choices label="Group" :options="$this->groups" single searchable wire:model.live="filterGroup" />
            </div>
        </div>


        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-choices label="Course" :options="$this->courses" single searchable wire:model.live="filterCourse" />
            </div>
            <div class="lg:w-1/2">
                <x-choices label="Batch" :options="$this->batches" single searchable wire:model.live="filterBatch" />
            </div>
        </div>

        <div class="lg:flex gap-2">

            <div class="lg:w-1/2">
                <x-choices label="Academic Year" :options="$this->academics_years" single wire:model.live="filterAcademicYear" option-value="year" option-label="year" />
            </div>
            <div class="lg:w-1/2">
                <x-choices label="DUE" :options="[['label'=>'Yes','value'=>'Yes'],['label'=>'No','value'=>'No']]" single wire:model.live="filterDue" option-value="value" option-label="label" />
            </div>

        </div>


        <div class="lg:w-1/2">

            <x-choices label="SEND TO" :options="[['label'=>'Student','value'=>'Student'],['label'=>'Guardian','value'=>'Guardian']]" single wire:model.live="filterSendTo" option-value="value" option-label="label" />
        </div>

        <x-button wire:click="setnumber" label="Search" class="btn-primary" />
    </div>
    <div>
        <div class="lg:w-1/2">
            <x-file wire:model="excelFile" label="Upload Excel" hint="Only Excel. Numbers will contain first column of excel & without any header" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
        </div>
    </div>

    <div>
        <form wire:submit="sms_send">
            <x-input label="Mobile Numbers" wire:model="number" placeholder=" 017000000,0180000000" clearable />
            <x-textarea label="Message" wire:model="message" placeholder="Message" rows="5" />

            <x-button type="submit" label="Send" class="btn-success" />
        </form>
    </div>



</x-card>
