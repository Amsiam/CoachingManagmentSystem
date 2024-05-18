<?php

use Illuminate\Support\Facades\Session;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;

use App\Models\Package;
use App\Models\Group;
use App\Models\Course;
use App\Models\Batch;


use App\Exports\MonlyExport;


new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {


    public $from;
    public $to;



    public $filterPackage=1 ;
    public $filterGroup ;
    public $filterCourse ;
    public $filterBatch ;
    public $filterAcademicYear;
    public $filterAddedBy=[];


    public function mount(){
        $this->from = date("Y-m-d");
        $this->to = date("Y-m-d");

        // $this->filterRecievedBy = auth()->user()->email;
    }

    #[Computed]
    public function groups()
    {
        return  Group::all();
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


    public function export(){
        return Excel::download(new MonlyExport(
            $this->from,
            $this->to,
            $this->filterPackage,
            $this->filterGroup ,
            $this->filterCourse ,
            $this->filterBatch ,
            $this->filterAcademicYear,
            $this->filterAddedBy
        ),date("Y-m-d H:s a")."-monthly-export.xlsx");
    }

    public function exportPdf(){
        return Excel::download(new MonlyExport(
            $this->from,
            $this->to,
            $this->filterPackage,
            $this->filterGroup ,
            $this->filterCourse ,
            $this->filterBatch ,
            $this->filterAcademicYear,
            $this->filterAddedBy
        ),date("Y-m-d H:s a")."-monthly-export.pdf",\Maatwebsite\Excel\Excel::MPDF);
    }

};

?>



<x-card title="Montly Report" separator progress-indicator>
    @php
    $academics_year =[["name"=>2024],["name"=>2025],["name"=>2026]];

    @endphp


    <div>
        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-choices label="Academic Year" :options="$academics_year" single wire:model.live="filterAcademicYear" option-value="name"  />
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

        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-datetime label="From" wire:model.live="from" />
            </div>
            <div class="lg:w-1/2">
                <x-datetime label="to" wire:model.live="to" />
            </div>
        </div>
    </div>
<div class="mt-1"></div>
@can("report.excel")
    <x-button class="btn-primary btn-sm" wire:click="export" >Export</x-button>
   @endcan
    <x-button class="btn-accent btn-sm" wire:click="exportPdf" >PDF</x-button>



</x-card>
