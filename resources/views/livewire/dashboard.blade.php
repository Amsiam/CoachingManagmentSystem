<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\User;

use App\Models\Payment;




use App\Exports\IncomeExport;


new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {

};

?>



<x-card title="Dashboard" separator progress-indicator>

    <x-card title="Hi, {{auth()->user()->name}}">
    </x-card>

    <div class="grid grid-cols-4 gap-2">

{{--
    <x-card class="shadow-lg" title="Total Student, {{date('Y')}} " shadow>
        <div class="text-center font-bold">1000</div>
    </x-card> --}}

    </div>


</x-card>
