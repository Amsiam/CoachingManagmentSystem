<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;


use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $perPage=20;
    public bool $modal = false;

    public $role;


    public $selectedPermission=[];



    public function rules()
    {
        return [
            "role.name"=>"required",
            "role.guard_name"=>""
        ];
    }

    public function mount() {
        $this->role = new Role();
    }

     #[Computed]
    public function roles()
    {
        return  Role::with("permissions")->latest()->paginate($this->perPage);
    }

    #[Computed]
     public function permissions()
    {
        return  Permission::all();
    }



    public function modalClose(){
        $this->modal=false;
    }

    public function modalOpen(){

        $this->role = new Role();
        $this->selectedPermission=[];

        $this->modal=true;
    }

    public function modalEdit($id){

        $this->role = Role::with("permissions")->findOrFail($id);
        $this->selectedPermission=$this->role->permissions->pluck("name");

        $this->modal=true;
    }



    public function save(){

        $this->validate();

        $this->role->guard_name = "web";
        $this->role->save();

        $this->role->syncPermissions($this->selectedPermission);

        $this->success(title:"Added successfully");

        $this->modalClose();


    }

    public function delete($id){
        Role::find($id)->delete();
        $this->success(title:"Deleted successfully");
    }



};

?>



<x-card title="Roles" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Role" class="backdrop-blur">

            <x-form wire:submit.prevent="save">

            <x-input label="Name" wire:model="role.name" />
            <x-select wire:model="selectedPermission" option-value="name" :options="$this->permissions" select label="Permissions" multiple />
            <x-slot:actions>

                <x-button label="Cancel" @click="$wire.modalClose()" />
                <x-button type="submit" label="Save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
        </x-modal>

        <x-button label="Add Role" class="btn-primary btn-sm" @click="$wire.modalOpen()" />
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
        ["key"=>"name","label"=>"Name"],
        ["key"=>"permission","label"=>"Permissions"]
    ]' :rows="$this->roles" with-pagination >

    @scope("cell_id",$role)
    {{$this->loop->index+1}}
    @endscope

    @scope("cell_permission",$role)

    @foreach ($role->permissions as $permission)
        <x-badge value="{{$permission->name}}" class="badge-primary m-1" />
    @endforeach

    @endscope

    @scope('actions', $role)
    <div class="flex">
        <x-button  icon="o-pencil" @click="$wire.modalEdit({{ $role->id }})" spinner class="btn-xs btn-primary text-white" />
        <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $role->id }})" spinner class="btn-xs btn-error text-white" />
    </div>
    @endscope
    </x-table>
</x-card>
