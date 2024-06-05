<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title, Computed, Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Group;
use App\Models\Classs;
use App\Models\Course;
use App\Models\Package;


use Livewire\WithFileUploads;


new
#[Layout('layouts.app')]
#[Title('Classes')]
class extends Component {
    use Toast, WithPagination,WithFileUploads;

     #[Locked]
     public $id;

    #[Validate('required')]
    public $course;

    public $file;



    public bool $modal = false;

    public function rules()
    {
        return [
            'course.name' => 'required',
            'course.price' => 'required',
            'course.package_id' => 'required',
            'course.classs_id' => '',
            'course.longDesc' => '',
            'course.shortDesc' => '',
            "file"=>""
        ];
    }

    public function mount(){

        $this->course = new Course();
        $this->course->package_id = 1;

        // dd($this->course);
    }

    public function createModalOpen(){
        $this->course = new Course();
        $this->course->package_id = 1;
        $this->file = "";

        $this->modal = true;
    }

    #[Computed]
    public function classses()
    {
        return Classs::all();
    }

    #[Computed]
    public function packages()
    {
        return Package::all();
    }

    #[Computed]
    public function courses()
    {
        return Course::with(['classs',"package"])->paginate(20);
    }

    public function modalClose()
    {
        $this->modal = false;

    }

    public function editModalOpen($id)
    {
        $course = Course::find($id);

        $this->file = "";

        if(!$course){
            $this->error("Course Not Found");
            return;
        }
        $this->course = $course;
        $this->modal = true;
    }

    public function save()
    {
        $this->validate();

        if($this->file){
            $path = $this->file->store(path: 'public');

            $path = explode("public/",$path)[1];


            $this->course->image = $path;
        }

        $course = $this->course->save();



        $this->success(title: 'Added successfully');


        $this->modalClose();
    }

    public function delete($id)
    {
        Course::find($id)->delete();

        $this->success(title: 'Deleted successfully');
    }
};

?>


<div>

<x-card title="Courses" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Courses" class="backdrop-blur">

            <x-form wire:submit.prevent="save">

                <x-input label="Name" wire:model="course.name" />
                <x-input label="Price" wire:model="course.price" />

                <x-choices label="Class" wire:model="course.classs_id" single :options="$this->classses" />
                <x-choices label="Package" wire:model="course.package_id" single :options="$this->packages" />

                    <x-textarea
                    label="Short Description"
                    wire:model="course.shortDesc"
                    rows="5"
                    inline />
                    <x-textarea
                    label="Long Description"
                    wire:model="course.longDesc"
                    rows="5"
                    inline />

                    <x-file wire:model.live="file"  label="Photo" accept="image/*"/>
                    @if ($file)
                        <img src="{{ $file->temporaryUrl() }}">
                    @endif

                <x-slot:actions>
                    {{-- Notice `onclick` is HTML --}}
                    <x-button label="Cancel" wire:click="modalClose" />
                    <x-button type="submit" label="Save" class="btn-primary" />
                </x-slot:actions>
            </x-form>
        </x-modal>

        {{-- Notice `onclick` is HTML --}}
        <x-button label="Add Course" class="btn-primary btn-sm" @click="$wire.createModalOpen()" />
    </div>
    <x-table :headers="[
        ['key' => 'id', 'label' => '#'],
        ['key' => 'name', 'label' => 'Course Name',],
        ['key' => 'price', 'label' => 'Course Price',],
        ['key' => 'classs.name', 'label' => 'Course For', ],
        ['key' => 'package.name', 'label' => 'Package',],
    ]" :rows="$this->courses" with-pagination>

        @scope('cell_id', $course)
            {{ $this->loop->index + 1 }}
        @endscope

        @scope('actions', $course)
<div class="flex">
        <x-button icon="o-pencil-square" class="btn-primary btn-sm" @click="$wire.editModalOpen({{$course->id}})" />
            <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $course->id }})" spinner
                class="btn-sm btn-error" />
            </div>
        @endscope
    </x-table>
</x-card>

</div>
