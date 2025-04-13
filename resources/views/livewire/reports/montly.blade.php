<?php

use Illuminate\Support\Facades\Session;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;

use App\Models\Package;
use App\Models\Group;
use App\Models\{Course, Shift};
use App\Models\Batch;
use App\Models\AcademicYear;


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
    public $filterShift;
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
            $this->filterShift,
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
            $this->filterShift,
        ),date("Y-m-d H:s a")."-monthly-export.pdf",\Maatwebsite\Excel\Excel::MPDF);
    }

    #[Computed]
    public function academics_years(){
            return AcademicYear::where("active",true)->latest()->get();
        }

    #[Computed]
    public function shifts(){
            return Shift::when($this->filterCourse,function($q){
                return $q->where("course_id",$this->filterCourse);
            })->latest()->get();
        }


};

?>



<x-card title="Montly Report" separator progress-indicator>

    <div>
        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-choices label="Academic Year" :options="$this->academics_years" single wire:model.live="filterAcademicYear" option-value="year" option-label="year" />
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
                <x-choices label="Shift" :options="$this->shifts" single searchable wire:model.live="filterShift"  />
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
