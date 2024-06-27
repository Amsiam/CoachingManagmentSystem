<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Exam;
use App\Models\Student;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $exam;
    public $perPage=20;
    public $search;


    public function mount($id) {
        $this->exam =  Exam::findOrFail($id);

        $this->exam->load(["batch.students"=>fn($q)=>$q->where("year",$this->exam->year)]);

    }







};

?>



<x-card title="Admit Cards" separator progress-indicator>
    <div class="flex justify-end">


        <a href="{{route("print.admit_card",[$exam->id,"all"])}}" class="btn btn-primary btn-sm">Print All</a>
    </div>

    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"name","label"=>"Name"],
        ["key"=>"roll","label"=>"Roll"],
    ]' :rows="$exam->batch->students->where('year',$exam->year)" >

    @scope("cell_id",$student)
    {{$this->loop->index+1}}
    @endscope

    @scope('actions', $student,$exam)
    <div class="flex">
        <a href="{{route("print.admit_card",[$exam->id,$student->id])}}" class="btn btn-primary btn-sm">Print</a>

     </div>
    @endscope
    </x-table>
</x-card>
