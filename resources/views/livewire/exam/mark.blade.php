<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Result;
use App\Models\Student;
use App\Models\ResultMark;
use App\Models\Exam;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $resultId;
    public $perPage=20;
    public $search;
    public $subjects=[];
    public $modal=false;


    public function rules()
    {
        return [
            "subjects.*.cq" => "required",
            "subjects.*.mcq" => "required",
        ];
    }

    public function mount($id) {
        $this->resultId =  $id;

    }

    #[Computed()]

    public function result(){
        return Result::with(["marks"=>fn($q)=>$q->select("result_id","student_id")->distinct(),"marks.student"])

        ->find($this->resultId);
    }

    public function save(){

        $this->validate();
        try {
            DB::transaction(function () {
            foreach ($this->subjects as $key => $subject) {

                unset($subject["subject"]);

                ResultMark::where("result_id",$subject["result_id"])->
                    where("student_id",$subject["student_id"])->
                    where("subject_id",$subject["subject_id"])
                    ->update($subject);
            }
        });

        $this->success(title:"Added successfully");

        $this->modalClose();
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error(title:$th->getMessage());

        }

        }

        public function modalClose(){
        $this->modal=false;
    }
    public function modalEditOpen($id){
        $this->subjects = ResultMark::with("subject:id,name")
        ->where("result_id",$this->resultId)
        ->where("student_id",$id)->get()->toArray();

        $this->modal=true;
    }

};

?>



<x-card title="Mark Entry" separator progress-indicator>

    <x-modal wire:model="modal" title="Add Exam" class="backdrop-blur">

        <x-form wire:submit.prevent="save">
            <div class="font-bold">Subjects</div>

            @foreach ($subjects as $key=>$subject)

            <div class="flex justify-center item-center w-full gap-2">
                <x-input label="Name" wire:model="subjects.{{$key}}.subject.name" readonly />
                <x-input type="number" label="CQ Mark" wire:model="subjects.{{$key}}.cq" />
                <x-input type="number" label="MCQ Mark" wire:model="subjects.{{$key}}.mcq" />
            </div>
            @endforeach


        <x-slot:actions>
            <x-button type="submit" label="Save" class="btn-primary" />
        </x-slot:actions>
    </x-form>
    </x-modal>

    <div class="flex justify-end">



        {{-- <a href="{{route("print.admit_card",[$exam->id,"all"])}}" class="btn btn-primary btn-sm">Print All</a> --}}
    </div>


    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"student.name","label"=>"Name"],
        ["key"=>"student.roll","label"=>"Roll"],
    ]' :rows="$this->result->marks" >

    @scope("cell_id",$mark)
    {{$this->loop->index+1}}
    @endscope

    @scope('actions', $mark)
    <div class="flex">
        <x-button icon="o-pencil-square" @click="$wire.modalEditOpen({{ $mark->student_id }})" spinner class="btn-xs btn-primary text-white" />
     </div>
    @endscope
    </x-table>
</x-card>
