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

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $result;
    public $perPage=20;
    public $search;


    public bool $modal = false;

    public function rules()
    {
        return [
            'result.exam_id' => 'required',
            'result.status' => 'required',

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

        $this->modal=true;
    }




    public function save(){

        $this->validate();



        try {
            DB::transaction(function () {
            $this->result->save();

            $exam =  Exam::with("exam_routines")->findOrFail($this->result->exam_id);

        $exam->load(["batch.students"=>fn($q)=>$q->where("year",$exam->year)]);

        foreach ($exam->batch->students as $student) {
            foreach ($exam->exam_routines as $subject) {
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

        }

    }

    public function delete($id){
        Result::find($id)->delete();
        $this->success(title:"Deleted successfully");
    }



};

?>



<x-card title="Result" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Result" class="backdrop-blur">

            <x-form wire:submit.prevent="save">

                <x-choices
                label="Exam"
                wire:model="result.exam_id"
                :options="$this->exams"
                single
                searchable />

                <x-choices
                label="status"
                wire:model.live="result.status"
                :options='[["id"=>0,"name"=>"Not Published"],["id"=>1,"name"=>"Published"]]'
                single
                searchable />
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
        <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $result->id }})" spinner class="btn-xs btn-error text-white" />
    </div>
    @endscope
    </x-table>
</x-card>
