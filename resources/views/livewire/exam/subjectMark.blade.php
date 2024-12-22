<?php

use function Livewire\Volt\{state,uses,computed,layout,rules,updated,on};
use App\Models\ResultSubject;
use App\Models\ResultMark;
use Mary\Traits\Toast;

uses(Toast::class);


state(["id","subject"=>null,"mark"=>[]]);



layout("layouts.app");
$resultSubjects=computed(function(){
    return ResultSubject::where("result_id",$this->id)->get();
});

on(["autoSave"=>function(){

    foreach ($this->mark as $m) {
        ResultMark::where("result_id",$m->result_id)->where("subject_id",$m->subject_id)->where("student_id",$m->student_id)->delete();
        $m->save();
    }

}]);


rules([
    "mark.*.result_id"=>"required",
    "mark.*.subject_id"=>"required",
    "mark.*.student_id"=>"required",
    "mark.*.cq"=>"required|numeric|min:0|max:100",
    "mark.*.mcq"=>"required|numeric|min:0|max:100",
    "mark.*.practical"=>"required|numeric|min:0|max:100",
    "mark.*.is_optional"=>"nullable|boolean"
]);



$marks=computed(function(){
    $marks=ResultMark::with("student")->where("result_id",$this->id)->where("subject_id",$this->subject)->get();
    foreach ($marks as $m) {
        $this->mark[$m->student_id]=$m;
    }
    return $marks;
});

$saveMark=function($student_id){


    ResultMark::where("result_id",$this->id)->where("subject_id",$this->subject)->where("student_id",$student_id)->delete();

    $this->mark[$student_id]->save();
    $this->success(title:"Mark saved successfully");
}

?>

<x-card>
    <x-choices wire:model.live="subject" single :options="$this->resultSubjects" />

    @if($this->subject)
        <x-card>
            <x-table :headers='[
                ["key"=>"student.name","label"=>"Name"],
                ["key"=>"student.roll","label"=>"Roll"],
                ["key"=>"cq","label"=>"CQ"],
                ["key"=>"mcq","label"=>"MCQ"],
                ["key"=>"practical","label"=>"Practical"],
                ["key"=>"optional","label"=>"Optional"],
                ["key"=>"updated_at","label"=>"Last Save"],
                ["key"=>"actions","label"=>"Actions"],
            ]' :rows="$this->marks"  >
                @scope("cell_cq",$m)
                <x-input class="input-sm" wire:model="mark.{{$m->student_id}}.cq" type="number" />
                @endscope
                @scope("cell_mcq",$m)
                <x-input class="input-sm" wire:model="mark.{{$m->student_id}}.mcq" type="number" />
                @endscope
                @scope("cell_practical",$m)
                <x-input class="input-sm" wire:model="mark.{{$m->student_id}}.practical" type="number" />
                @endscope
                @scope("cell_optional",$m)
                <x-checkbox wire:model="mark.{{$m->student_id}}.is_optional" />
                @endscope
                @scope("actions",$m)
                <x-button wire:click="saveMark({{$m->student_id}})" label="Save" class="btn-primary btn-xs" />
                @endscope
            </x-table>
        </x-card>
    @endif

    <script>

       //dispatch event to parent

       setInterval(function(){
        Livewire.dispatch("autoSave");
       },60000);

    </script>

</x-card>

