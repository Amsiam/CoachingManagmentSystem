<?php
use function Livewire\Volt\{state, uses,layout,mount,title};
use App\Models\Setting;

layout("layouts.app");




title("Auto SMS");

state([
    "auto_sms" => false,
    "message" => "",
]);

mount(function(){
    $this->auto_sms = Setting::where("key", "auto_sms")->first()?->value ?? false;

    if($this->auto_sms){
        $this->auto_sms = true;
    }else{
        $this->auto_sms = false;
    }

    $this->message = Setting::where("key", "auto_sms_message")->first()?->value ?? "";
});

$sms_send = function(){
    Setting::updateOrCreate(["key" => "auto_sms"], ["value" => $this->auto_sms]);
    Setting::updateOrCreate(["key" => "auto_sms_message"], ["value" => $this->message]);


};


?>




<x-card title="Auto DUE SMS" separator progress-indicator>



    <div>
        <form wire:submit="sms_send">
            <x-checkbox label="Auto SMS" wire:model="auto_sms" />
            <x-textarea label="Message" wire:model="message" placeholder="

            use STUDENT_NAME, STUDENT_ROLL, STUDENT_COURSE, STUDENT_BATCH to replace the values

            " rows="5" />



            <x-button type="submit" label="Send" class="btn-success" />
        </form>
    </div>



</x-card>
