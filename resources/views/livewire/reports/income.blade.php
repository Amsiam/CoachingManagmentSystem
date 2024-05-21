<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\User;

use App\Models\Payment;




use App\Exports\IncomeExport;


new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $perPage=20;

    public $from;
    public $to;

    public $filterRecievedBy=[] ;
    public $filterPaymentType ;
    public $filterPayType ;

    public function mount(){
        $this->from = date("Y-m-d");
        $this->to = date("Y-m-d");

        if(!auth()->user()->can("report.excel")){
            $this->filterRecievedBy = [auth()->user()->email];
        }
    }

    #[Computed]
    public function users()
    {
        return  User::all();

    }



     #[Computed]
     public function payments()
    {
        return  Payment::with(["student"])
        ->when($this->from,function($q){
            return $q->whereDate("created_at",">=",$this->from);
        })
        ->when($this->to,function($q){
            return $q->whereDate("created_at","<=",$this->to);
        })
        ->when($this->filterRecievedBy!=[],function($q){
            return $q->whereIn("recieved_by",$this->filterRecievedBy);
        })
        ->when($this->filterPayType,function($q){
            return $q->where("payType",$this->filterPayType);
        })
        ->when($this->filterPaymentType,function($q){
            return $q->where("paymentType",$this->filterPaymentType);
        })
        ->latest()
        ->paginate($this->perPage);

    }

     #[Computed]
     public function paymentsTotal()
    {
        return  Payment::with(["student"])
        ->when($this->from,function($q){
            return $q->whereDate("created_at",">=",$this->from);
        })
        ->when($this->to,function($q){
            return $q->whereDate("created_at","<=",$this->to);
        })
        ->when($this->filterRecievedBy,function($q){
            return $q->where("recieved_by",$this->filterRecievedBy);
        })
        ->when($this->filterPayType,function($q){
            return $q->where("payType",$this->filterPayType);
        })
        ->when($this->filterPaymentType,function($q){
            return $q->where("paymentType",$this->filterPaymentType);
        })->sum("paid");

    }


    public function export(){

        return Excel::download(new IncomeExport(
            $this->from,
            $this->to ,
            $this->filterRecievedBy ,
            $this->filterPayType ,
            $this->filterPaymentType
        ),date("Y-m-d H:s a")."-Income-Export.xlsx");
    }

    public function exportPdf(){

        return Excel::download(new IncomeExport(
            $this->from,
            $this->to ,
            $this->filterRecievedBy ,
            $this->filterPayType ,
            $this->filterPaymentType
        ),date("Y-m-d H:s a")."-Income-Export.pdf",\Maatwebsite\Excel\Excel::MPDF);
    }

};

?>



<x-card title="Income Report" separator progress-indicator>



    <div>
        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-choices label="Payment Type" :options='
                [["id"=>0,"name"=>"Monthly"],
                ["id"=>2,"name"=>"Admission"],
                ["id"=>1,"name"=>"Due Payment"]]' single searchable wire:model.live="filterPaymentType"  />
            </div>
            <div class="lg:w-1/2">
                <x-choices label="Pay Type" :options='
                [["id"=>"Hand","name"=>"Hand"],
                ["id"=>"Bkash","name"=>"Bkash"],
                ["id"=>"Nagad","name"=>"Nagad"],
                ]' single searchable wire:model.live="filterPayType"  />
            </div>
        </div>


        @can("report.excel")

        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-choices label="Recieved By" :options="$this->users" option-value="email" searchable wire:model.live="filterRecievedBy"  />
            </div>
        </div>
        @endcan
    </div>
    @can("report.excel")
    <x-button class="btn-primary btn-sm" wire:click="export" >Export</x-button>
   @endcan
    <x-button class="btn-accent btn-sm" wire:click="exportPdf" >PDF</x-button>



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
        ["key"=>"student.roll","label"=>"Roll"],
        ["key"=>"student.name","label"=>"Name"],
        ["key"=>"paid","label"=>"Amount"],
        ["key"=>"payType","label"=>"Pay Type"],
        ["key"=>"paymentType","label"=>"Payment Type"],
        ["key"=>"created_at","label"=>"Created Date"],
        ["key"=>"recieved_by","label"=>"Recieved By"],
    ]' :rows="$this->payments" with-pagination >

    @scope("cell_paymentType",$payment)

        @if ($payment->paymentType==0)
        Montly({{date("F",strtotime($payment->month))}})
        @elseif($payment->paymentType==1)
        Due Payment
        @elseif($payment->paymentType==2)
        Admission

        @endif

    @endscope


    @scope('actions', $payment)

    @endscope
    </x-table>

    <table class="table table-zebra">
    <tr>
        <td colspan="2">Total</td>
        <td colspan="5">{{$this->paymentsTotal}}</td>
    </tr>
    </table>
</x-card>
