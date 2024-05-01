<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title, Computed, Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Group;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Package;

new
#[Layout('layouts.app')]
#[Title('Classes')]
class extends Component {
    use Toast, WithPagination;

     #[Locked]
     public $id;

    #[Validate('required')]
    public $batch;


    public $groups;

    public bool $modal = false;

    public function rules()
    {
        return [
            'batch.name' => 'required',
            'batch.time' => 'required',
            'batch.roll_start' => 'required',
            'batch.course_id' => 'required',
            'batch.group_id' => 'required_with:groups',
        ];
    }

    public function mount(){
        $this->batch = new Batch();
    }


    public function createModalOpen(){
        $this->batch = new Batch();

        $this->modal = true;
    }

    #[Computed]
    public function groups()
    {
        return Group::all();
    }

    public function updatedBatchCourseId($val) {

        if($val){
        $course = Course::with("classs.groups")->find($val);

        if(!$course || !$course->classs || !$course->classs->groups){
            $this->groups = null;
            return;
        }

        $this->groups = $course->classs->groups;


        }

    }


    #[Computed]
    public function courses()
    {
        return Course::all();
    }

    #[Computed]
    public function batches()
    {
        return Batch::with(["course","group"])->paginate(20);
    }

    public function modalClose()
    {
        $this->modal = false;
    }

    public function editModalOpen($id)
    {
        $batch = Batch::find($id);



        if(!$batch){
            $this->error("Batch Not Found");
            return;
        }
        $this->batch = $batch;
        $this->modal = true;
    }

    public function save()
    {
        $this->validate();

        $this->batch->roll_current = $this->batch->roll_start;

        $batch = $this->batch->save();


        $this->success(title: 'Added successfully');


        $this->modalClose();
    }

    public function delete($id)
    {
        Batch::find($id)->delete();

        $this->success(title: 'Deleted successfully');
    }
};

?>



<x-card title="Batches" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Batches" class="backdrop-blur">

            <x-form wire:submit.prevent="save">

                <x-input label="Name" wire:model="batch.name" />
                <x-input label="Time" wire:model="batch.time" />
                <x-input label="Roll Start" wire:model="batch.roll_start" />

                <x-choices label="Course" wire:model.live="batch.course_id" single :options="$this->courses" />

                @if($groups)
                <x-choices label="Group" wire:model="batch.group_id" single :options="$groups" />
                @endif



                <x-slot:actions>
                    <x-button type="button" label="Cancel" wire:click.stop="modalClose" />
                    <x-button type="submit" label="Save" class="btn-primary" />
                </x-slot:actions>
            </x-form>
        </x-modal>

        {{-- Notice `onclick` is HTML --}}
        <x-button label="Add Batche" class="btn-primary btn-sm" @click="$wire.createModalOpen()" />
    </div>
    <x-table :headers="[
        ['key' => 'id', 'label' => '#'],
        ['key' => 'name', 'label' => 'Batch Name',],
        ['key' => 'time', 'label' => 'Batch Time',],
        ['key' => 'roll_start', 'label' => 'Roll Start',],
        ['key' => 'course.name', 'label' => 'Course', ],
        ['key' => 'group.name', 'label' => 'Group',],
    ]" :rows="$this->batches" with-pagination>

        @scope('cell_id', $batche)
            {{ $this->loop->index + 1 }}
        @endscope

        @scope('actions', $batche)
<div class="flex">
        <x-button icon="o-pencil-square" class="btn-primary btn-sm" @click="$wire.editModalOpen({{$batche->id}})" />
            <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $batche->id }})" spinner
                class="btn-sm btn-error" />
            </div>
        @endscope
    </x-table>
</x-card>
