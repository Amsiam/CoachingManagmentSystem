<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title, Computed, Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Group;
use App\Models\Classs;

new
#[Layout('layouts.app')]
#[Title('Classes')]
class extends Component {
    use Toast, WithPagination;

    #[Validate('required')]
    public $name = '';

    public $group_ids = [];
    public bool $modal = false;

    #[Computed]
    public function groups()
    {
        return Group::all();
    }

    #[Computed]
    public function classses()
    {
        return Classs::with('groups')->paginate(20);
    }

    public function modalClose()
    {
        $this->modal = false;
    }

    public function save()
    {
        $this->validate();

        $class = Classs::create(['name' => $this->name]);

        $class->groups()->sync($this->group_ids);

        $this->success(title: 'Added successfully');
        $this->name = '';
        $this->group_ids = [];

        $this->modalClose();
    }

    public function delete($id)
    {
        Classs::find($id)->delete();

        $this->success(title: 'Deleted successfully');
    }
};

?>



<x-card title="Classes" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Class" class="backdrop-blur">

            <x-form wire:submit.prevent="save">

                <x-input label="Name" wire:model="name" />

                <x-choices label="Groups" wire:model="group_ids" :options="$this->groups" allow-all />




                <x-slot:actions>
                    {{-- Notice `onclick` is HTML --}}
                    <x-button label="Cancel" @click="$wire.modal = false" />
                    <x-button type="submit" label="Save" class="btn-primary" />
                </x-slot:actions>
            </x-form>
        </x-modal>

        {{-- Notice `onclick` is HTML --}}
        <x-button label="Add Class" class="btn-primary btn-sm" @click="$wire.modal = true" />
    </div>
    <x-table :headers="[
        ['key' => 'id', 'label' => '#'],
        ['key' => 'name', 'label' => 'Class Name',],
        ['key' => 'groups', 'label' => 'Groups', ],
    ]" :rows="$this->classses" with-pagination>

        @scope('cell_id', $class)
            {{ $this->loop->index + 1 }}
        @endscope

        @scope('cell_groups', $class)
            @foreach ($class->groups as $group)

            <x-badge value="{{ $group->name }}" class="badge-primary" />

            @endforeach
        @endscope

        @scope('actions', $class)
            <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $class->id }})" spinner
                class="btn-sm btn-error text-white" />
        @endscope
    </x-table>
</x-card>
