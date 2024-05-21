<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Student;
use App\Models\Expense;
use App\Models\Package;
use App\Models\Group;
use App\Models\Course;
use App\Models\Batch;
use App\Models\User;

use App\Models\Payment;


use App\SMS\PaymentSMS;


use App\Exports\AdmissionExport;


new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $category;
    public $perPage=20;


    public $from ;
    public $to ;

    public $filterPackage ;
    public $filterGroup ;
    public $filterCourse ;
    public $filterBatch ;
    public $filterAcademicYear;
    public $filterAddedBy=[];



    public function mount(){
        $this->from = date("Y-m-d");
        $this->to = date("Y-m-d");

        if(!auth()->user()->can("report.excel")){
            $this->filterAddedBy = [auth()->user()->email];
        }
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
    public function users()
    {
        return  User::all();
    }

    #[Computed]
    public function courses()
    {
        return  Course::when($this->filterPackage,function($q) {
            $q->where("package_id",$this->filterPackage);
        })->get();
    }

     #[Computed]
     public function batches()
    {
        return  Batch::when($this->filterGroup,function($q) {
            $q->where("group_id",$this->filterGroup);
        })->when($this->filterCourse,function($q) {
            $q->where("course_id",$this->filterCourse);
        })->get();
    }


     #[Computed]
     public function students()
    {
        return  Student::with(["batches","courses","addedBy"])
        ->when($this->from,function($q){
            return $q->whereDate("created_at",">=",$this->from);
        })
        ->when($this->to,function($q){
            return $q->whereDate("created_at","<=",$this->to);
        })
        ->when($this->filterPackage,function($q) {
            return $q->where("package_id",$this->filterPackage);
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
        ->paginate($this->perPage);

        // dd($stu);
    }
    public function exportAdmission(){
        return Excel::download(new AdmissionExport(
            $this->from,
            $this->to,
            $this->filterPackage,
            $this->filterGroup ,
            $this->filterCourse ,
            $this->filterBatch ,
            $this->filterAcademicYear,
            $this->filterAddedBy
        ),date("Y-m-d H:s a")."-admission-export.xlsx");
    }

    public function exportPdf(){
        return Excel::download(new AdmissionExport(
            $this->from,
            $this->to,
            $this->filterPackage,
            $this->filterGroup ,
            $this->filterCourse ,
            $this->filterBatch ,
            $this->filterAcademicYear,
            $this->filterAddedBy
        ),date("Y-m-d H:s a")."-admission-export.pdf",\Maatwebsite\Excel\Excel::MPDF);
    }



};

?>

@php
    $academics_year =[["name"=>2024],["name"=>2025],["name"=>2026]];

@endphp


<x-card title="Student List" separator progress-indicator>


    <div>
        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-choices label="Package" :options="$this->packages" single searchable wire:model.live="filterPackage"  />
            </div>
            <div class="lg:w-1/2">
                <x-choices label="Group" :options="$this->groups" single searchable wire:model.live="filterGroup"  />
            </div>
        </div>


        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-choices label="Course" :options="$this->courses" single searchable wire:model.live="filterCourse"  />
            </div>
            <div class="lg:w-1/2">
                <x-choices label="Batch" :options="$this->batches" single searchable wire:model.live="filterBatch"  />
            </div>
        </div>

        @can("report.excel")
        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-choices label="Academic Year" :options="$academics_year" single wire:model.live="filterAcademicYear" option-value="name"  />
            </div>
            <div class="lg:w-1/2">
                <x-choices label="Added By" :options="$this->users" wire:model.live="filterAddedBy" option-value="email"  />
            </div>
        </div>
        @endcan
    </div>

    @can("report.excel")
        <x-button class="btn-primary btn-sm" wire:click="exportAdmission" >Export</x-button>
    @endcan
    <x-button class="btn-accent btn-sm" wire:click="exportPdf" >PDF</x-button>




    <div class="flex justify-between">
        <x-choices label="Per page" wire:model.live="perPage" single :options='[
            ["id"=>10,"name"=>10],
            ["id"=>20,"name"=>20],
            ["id"=>100,"name"=>100],
        ]' option-value="name" />

        <div class="flex justify-end">
            <x-datetime label="From" wire:model.live="from" />
            <x-datetime label="to" wire:model.live="to" />
        </div>
    </div>
    <x-table :headers='[
        ["key"=>"roll","label"=>"Roll"],
        ["key"=>"name","label"=>"Name"],
        ["key"=>"batch","label"=>"Batches"],
        ["key"=>"course","label"=>"Courses"],
        ["key"=>"package.name","label"=>"Package"],
        ["key"=>"created_at","label"=>"Admitted Date"],
        ["key"=>"admitted_by","label"=>"Added By"],
    ]' :rows="$this->students" with-pagination >


    @scope("cell_batch",$student)
    {{$student->batches->pluck("name")->implode(",")}}
    @endscope

    @scope("cell_course",$student)
    {{$student->courses->pluck("name")->implode(",")}}
    @endscope


    @scope("cell_admitted_by",$student)
    @if ($student->addedBy)
        {{$student->addedBy->name}}
    @endif
    @endscope


    </x-table>


</x-card>
