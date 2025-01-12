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
use App\Models\Student;


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

    public $subCourses ;

    public function rules()
    {
        return [
            'course.name' => 'required',
            'course.price' => 'required',
            'course.package_id' => 'required',
            'course.classs_id' => '',
            'course.longDesc' => '',
            'course.shortDesc' => '',
            'course.featured' => '',
            "file"=>"",
            "subCourses.*.name"=>"",
            "subCourses.*.price"=>"",

        ];
    }

    public function mount(){

        $this->course = new Course();
        $this->course->package_id = 1;
        $this->subCourses = collect([]);

        // dd($this->course);
    }

    public function createModalOpen(){
        $this->course = new Course();
        $this->course->package_id = 1;
        $this->course->featured = 0;
        $this->file = "";

        $this->subCourses = collect([]);

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
        return Course::with(['classs',"package"])->whereNull("parent_id")->paginate(20);
    }

    public function modalClose()
    {
        $this->modal = false;

    }

    public function editModalOpen($id)
    {
        $course = Course::with("subCourses")->find($id);

        $this->file = "";

        if(!$course){
            $this->error("Course Not Found");
            return;
        }



        $this->course = $course;

        $this->subCourses = $course->subCourses;

        $this->modal = true;
    }

    public function save()
    {

        if($this->course->featured){
            $this->course->featured=1;
        }else{
            $this->course->featured=0;
        }

        $this->validate();

        if($this->file){
            $path = $this->file->store(path: 'public');

            $path = explode("public/",$path)[1];


            $this->course->image = $path;
        }

        $course = $this->course->save();

        if($this->subCourses->count()>0){
            foreach ($this->subCourses as  $subCourse) {
                $subCourse->parent_id = $this->course->id;
                $subCourse->package_id = $this->course->package_id;
                $subCourse->classs_id = $this->course->classs_id;
                $subCourse->save();
            }
        }



        $this->success(title: 'Added successfully');


        $this->modalClose();
    }

    public function delete($id)
    {
        $studentCount = Student::whereHas("courses",function($q) use ($id) { return $q->where("id",$id); })->count();

        if($studentCount>0){
            $this->error("Course has student. Can't delete");
            return;
        }

        Course::find($id)->delete();

        $this->success(title: 'Deleted successfully');
    }

    public function addSubCourse($id=null){

        $subCouese = new Course();

        $subCouese->parent_id = $id;

        $this->subCourses->push($subCouese);

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


                <x-checkbox label="Featured Course?" wire:model="course.featured" right />

                @foreach ($subCourses as $key => $val)
                    <div key="{{$key}}" class="flex justify-center items-center">
                        <x-input label="Name" wire:model="subCourses.{{$key}}.name" />
                        <x-input label="Price" wire:model="subCourses.{{$key}}.price" />
                    </div>
                @endforeach

                <x-slot:actions>
                    {{-- Notice `onclick` is HTML --}}
                    <x-button label="Sub Course" class="btn-secondary btn-sm" wire:click="addSubCourse({{$this->course->id}})" />
                    <x-button label="Cancel" class="btn-sm" wire:click="modalClose" />
                    <x-button type="submit" label="Save" class="btn-primary btn-sm" />
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
