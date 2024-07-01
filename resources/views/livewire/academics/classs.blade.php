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

    public $classs = [];

    public $group_ids = [];
    public bool $modal = false;

    public function rules()
    {
        return [
            'classs.name' => 'required',
        ];
    }

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

    public function modalOpen($id=null)
    {

        if($id){
            $this->classs = Classs::with("groups")->find($id);
            $this->group_ids = $this->classs->groups->pluck("id");
        }else{
            $this->classs = new Classs();
            $this->group_ids =[];
        }


        $this->modal = true;
    }

    public function save()
    {
        $this->validate();

        $this->classs->save();

        $this->classs->groups()->sync($this->group_ids);

        $this->success(title: 'Added successfully');

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

                <x-input label="Name" wire:model="classs.name" />

                <x-choices label="Groups" wire:model="group_ids" :options="$this->groups" allow-all />




                <x-slot:actions>
                    {{-- Notice `onclick` is HTML --}}
                    <x-button label="Cancel" @click="$wire.modal = false" />
                    <x-button type="submit" label="Save" class="btn-primary" />
                </x-slot:actions>
            </x-form>
        </x-modal>

        {{-- Notice `onclick` is HTML --}}
        <x-button label="Add Class" class="btn-primary btn-sm" wire:click="modalOpen" />
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

        @scope('actions', $class)<div class="flex gap-1">
            <x-button icon="o-pencil-square" class="btn-primary btn-xs" wire:click="modalOpen({{$class->id}})" />

            <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $class->id }})" spinner
                class="btn-xs btn-error text-white" />
                </div>
        @endscope
    </x-table>
</x-card>
