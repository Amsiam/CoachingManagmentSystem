<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\ExpenseCategory;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $category;
    public bool $modal = false;

    public function rules()
    {
        return [
            'category.name' => 'required',
        ];
    }

    public function mount() {
        $this->category = new ExpenseCategory();
    }

     #[Computed]
    public function categories()
    {
        return  ExpenseCategory::paginate(20);
    }

    public function modalClose(){
        $this->modal=false;
    }

    public function modalOpen(){

        $this->category = new ExpenseCategory();
        $this->modal=true;
    }

    public function modalEditOpen($id){
        $this->category = ExpenseCategory::find($id);
        $this->modal=true;
    }

    public function save(){

        $this->validate();

        $this->category->save();

        $this->success(title:"Added successfully");

        $this->modalClose();


    }

    public function delete($id){
        ExpenseCategory::find($id)->delete();

        $this->success(title:"Deleted successfully");
    }



};

?>



<x-card title="Expense Categories" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Expense Categpory" class="backdrop-blur">

            <x-form wire:submit.prevent="save">

            <x-input label="Name" wire:model="category.name" />
            <x-slot:actions>
                {{-- Notice `onclick` is HTML --}}
                <x-button label="Cancel" @click="$wire.modalClose()" />
                <x-button type="submit" label="Save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
        </x-modal>

        {{-- Notice `onclick` is HTML --}}
        <x-button label="Add Expense Categpory" class="btn-primary btn-sm" @click="$wire.modalOpen()" />
    </div>
    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"name","label"=>"Category Name","class"=>"w-full"],
    ]' :rows="$this->categories" with-pagination >

    @scope("cell_id",$category)
    {{$this->loop->index+1}}
    @endscope

    @scope('actions', $category)
    <div class="flex">

        <x-button icon="o-pencil-square" @click="$wire.modalEditOpen({{ $category->id }})" spinner class="btn-sm btn-primary text-white" />
        <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $category->id }})" spinner class="btn-sm btn-error text-white" />
    </div>
    @endscope
    </x-table>
</x-card>
