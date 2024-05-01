<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;


use Spatie\Permission\Models\Permission;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $perPage=20;
    public bool $modal = false;

    public $permission;

    public function rules()
    {
        return [
            "permission.name"=>"required",
            "permission.guard_name"=>""
        ];
    }

    public function mount() {
        $this->permission = new Permission();
    }

     #[Computed]
    public function permissions()
    {
        return  Permission::latest()->paginate($this->perPage);
    }



    public function modalClose(){
        $this->modal=false;
    }

    public function modalOpen(){

        $this->permission = new Permission();

        $this->modal=true;
    }



    public function save(){

        $this->validate();

        $this->permission->guard_name = "web";
        $this->permission->save();

        $this->success(title:"Added successfully");

        $this->modalClose();


    }

    public function delete($id){
        Permission::find($id)->delete();
        $this->success(title:"Deleted successfully");
    }



};

?>



<x-card title="Permission" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Permission" class="backdrop-blur">

            <x-form wire:submit.prevent="save">

            <x-input label="Name" wire:model="permission.name" />
            <x-slot:actions>

                <x-button label="Cancel" @click="$wire.modalClose()" />
                <x-button type="submit" label="Save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
        </x-modal>

        <x-button label="Add Permission" class="btn-primary btn-sm" @click="$wire.modalOpen()" />
    </div>

    <div class="flex justify-between">
        <x-choices label="Per page" wire:model.live="perPage" single :options='[
            ["id"=>10,"name"=>10],
            ["id"=>20,"name"=>20],
            ["id"=>100,"name"=>100],
        ]' option-value="name" />

        <div class="w-96">

        </div>

        <div class="flex justify-end">

        </div>
    </div>
    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"name","label"=>"Name"]
    ]' :rows="$this->permissions" with-pagination >

    @scope("cell_id",$permission)
    {{$this->loop->index+1}}
    @endscope

    @scope('actions', $permission)
    <div class="flex">
        <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $permission->id }})" spinner class="btn-xs btn-error text-white" />
    </div>
    @endscope
    </x-table>
</x-card>
