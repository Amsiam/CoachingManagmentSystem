<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Illuminate\Database\Eloquent\Collection;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\BookSell;

use App\Models\Book;
use App\Models\User;

use App\Exports\BookSellExport;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $addedBy;

    public $bookSell;
    public $price=0;
    public $books_selected=[];


    public $perPage=20;
    public $from;
    public $to;

    public bool $modal = false;

    public Collection $bookSearchable;

    public function search(string $value = '')
    {
        $selectedOption = Book::where('id', $this->books_selected)->get();

        $this->bookSearchable = Book::query()
            ->where('name', 'like', "%$value%")
            ->take(5)
            ->orderBy('name')
            ->get()
            ->merge($selectedOption);
    }

     #[Computed]
     public function users()
    {
        return  User::all();

    }


    public function rules()
    {
        return [
            'bookSell.description' => '',
            'bookSell.totalBook' => 'required',
            'bookSell.price' => 'required',
            'bookSell.date' => 'required',
        ];
    }



    public function mount() {
        $this->bookSell = new BookSell();

        $this->from = date("Y-m-d");
        $this->to = date("Y-m-d");

        $this->addedBy = auth()->user()->email;

        $this->search();

    }


     #[Computed]
     public function bookSells()
    {
        return  BookSell::whereBetween("date",[$this->from,$this->to])
        ->where("added_by",$this->addedBy)
        ->paginate($this->perPage);
    }


    public function updated($property)
    {
        $this->price =   Book::whereIn("id",$this->books_selected)->sum("price");
    }

    public function modalClose(){
        $this->modal=false;
    }

    public function modalOpen(){

        $this->bookSell = new BookSell();

        $this->price = 0;


        $this->bookSell->date=date("Y-m-d");
        $this->books_selected=[];
        $this->modal=true;
    }


    public function save(){

        $this->bookSell->totalBook = count($this->books_selected);
        $this->bookSell->price = $this->price;

        $this->bookSell->added_by = auth()->user()->email;

        $this->validate();



        $this->bookSell->save();

        $this->success(title:"Added successfully");

        $this->modalClose();


    }

    public function delete($id){
        BookSell::find($id)->delete();
        $this->success(title:"Deleted successfully");
    }


    public function export(){
        return Excel::download(new BookSellExport(
            $this->from,
            $this->to
        ),date("Y-m-d H:s a")."-book-sell-export.xlsx");
    }



    public function pdf(){
        return Excel::download(new BookSellExport(
            $this->from,
            $this->to
        ),date("Y-m-d H:s a")."-book-sell-export.pdf",\Maatwebsite\Excel\Excel::MPDF);
    }



};

?>



<x-card title="Book Sell" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Sell" class="backdrop-blur">

            <x-form wire:submit.prevent="save">


                <x-input label="Description" wire:model="bookSell.description" />
                <x-choices
                label="Books"
                wire:model.live="books_selected"
                :options="$bookSearchable"
                search-function="search"
                no-result-text="Ops! Nothing here ..."
                searchable />

            <x-input label="Price" readonly wire:model="price" />
            <x-datetime label="Date" wire:model="bookSell.date" />
            <x-slot:actions>
                {{-- Notice `onclick` is HTML --}}
                <x-button label="Cancel" @click="$wire.modalClose()" />
                <x-button type="submit" label="Save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
        </x-modal>


@can("report.excel")
<x-button label="Export" class="btn-warning btn-sm" wire:click="export" />

@endcan
<x-button label="PDF" class="btn-accent btn-sm" wire:click="pdf" />

        <x-button label="Add Sell" class="btn-primary btn-sm" @click="$wire.modalOpen()" />
    </div>

    <div class="flex justify-between">
        <x-choices label="Per page" wire:model.live="perPage" single :options='[
            ["id"=>10,"name"=>10],
            ["id"=>20,"name"=>20],
            ["id"=>100,"name"=>100],
        ]' option-value="name" />

        <div class="w-80">
            <x-choices label="Added By" :options="$this->users" option-value="email" single searchable wire:model.live="addedBy"  />
        </div>

        <div class="flex justify-end">

            <x-datetime label="From" wire:model.live="from" />
            <x-datetime label="to" wire:model.live="to" />
        </div>
    </div>
    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"description","label"=>"Description"],
        ["key"=>"totalBook","label"=>"Total Book"],
        ["key"=>"price","label"=>"Price"],
        ["key"=>"date","label"=>"Date"],
        ["key"=>"added_by","label"=>"Added By"],
    ]' :rows="$this->bookSells" with-pagination >

    @scope("cell_id",$bookSell)
    {{$this->loop->index+1}}
    @endscope

    @scope('actions', $bookSell)
    <div class="flex">

        <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $bookSell->id }})" spinner class="btn-xs btn-error text-white" />
    </div>
    @endscope
    </x-table>
</x-card>
