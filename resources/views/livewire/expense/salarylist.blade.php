<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\ExpenseCategory;
use App\Models\Salary;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $category;
    public $perPage=20;
    public $from;
    public $to;
    public bool $modal = false;

    public function rules()
    {
        return [
            'category.desc' => 'required',
            'category.amount' => 'required',
            'category.date' => 'required',
        ];
    }

    public function mount() {
        $this->category = new Salary();

        $this->from = date("Y-m-d");
        $this->to = date("Y-m-d");

    }

     #[Computed]
    public function expenseCategories()
    {
        return  ExpenseCategory::all();
    }

     #[Computed]
     public function salaries()
    {
        return  Salary::whereBetween("date",[$this->from,$this->to])->paginate($this->perPage);
    }

    #[Computed]
    public function totalSalary()
    {
        return  Salary::whereBetween("date",[$this->from,$this->to])
        ->sum("amount");
    }

    public function modalClose(){
        $this->modal=false;
    }

    public function modalOpen(){

        $this->category = new Salary();

        $this->category->date=date("Y-m-d");
        $this->modal=true;
    }

    public function modalEditOpen($id){
        $this->category = Salary::find($id);
        $this->modal=true;
    }

    public function save(){

        $this->validate();

        $this->category->save();

        $this->success(title:"Added successfully");

        $this->modalClose();


    }

    public function delete($id){
        Salary::find($id)->delete();
        $this->success(title:"Deleted successfully");
    }



};

?>



<x-card title="Salary" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Salary" class="backdrop-blur">

            <x-form wire:submit.prevent="save">


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
        <x-button label="Add Salary" class="btn-primary btn-sm" @click="$wire.modalOpen()" />
    </div>

    <div class="flex justify-between">
        <x-choices label="Per page" wire:model.live="perPage" single :options='[
            ["id"=>10,"name"=>10],
            ["id"=>20,"name"=>20],
            ["id"=>100,"name"=>100],
        ]' option-value="name" />
        <div class="flex justify-end">

            <x-datetime label="From" wire:model.live="from" />
            <x-datetime label="to" wire:model.live="to" />
        </div>
    </div>
    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"desc","label"=>"Description"],
        ["key"=>"amount","label"=>"Amount"],
        ["key"=>"date","label"=>"Date"],
    ]' :rows="$this->salaries" with-pagination >

    @scope("cell_id",$salary)
    {{$this->loop->index+1}}
    @endscope

    @scope('actions', $salary)
    <div class="flex">

        <x-button icon="o-pencil-square" @click="$wire.modalEditOpen({{ $salary->id }})" spinner class="btn-sm btn-primary text-white" />
        <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $salary->id }})" spinner class="btn-sm btn-error text-white" />
    </div>
    @endscope
    </x-table>

    <table class="table table-zebra bold">
        <tr>
            <td colspan="2">Total</td>
            <td colspan="2">{{$this->totalSalary}}</td>
        </tr>
    </table>
</x-card>
