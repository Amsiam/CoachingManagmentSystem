<?php
use function Livewire\Volt\{state, uses,layout,mount,title,computed};
use App\Models\Setting;
use App\Models\Course;
layout("layouts.app");




title("Auto SMS");

state([
    "auto_sms" => false,
    "message" => "",
    "filterCourse" => []
]);

mount(function(){
    $this->auto_sms = Setting::where("key", "auto_sms")->first()?->value ?? false;
    $this->filterCourse = explode(",",Setting::where("key", "auto_sms_course")->first()?->value ?? "");

    if($this->auto_sms){
        $this->auto_sms = true;
    }else{
        $this->auto_sms = false;
    }

    $this->message = Setting::where("key", "auto_sms_message")->first()?->value ?? "";
});

$courses = computed(fn()=>Course::where("package_id",1)->get());

$sms_send = function(){
    Setting::updateOrCreate(["key" => "auto_sms"], ["value" => $this->auto_sms]);
    Setting::updateOrCreate(["key" => "auto_sms_course"], ["value" => implode(",",$this->filterCourse)]);
    Setting::updateOrCreate(["key" => "auto_sms_message"], ["value" => $this->message]);


};


?>




<x-card title="Auto DUE SMS" separator progress-indicator>



    <div>
        <form wire:submit="sms_send">
            <x-checkbox label="Auto SMS" wire:model="auto_sms" />


            <div class="lg:flex gap-2">
                <div class="lg:w-1/2">
                    <x-choices label="Course" :options="$this->courses" searchable wire:model.live="filterCourse" />
                </div>

            </div>

            <x-textarea label="Message" wire:model="message" placeholder="

            use STUDENT_NAME, STUDENT_ROLL, STUDENT_COURSE, STUDENT_BATCH to replace the values

            " rows="5" />



            <x-button type="submit" label="Send" class="btn-success" />
        </form>
    </div>



</x-card>
