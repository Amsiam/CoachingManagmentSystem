<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title, Computed, Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Slider;
use App\Models\StudentReview;
use Livewire\WithFileUploads;

new
    #[Layout('layouts.app')]
    #[Title("Groups")]
    class extends Component
    {
        use Toast, WithPagination, WithFileUploads;



        #[Validate('required')]
        public $file = "";

        #[Validate([
            "student.name" => 'required',
            "student.exam_year" => 'required',
            "student.rank" => 'required',
            "student.desc" => 'required',
        ])]
        public $student;
        public bool $modal = false;

        #[Computed]
        public function sliders()
        {
            return  StudentReview::paginate(20);
        }

        public function mount()
        {
            $this->student = new StudentReview();
        }



        public function modalClose()
        {
            $this->modal = false;
        }

        public function openModal()
        {
            $this->student = new StudentReview();
            $this->modal = true;
        }

        public function save()
        {

            $this->validate();
            $path = $this->file->store(path: 'public');

            $path = explode("public/", $path)[1];

            $this->student->image = $path;

            $this->student->save();
            $this->success(title: "Added successfully");
            $this->file = "";
            $this->modalClose();
        }

        public function delete($id)
        {
            StudentReview::find($id)->delete();

            $this->success(title: "Deleted successfully");
        }
    };

?>



<x-card title="Student Reviews" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Slider" class="backdrop-blur">

            <x-form wire:submit.prevent="save">
                <x-input label="Name" wire:model="student.name" />
                <x-input label="Exam Year" wire:model="student.exam_year" />
                <x-input label="Rank" wire:model="student.rank" />
                <x-textarea label="Desc" wire:model="student.desc" rows="5" />
                <x-file wire:model.live="file" label="Photo" accept="image/*" />
                @if ($file)
                <img src="{{ $file->temporaryUrl() }}">
                @endif


                <x-slot:actions>
                    {{-- Notice `onclick` is HTML --}}
                    <x-button label="Cancel" @click="$wire.modal = false" />
                    <x-button type="submit" label="Save" class="btn-primary" />
                </x-slot:actions>
            </x-form>
        </x-modal>

        {{-- Notice `onclick` is HTML --}}
        <x-button label="Add Slider" class="btn-primary btn-sm" wire:click="openModal" />
    </div>
    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"image","label"=>"Image"],
        ["key"=>"name","label"=>"Name"],
        ["key"=>"exam_year","label"=>"Exam Year"],
        ["key"=>"rank","label"=>"Rank"],
    ]' :rows="$this->sliders" with-pagination>

        @scope("cell_id",$group)
        {{$this->loop->index+1}}
        @endscope

        @scope("cell_image",$slide)
        <img src="{{asset('storage/'.$slide->image)}}" width="100" height="100" />
        @endscope


        @scope('actions', $slide)
        <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $slide->id }})" spinner class="btn-sm btn-error text-white" />
        @endscope
    </x-table>
</x-card>