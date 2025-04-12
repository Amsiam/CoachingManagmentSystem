<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title, Computed, Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Course;
use App\Models\Shift;

new
#[Layout('layouts.app')]
#[Title('Shifts')]
class extends Component {
    use Toast, WithPagination;

    #[Locked]
    public $id;

    #[Validate('required')]
    public $shift;

    public bool $modal = false;

    public function rules()
    {
        return [
            'shift.name' => 'required',
            'shift.course_id' => 'required',
        ];
    }

    public function mount()
    {
        $this->shift = new Shift();
    }

    public function createModalOpen()
    {
        $this->shift = new Shift();
        $this->modal = true;
    }

    #[Computed]
    public function courses()
    {
        return Course::all();
    }

    #[Computed]
    public function shifts()
    {
        return Shift::with('course')->latest()->paginate(20);
    }

    public function modalClose()
    {
        $this->modal = false;
    }

    public function editModalOpen($id)
    {
        $shift = Shift::find($id);

        if (!$shift) {
            $this->error('Shift Not Found');
            return;
        }

        $this->shift = $shift;

        $this->modal = true;
    }

    public function save()
    {
        $this->validate();

        $shift = $this->shift->save();

        $this->success(title: 'Added successfully');

        $this->modalClose();
    }

    public function delete($id)
    {
        Shift::find($id)->delete();

        $this->success(title: 'Deleted successfully');
    }
};

?>


<div>

    <x-card title="Shifts" separator progress-indicator>
        <div class="flex justify-end">
            <x-modal wire:model="modal" title="Add Shift" class="backdrop-blur">

                <x-form wire:submit.prevent="save">

                    <x-input label="Name" wire:model="shift.name" />

                    <x-choices label="Course" wire:model="shift.course_id" single :options="$this->courses" />


                    <x-slot:actions>
                        {{-- Notice `onclick` is HTML --}}
                        <x-button label="Cancel" class="btn-sm" wire:click="modalClose" />
                        <x-button type="submit" label="Save" class="btn-primary btn-sm" />
                    </x-slot:actions>
                </x-form>
            </x-modal>

            {{-- Notice `onclick` is HTML --}}
            <x-button label="Add Shifts" class="btn-primary btn-sm" @click="$wire.createModalOpen()" />
        </div>
        <x-table :headers="[
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Shift Name'],
            ['key' => 'course.name', 'label' => 'Course Name'],
        ]" :rows="$this->shifts" with-pagination>

            @scope('actions', $shift)
            <div class="flex">
                <x-button icon="o-pencil-square" class="btn-primary btn-sm"
                    @click="$wire.editModalOpen({{ $shift->id }})" />
                <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $shift->id }})" spinner
                    class="btn-sm btn-error" />
            </div>
            @endscope
        </x-table>
    </x-card>

</div>