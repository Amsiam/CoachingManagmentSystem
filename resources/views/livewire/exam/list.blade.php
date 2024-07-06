<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Exam;
use App\Models\ExamRoutine;
use App\Models\Package;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Group;
use App\Models\AcademicYear;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $exam;
    public $perPage=20;
    public $search;

    public $routines = [];

    public bool $modal = false;

    public function rules()
    {
        return [
            'exam.name' => 'required',
            'exam.year' => 'required',
            'exam.package_id' => 'required',
            'exam.course_id' => 'required',
            'exam.batch_id' => 'required',
            'exam.group_id' => '',
            "routines.*.name" => "required",
            "routines.*.date" => "required",
            "routines.*.time" => "required",
            "routines.*.cq_mark" => "required",
            "routines.*.mcq_mark" => "required",
            "routines.*.practical_mark" => "required",
            "routines.*.code" => "required",
        ];
    }

    public function mount() {
        $this->exam = new Exam();



    }


     #[Computed]
     public function packages()
    {
        return  Package::get();
    }

     #[Computed]
     public function groups()
    {
        return  Group::get();
    }

    #[Computed]
    public function courses()
    {
        return  Course::when($this->exam->package_id,function ($q) {
            return $q->where("package_id",$this->exam->package_id);
        })->get();
    }

    #[Computed]
    public function batches()
    {
        return  Batch::when($this->exam->course_id,function ($q) {
            return $q->where("course_id",$this->exam->course_id);
        })->when($this->exam->group_id,function ($q) {
            return $q->where("group_id",$this->exam->group_id);
        })->get();
    }


     #[Computed]
     public function exams()
    {
        return  Exam::with(["package","course","batch","group"])->latest()
        ->when($this->search,function($q) {
            return $q->where("name","like","%".$this->search."%");
        })
        ->paginate($this->perPage);
    }

    public function modalClose(){
        $this->modal=false;
    }

    public function modalOpen(){

        $this->exam = new Exam();
        $this->routines = [];
        array_push($this->routines,[]);

        $this->modal=true;
    }

    public function addSubject(){
        array_push($this->routines,[]);
    }

    public function modalEditOpen($id){
        $this->exam = Exam::find($id);
        $this->routines = ExamRoutine::where("exam_id",$this->exam->id)->select("name","id","date","time","cq_mark","mcq_mark","practical_mark","code","exam_id")->get()->toArray();

        $this->modal=true;
    }

    public function save(){

        $this->validate();
        try {
            DB::transaction(function () {
            $this->exam->save();
            foreach ($this->routines as $key => $value) {
                $value["exam_id"] = $this->exam->id;
                ExamRoutine::updateOrCreate(["id"=>array_key_exists('id', $value)?$value["id"]:null],$value);
            }
        });

        $this->success(title:"Added successfully");

        $this->modalClose();
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error(title:$th->getMessage());

        }

    }

    public function deleteRoutine($key) {
        if (array_key_exists('id', $this->routines[$key])) {
            ExamRoutine::find($this->routines[$key]["id"])->delete();
        }

        unset($this->routines[$key]);
    }

    public function delete($id){
        Exam::find($id)->delete();
        $this->success(title:"Deleted successfully");
    }


#[Computed]
public function academics_years(){
        return AcademicYear::where("active",true)->latest()->get();
    }
};

?>



<x-card title="Exam" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Exam" class="backdrop-blur" box-class="w-3/4 max-w-full">

            <x-form wire:submit.prevent="save">

                <x-choices
                label="Package"
                wire:model.live="exam.package_id"
                :options="$this->packages"
                single
                searchable />

                <x-choices
                label="Course"
                wire:model.live="exam.course_id"
                :options="$this->courses"
                single
                searchable />

                @if ($exam->package_id==1)

                <x-choices
                label="Group"
                wire:model.live="exam.group_id"
                :options="$this->groups"
                single
                searchable />
                @endif

                <x-choices
                label="Batch"
                wire:model.live="exam.batch_id"
                :options="$this->batches"
                single
                searchable />
                <x-input label="Name" wire:model="exam.name" />
                <x-choices class="select-sm" label="Academic Year" wire:model="exam.year" :options="$this->academics_years" option-value="year" option-label="year" single />


                <hr>
                <div class="font-bold">Subjects</div>

                @foreach ($routines as $key=>$value)
                <div class="flex justify-center item-center w-full gap-2">
                    <x-input type="number" label="Code" wire:model="routines.{{$key}}.code" />
                    <x-input label="Name" wire:model="routines.{{$key}}.name" />
                    <x-datetime label="Date" wire:model="routines.{{$key}}.date" />
                    <x-datetime type="time" label="Time" wire:model="routines.{{$key}}.time" />

                    <x-input type="number" label="Full Mark(CQ)" wire:model="routines.{{$key}}.cq_mark" />
                    <x-input type="number" label="Full Mark(MCQ)" wire:model="routines.{{$key}}.mcq_mark" />
                    <x-input type="number" label="Practical" wire:model="routines.{{$key}}.practical_mark" />
                    <div wire:confirm wire:click="deleteRoutine({{$key}})" class="btn btn-xs btn-error">X</div>
                </div>
                @endforeach


            <x-slot:actions>
                {{-- Notice `onclick` is HTML --}}
                <x-button label="Add Subject" wire:click="addSubject"/>
                <x-button type="submit" label="Save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
        </x-modal>

        {{-- Notice `onclick` is HTML --}}
        <x-button label="Add Exam" class="btn-primary btn-sm" @click="$wire.modalOpen()" />
    </div>

    <div class="flex justify-between">
        <x-choices label="Per page" wire:model.live="perPage" single :options='[
            ["id"=>10,"name"=>10],
            ["id"=>20,"name"=>20],
            ["id"=>100,"name"=>100],
        ]' option-value="name" />
        <div class="flex justify-end">

            <x-input label="Search" wire:model.debounce.500ms="search" />
        </div>
    </div>
    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"package.name","label"=>"Package"],
        ["key"=>"course.name","label"=>"Course"],
        ["key"=>"groupname","label"=>"Group"],
        ["key"=>"batchname","label"=>"Batch"],
        ["key"=>"name","label"=>"Name"],
        ["key"=>"year","label"=>"Year"],
    ]' :rows="$this->exams" with-pagination >

    @scope("cell_id",$exam)
    {{$this->loop->index+1}}
    @endscope

    @scope("cell_groupname",$exam)
    {{$exam->group?$exam->group->name:"all"}}
    @endscope
    @scope("cell_batchname",$exam)
    {{$exam->batch?$exam->batch->name:"all"}}
    @endscope

    @scope('actions', $exam)
    <div class="flex">

        <a href="{{route("exam.single",$exam->id)}}" class="btn btn-xs btn-primary text-white">Print</a>
        <x-button icon="o-pencil-square" @click="$wire.modalEditOpen({{ $exam->id }})" spinner class="btn-xs btn-primary text-white" />
        <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $exam->id }})" spinner class="btn-xs btn-error text-white" />
    </div>
    @endscope
    </x-table>
</x-card>
