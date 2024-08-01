<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Exam;
use App\Models\ExamRoutine;
use App\Models\ResultMark;
use App\Models\Result;
use App\Models\ResultSubject;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $result;
    public $perPage=20;
    public $search;

    public $routines = [];


    public bool $modal = false;

    public function rules()
    {
        return [
            'result.exam_id' => 'required',
            'result.status' => 'required',
            'routines.*.name' => 'required',
            'routines.*.code' => 'required',
            'routines.*.cq' => 'required|min:0',
            'routines.*.mcq' => 'required|min:0',
            'routines.*.practical' => 'required|min:0',
            'routines.*.firstpart' => '',

        ];
    }

    public function mount() {
        $this->result = new Result();
    }


     #[Computed]
     public function results()
    {
        return  Result::with(["exam"])
        ->latest()
        ->whereHas("exam",function($q) {
            return $q->where("name","like","%".$this->search."%");
        })
        ->paginate($this->perPage);
    }

    public function updatedResultExamId($value){
        if(!isset($value)){
            return;
        }
        $routines = ExamRoutine::where("exam_id",$value)->select("name","code")->orderBy("code")->get()->toArray();

        foreach ($routines as $routine ) {
            array_push($this->routines,[...$routine,"firstpart"=>""]);
        }
    }


     #[Computed]
     public function exams()
    {
        return  Exam::latest()->whereDoesntHave("result")->get();
    }



    public function modalClose(){
        $this->modal=false;
    }

    public function modalOpen(){

        $this->result = new Result();

        $this->routines = [];

        $this->modal=true;
    }




    public function save(){

        $this->validate();


        try {
            DB::transaction(function () {

            $this->result->save();

            $exam =  Exam::findOrFail($this->result->exam_id);
            $exam->load(["batch.students"=>fn($q)=>$q->where("year",$exam->year)]);

            $resultSubjects = [];
            foreach ($this->routines as $key => $routine) {
                $firstpart = null;
                if(isset($routine["firstpart"])){
                $firstpart = ResultSubject::where("result_id",$this->result->id)
                ->where("code",$routine["firstpart"])->first();

                if($firstpart){
                    $firstpart = $firstpart->id;
                }
                }

                    $resultSub = new ResultSubject();
                    $resultSub->name = $routine['name'];
                    $resultSub->result_id = $this->result->id;
                    $resultSub->code = $routine['code'];
                    $resultSub->cq_mark = $routine['cq'];
                    $resultSub->mcq_mark = $routine['mcq'];
                    $resultSub->practical_mark = $routine['practical'];
                    $resultSub->first_part_id = $firstpart;

                    $resultSub->save();

                    array_push($resultSubjects,$resultSub);

            }


        foreach ($exam->batch->students as $student) {
            foreach ($resultSubjects as $subject) {
                ResultMark::create([
                    "result_id"=>$this->result->id,
                    "student_id"=>$student->id,
                    "subject_id"=>$subject->id,
            ]);
            }
        }




        });

        $this->success(title:"Added successfully");

        $this->modalClose();
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error(title:$th->getMessage());
            dd($th);

        }

    }


    public function delete($id){
        Result::find($id)->delete();
        $this->success(title:"Deleted successfully");
    }

    public function  updateStatus($id){
        $result = Result::find($id);
        $result->status = !$result->status;
        $result->save();
        $this->success(title:"Updated Successfully");
    }



};

?>



<x-card title="Result" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Result" box-class="w-[90%] max-w-full" class="backdrop-blur">

            <x-form wire:submit.prevent="save">

                <x-choices
                label="Exam"
                wire:model.live="result.exam_id"
                :options="$this->exams"
                single
                searchable />

                <x-choices
                label="status"
                wire:model.live="result.status"
                :options='[["id"=>0,"name"=>"Not Published"],["id"=>1,"name"=>"Published"]]'
                single
                searchable />
<hr>
                @foreach ($routines as $key=>$value)
                <div class="flex justify-center item-center w-full gap-2">
                    <x-input label="Name" wire:model="routines.{{$key}}.name" />
                    <x-input label="Subject Codes" wire:model="routines.{{$key}}.code" />

                    <x-input type="number" label="Full Mark(CQ)" wire:model="routines.{{$key}}.cq" />
                    <x-input type="number" label="Full Mark(MCQ)" wire:model="routines.{{$key}}.mcq" />
                    <x-input type="number" label="Practical" wire:model="routines.{{$key}}.practical" />
                    <x-choices
                    label="First Part"
                    wire:model="routines.{{$key}}.firstpart"
                    :options='$routines'
                    option-value="code"
                    single
                    searchable />
                    <div wire:confirm wire:click="deleteRoutine({{$key}})" class="btn btn-xs btn-error">X</div>
                </div>
                @endforeach

            <x-slot:actions>
                {{-- Notice `onclick` is HTML --}}
                <x-button type="submit" label="Save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
        </x-modal>

        {{-- Notice `onclick` is HTML --}}
        <x-button label="Add Result" class="btn-primary btn-sm" @click="$wire.modalOpen()" />
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
        ["key"=>"exam.name","label"=>"Exam Name"],
        ["key"=>"exam.year","label"=>"Year"],
        ["key"=>"status","label"=>"Status"],
    ]' :rows="$this->results" with-pagination >

    @scope("cell_id",$result)
    {{$this->loop->index+1}}
    @endscope

    @scope("cell_status",$result)
    @if ($result->status=="0")
        Not Published yet
    @else
    Published
    @endif
    @endscope

    @scope('actions', $result)
    <div class="flex">

        <a href="{{route("exam.result.mark",$result->id)}}" class="btn btn-xs btn-primary text-white">Mark Entry</a>
        <x-button wire:confirm="Are you sure?" icon="o-eye" wire:click="updateStatus({{ $result->id }})" spinner class="btn-xs btn-success text-white" />
        <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $result->id }})" spinner class="btn-xs btn-error text-white" />
    </div>
    @endscope
    </x-table>
</x-card>
