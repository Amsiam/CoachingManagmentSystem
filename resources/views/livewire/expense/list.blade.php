<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\ExpenseCategory;

use App\Models\Expense;

use App\Exports\ExpenseExport;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $category;
    public $perPage=20;
    public $from;
    public $to;
    public $type=[];
    public bool $modal = false;

    public function rules()
    {
        return [
            'category.type' => 'required',
            'category.desc' => 'required',
            'category.amount' => 'required',
            'category.date' => 'required',
            'category.user_id' => '',
        ];
    }

    public function mount() {
        $this->category = new Expense();
        $this->category->user_id = auth()->user()->id;
        $this->from = date("Y-m-d");
        $this->to = date("Y-m-d");

    }

     #[Computed]
    public function expenseCategories()
    {
        return  ExpenseCategory::all();
    }

     #[Computed]
     public function expenses()
    {
        return  Expense::when(
            $this->type!=[],function($q) {
                return $q->whereIn("type",$this->type);
            }
        )->whereBetween("date",[$this->from,$this->to])->paginate($this->perPage);
    }


      #[Computed]
      public function totalExpense()
    {
        return  Expense::when(
            $this->type!=[],function($q) {
                return $q->whereIn("type",$this->type);
            }
        )->whereBetween("date",[$this->from,$this->to])->sum("amount");
    }

    public function modalClose(){
        $this->modal=false;
    }

    public function modalOpen(){

        $this->category = new Expense();
        $this->category->user_id = auth()->user()->id;

        $this->category->date=date("Y-m-d");
        $this->modal=true;
    }

    public function modalEditOpen($id){
        $this->category = Expense::find($id);
        $this->modal=true;
    }

    public function save(){

        $this->validate();

        $this->category->save();

        $this->success(title:"Added successfully");

        $this->modalClose();


    }

    public function delete($id){
        Expense::find($id)->delete();
        $this->success(title:"Deleted successfully");
    }


    public function export(){
        return Excel::download(new ExpenseExport(
            $this->from,
            $this->to,$this->type
        ),date("Y-m-d H:s a")."-Student-export.xlsx");
    }




};

?>



<x-card title="Expenses" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Expense" class="backdrop-blur">

            <x-form wire:submit.prevent="save">


            <x-choices label="Type" wire:model="category.type" single :options="$this->expenseCategories" option-value="name" />

            <x-input label="Description" wire:model="category.desc" />
            <x-input label="Amount" wire:model="category.amount" />
            <x-datetime label="Date" wire:model="category.date" />
            <x-slot:actions>
                {{-- Notice `onclick` is HTML --}}
                <x-button label="Cancel" @click="$wire.modalClose()" />
                <x-button type="submit" label="Save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
        </x-modal>

        {{-- Notice `onclick` is HTML --}}
        <x-button label="Add Expense" class="btn-primary btn-sm" @click="$wire.modalOpen()" />
    </div>

    <div class="flex justify-between">
        <x-choices label="Per page" wire:model.live="perPage" single :options='[
            ["id"=>10,"name"=>10],
            ["id"=>20,"name"=>20],
            ["id"=>100,"name"=>100],
        ]' option-value="name" />

        <div class="w-96">
            <x-choices label="Type" wire:model.live="type" :options="$this->expenseCategories" option-value="name" />
        </div>

        <div class="flex justify-end">

            <x-datetime label="From" wire:model.live="from" />
            <x-datetime label="to" wire:model.live="to" />
        </div>
    </div>
    <x-button label="Export" class="btn-primary btn-sm" wire:click="export" />
    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"type","label"=>"Type"],
        ["key"=>"desc","label"=>"Description"],
        ["key"=>"amount","label"=>"Amount"],
        ["key"=>"date","label"=>"Date"],
    ]' :rows="$this->expenses" with-pagination >

    @scope("cell_id",$category)
    {{$this->loop->index+1}}
    @endscope

    @scope('actions', $category)
    <div class="flex">

        <x-button icon="o-pencil-square" @click="$wire.modalEditOpen({{ $category->id }})" spinner class="btn-xs btn-primary text-white" />
        <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $category->id }})" spinner class="btn-xs btn-error text-white" />
    </div>
    @endscope
    </x-table>

    <table class="table table-zebra bold">
        <tr>
            <td colspan="2">Total</td>
            <td class="bold" colspan="3">{{$this->totalExpense}}</td>
        </tr>
    </table>
</x-card>
