<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Slider;

use Livewire\WithFileUploads;
new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination,WithFileUploads;



     #[Validate('required')]
    public $file="";

    public $type=1;
    public bool $modal = false;

     #[Computed]
    public function sliders()
    {
        return  Slider::paginate(20);
    }



    public function modalClose(){
        $this->modal=false;
    }

    public function save(){

        $this->validate();
        $path = $this->file->store(path: 'public');

        $path = explode("public/",$path)[1];


        Slider::create(["image"=>$path,"type"=>$this->type]);

        $this->success(title:"Added successfully");
        $this->file="";

        $this->modalClose();


    }

    public function delete($id){
        Slider::find($id)->delete();

        $this->success(title:"Deleted successfully");
    }



};

?>



<x-card title="Slider Image" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Slider" class="backdrop-blur">

            <x-form wire:submit.prevent="save">
                <x-select label="Type" :options='[["id"=>1,"name"=>"Slider"],["id"=>2,"name"=>"অবাক করা সাফল্যগাঁথা"]]' wire:model="type" />

                <x-file wire:model.live="file"  label="Photo" accept="image/*"/>
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
        <x-button label="Add Slider" class="btn-primary btn-sm" @click="$wire.modal = true" />
    </div>
    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"image","label"=>"Image","class"=>"w-1/2"],
        ["key"=>"type","label"=>"Type"],
    ]' :rows="$this->sliders" with-pagination >

    @scope("cell_id",$group)
    {{$this->loop->index+1}}
    @endscope

    @scope("cell_image",$slide)
    <img src="{{asset('storage/'.$slide->image)}}" width="100" height="100" />
    @endscope

    @scope("cell_type",$slide)
        @if ($slide->type==1)
            Slider
        @elseif ($slide->type==2)
        অবাক করা সাফল্যগাঁথা
        @endif
    @endscope

    @scope('actions', $slide)
        <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $slide->id }})" spinner class="btn-sm btn-error text-white" />
    @endscope
    </x-table>
</x-card>
