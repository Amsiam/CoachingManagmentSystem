<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;

use Livewire\WithFileUploads;


use Illuminate\Support\Facades\Hash;


use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


use App\Models\User;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination,WithFileUploads;

    public $perPage=20;
    public bool $modal = false;
    public $isEdit = 0;

    public $user;


    public $password;
    public $file;
    public $selectedPermission=[];
    public $selectedRoles=[];



    public function rules()
    {
        return [
            "user.name"=>"required",
            "user.email"=>"required",
            "password"=>"required_if:isEdit,0",
            "file"=>""
        ];
    }

    public function mount() {
        $this->user = new User();
    }

    #[Computed]
    public function users()
    {
        return  User::with(["permissions","roles"])->latest()->paginate($this->perPage);
    }

     #[Computed]
    public function roles()
    {
        return  Role::all();
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

        $this->user = new User();
        $this->selectedPermission=[];
        $this->selectedRoles=[];

        $this->isEdit = 0;
        $this->password = "";

        $this->modal=true;
    }

    public function modalEditOpen($id){

        $this->user = User::with(["roles","permissions"])->findOrFail($id);
        $this->selectedPermission=$this->user->permissions->pluck("name");
        $this->selectedRoles=$this->user->roles->pluck("name");

        $this->isEdit = 1;
        $this->password = "";
        $this->modal=true;
    }



    public function save(){

        $this->validate();

        if($this->password){
            $this->user->password = Hash::make($this->password);
        }

        if($this->file){
            $path = $this->file->store(path: 'public');

            $path = explode("public/",$path)[1];

            $this->user->image = $path;
        }


        $this->user->save();

        $this->user->syncPermissions($this->selectedPermission);
        $this->user->syncRoles($this->selectedRoles);


        $this->success(title:"Added successfully");

        $this->modalClose();


    }

    public function delete($id){
        User::find($id)->delete();
        $this->success(title:"Deleted successfully");
    }



};

?>



<x-card title="Admins" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add User" class="backdrop-blur">

            <x-form wire:submit.prevent="save">

            <x-input label="Name" wire:model="user.name" />

            <x-input label="Email" wire:model="user.email" />

            <x-input label="Password" wire:model="password" />

            <x-file wire:model.live="file" label="Photo" hint="Only Image" accept="image/png, image/jpeg" />
            @if ($file)
                <img src="{{ $file->temporaryUrl() }}">
            @endif

            <x-select wire:model="selectedRoles" option-value="name" :options="$this->roles" select label="Role" multiple />
            <x-select wire:model="selectedPermission" option-value="name" :options="$this->permissions" select label="Permissions" multiple />
            <x-slot:actions>

                <x-button label="Cancel" @click="$wire.modalClose()" />
                <x-button type="submit" label="Save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
        </x-modal>

        <x-button label="Add Admin" class="btn-primary btn-sm" @click="$wire.modalOpen()" />
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
        ["key"=>"image","label"=>"Image"],
        ["key"=>"name","label"=>"Name"],
        ["key"=>"email","label"=>"Email"],
        ["key"=>"role","label"=>"Roles"],
        ["key"=>"permission","label"=>"Permissions"]
    ]' :rows="$this->users" with-pagination >

    @scope("cell_id",$user)
    {{$this->loop->index+1}}
    @endscope


    @scope("cell_role",$user)

    @foreach ($user->roles as $role)
        <x-badge value="{{$role->name}}" class="badge-primary m-1" />
    @endforeach

    @endscope

    @scope("cell_image",$user)
        @if ($user->image)

            <img src="{{asset('storage/'.$user->image)}}" class="w-16 h-16 bg-gray-300 rounded-full mb-4 shrink-0"/>

        @endif
    @endscope

    @scope("cell_permission",$user)

    @foreach ($user->permissions as $permission)
        <x-badge value="{{$permission->name}}" class="badge-primary m-1" />
    @endforeach

    @endscope

    @scope('actions', $user)
    <div class="flex">
        <x-button icon="o-pencil" @click="$wire.modalEditOpen({{ $user->id }})" spinner class="btn-xs btn-primary text-white" />
        <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $user->id }})" spinner class="btn-xs btn-error text-white" />
    </div>
    @endscope
    </x-table>
</x-card>
