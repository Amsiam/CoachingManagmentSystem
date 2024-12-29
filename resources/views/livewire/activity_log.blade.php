<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title, Computed, Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;

use App\Models\ActivityLog;

use App\SMS\PaymentSMS;

use App\Exports\AdmissionExport;
use Carbon\Carbon;

new #[Layout('layouts.app')] #[Title('Groups')] class extends Component {
    use Toast, WithPagination;

    public $perPage = 20;

    public $from;
    public $to;

    public function mount()
    {
        $this->from = Carbon::now()->startOfYear();
        $this->to = Carbon::now();
    }

    #[Computed]
    public function activityLogs()
    {
        return ActivityLog::with('user')
            ->latest()
            ->where('created_at', '>=', $this->from)
            ->where('created_at', '<=', $this->to)
            ->paginate($this->perPage);
    }
};

?>




<x-card title="Activity Log" separator progress-indicator>


    <div class="flex justify-between">
        <x-choices label="Per page" wire:model.live="perPage" single :options='[['id' => 10, 'name' => 10], ['id' => 20, 'name' => 20], ['id' => 100, 'name' => 100]]' option-value="name" />

        <div class="flex justify-end">
            <x-datetime label="From" wire:model.live="from" />
            <x-datetime label="to" wire:model.live="to" />
        </div>
    </div>
    <x-table :headers="[
        ['key' => 'user.name', 'label' => 'User'],
        ['key' => 'performance', 'label' => 'Performance'],
        ['key' => 'created_at', 'label' => 'Time'],
        ['key' => 'before_data', 'label' => ' আপডেটের পরের তথ্য'],
        ['key' => 'after_data', 'label' => ' পূর্বের ডেটা'],
    ]" :rows="$this->activityLogs" with-pagination>

    </x-table>


</x-card>
