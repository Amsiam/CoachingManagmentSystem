<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\{Book,ActivityLog};

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $book;
    public $perPage=20;
    public $search;

    public bool $modal = false;

    public function rules()
    {
        return [
            'book.name' => 'required',
            'book.author' => '',
            'book.price' => 'required',
        ];
    }

    public function mount() {
        $this->book = new Book();

    }



     #[Computed]
     public function books()
    {
        return  Book::
        when($this->search,function($q){
            return $q->where("name","like","%".$this->search."%");
        })
        ->paginate($this->perPage);
    }

    public function modalClose(){
        $this->modal=false;
    }

    public function modalOpen(){

        $this->book = new Book();

        $this->modal=true;
    }

    public function modalEditOpen($id){
        $this->book = Book::find($id);
        $this->modal=true;
    }

    public function save(){

        $this->validate();
        $activity = null;

        if ($this->book->id) {
            $activity = ActivityLog::create(
                [
                    "user_id" =>auth()->user()->id,
                    "performance" => "update",
                    "before_data" => Book::find($this->book->id)
                ]
            );
        }

        $this->book->save();

        if ($activity) {
            $activity->after_data = $this->book;
            $activity->save();
        }

        $this->success(title:"Added successfully");

        $this->modalClose();


    }

    public function delete($id){
        $book = Book::find($id);

        //save in log

        ActivityLog::create(
                [
                    "user_id" =>auth()->user()->id,
                    "performance" => "delete",
                    "before_data" => $book
                ]
            );

        $book->delete();

        $this->success(title:"Deleted successfully");
    }



};

?>



<x-card title="Book" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Book" class="backdrop-blur">

            <x-form wire:submit.prevent="save">


            <x-input label="Name" wire:model="book.name" />
            <x-input label="Author" wire:model="book.author" />
            <x-input label="Price" wire:model="book.price" />
            <x-slot:actions>
                {{-- Notice `onclick` is HTML --}}
                <x-button label="Cancel" @click="$wire.modalClose()" />
                <x-button type="submit" label="Save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
        </x-modal>

        {{-- Notice `onclick` is HTML --}}
        <x-button label="Add Book" class="btn-primary btn-sm" @click="$wire.modalOpen()" />
    </div>

    <div class="flex justify-between">
        <x-choices label="Per page" wire:model.live="perPage" single :options='[
            ["id"=>10,"name"=>10],
            ["id"=>20,"name"=>20],
            ["id"=>100,"name"=>100],
        ]' option-value="name" />
        <div class="flex justify-end">

            <x-input label="Search" wire:model.debounce.500ms="search" />
        </div>
    </div>
    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"name","label"=>"Name"],
        ["key"=>"author","label"=>"Author"],
        ["key"=>"price","label"=>"Price"],
    ]' :rows="$this->books" with-pagination >

    @scope("cell_id",$book)
    {{$this->loop->index+1}}
    @endscope

    @scope('actions', $book)
    <div class="flex">

        <x-button icon="o-pencil-square" @click="$wire.modalEditOpen({{ $book->id }})" spinner class="btn-sm btn-primary text-white" />
        <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $book->id }})" spinner class="btn-sm btn-error text-white" />
    </div>
    @endscope
    </x-table>
</x-card>
