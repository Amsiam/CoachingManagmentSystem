<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Group;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $group = [];
    public bool $modal = false;

    public function rules()
    {
        return [
            'group.name' => 'required',
        ];
    }

     #[Computed]
    public function groups()
    {
        return  Group::paginate(20);
    }

    public function modalClose(){
        $this->modal=false;
    }

    public function modalOpen($id=null){

        if(isset($id)){
            $this->group = Group::find($id);
        }else{
            $this->group = new Group();
        }

        $this->modal=true;
    }

    public function save(){

        $this->validate();

        $this->group->save();

        $this->success(title:"Added successfully");

        $this->modalClose();


    }

    public function delete($id){
        Group::find($id)->delete();

        $this->success(title:"Deleted successfully");
    }



};

?>



<x-card title="Groups" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Group" class="backdrop-blur">

            <x-form wire:submit.prevent="save">

            <x-input label="Name" wire:model="group.name" />




            <x-slot:actions>
                {{-- Notice `onclick` is HTML --}}
                <x-button label="Cancel" @click="$wire.modal = false" />
                <x-button type="submit" label="Save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
        </x-modal>

        {{-- Notice `onclick` is HTML --}}
        <x-button label="Add Group" class="btn-primary btn-sm" wire:click="modalOpen()" />
    </div>
    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"name","label"=>"Group Name","class"=>"w-full"],
    ]' :rows="$this->groups" with-pagination >

    @scope("cell_id",$group)
    {{$this->loop->index+1}}
    @endscope

    @scope('actions', $group)
    <div class="flex gap-1">
        <x-button icon="o-pencil-square" class="btn-primary btn-xs" wire:click="modalOpen({{$group->id}})" />
            <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $group->id }})" spinner class="btn-xs btn-error text-white" />

    </div>
    @endscope
    </x-table>
</x-card>
