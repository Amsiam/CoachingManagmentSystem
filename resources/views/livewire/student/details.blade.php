<?php

use Illuminate\Support\Facades\Session;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\ExpenseCategory;
use App\Models\Student;


use App\Models\Payment;

use App\SMS\PaymentSMS;

use Livewire\WithFileUploads;


new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithFileUploads;


    #[Validate('required')]
    public $file;

    public $student;
    public $payment;

    public $total;

    public $isEdit=0;

    public bool $modal = false;
    public bool $paymodal = false;


    public $paymentMonth;
    public $paymentYear;

    public function rules(){
        return [
            "payment.paymentType"=>"required",
            "payment.payType"=>"required",
            "payment.paid"=>"required",
            "payment.discount"=>"required",
            "payment.month"=>"",
            "payment.student_roll"=>"required",
            "payment.recieved_by"=>"",
            "payment.edited_by"=>"",

    ];
    }

    public function mount($id) {
        $this->student = Student::with(["personalDetails","academicDetails","hscSubs",
        "courses"
        ,"payments","package"])->findOrFail($id);

        $this->payment = new Payment();

    }




    public function save()
    {
        $this->validate([
            "file" => "required"
        ]);
        $path = $this->file->store(path: 'public');

        $path = explode("public/",$path)[1];


        $this->student->image = $path;

        $this->student->save();

        $this->success("Image stored successful");

        $this->modal=false;

    }

    public function getListeners()
    {
        return [
            "refresh" => '$refresh',
        ];
    }

    public function statusChange() {
        $this->student->active = ! $this->student->active;
        $this->student->save();
    }



    public function delete($id) {

        try {
            Student::find($id)->delete();
            Payment::where("student_roll",$id)->delete();
        } catch (\Exception $err) {
            $this->error("Something error Happed");
        }

        $this->success("Deleted Successfully");

        $this->redirect("/student/list");
    }

    public function modalClose(){
        $this->paymodal=false;
    }

    public function paySave(){
        $this->payment->month = $this->paymentYear."-".$this->paymentMonth."-02";

        $this->validate([
            "payment.paymentType"=>"required",
            "payment.payType"=>"required",
            "payment.paid"=>"required",
            "payment.discount"=>"required",
            "payment.month"=>"required",
            "payment.total"=>"",
            "payment.student_roll"=>"required",
            "payment.recieved_by"=>"",

        ]);


        try {
            DB::transaction(function () {

                if($this->payment->paymentType==0){
                    $this->payment->total = $this->payment->paid;
                }
                $this->payment->save();


            });
            $this->success("Payment Successfully taken.");
            $this->paymodal = false;

            $student = Student::with("personalDetails:student_id,id,gmobile")
            ->withSum("payments","due")
            ->find($this->payment->student_roll);
            $from = "Tusher's care";
            if($student->package_id==3){
                $from = "DMC Scholar";
            }

            if(!$this->isEdit){
                PaymentSMS::sendMessage($student->personalDetails->gmobile,$this->payment,$student->payments_sum_due,$from);
            }
            $this->dispatch("refresh")->self();
        } catch (\Exception $err) {
            dd($err);

            $this->error("Payment Error occered.");
        }

    }

    public function modalOpen($id,$package){
        $this->isEdit=0;

        $this->payment = new Payment();
        $this->payment->student_roll = $id;
        $this->payment->payType = "Hand";

        if($package!=1){
            $this->payment->paymentType = 1;

           $due = Payment::where("student_roll",$id)->sum("due");


            if($due>0){
                $this->total = $due;
            }

        }else{
            $this->payment->paymentType = 0;

            $this->paymentMonth = date("m");
            $this->paymentYear = date("Y");

        }
        $this->payment->discount=0;

        $this->payment->recieved_by=auth()->user()->email;

        $this->paymodal=true;
    }

    public function openEditModal($id){

        $this->payment = Payment::findOrFail($id);
        $this->paymodal=true;
        $this->isEdit=1;

        $this->paymentMonth = date("m",strtotime($this->payment->month));
        $this->paymentYear = date("Y",strtotime($this->payment->month));

        $this->payment->edited_by=auth()->user()->email;

        // dd($this->payment);

    }

    public function deletePayment($id){

        $payment = Payment::findOrFail($id);

        $payment->delete();
        $this->dispatch("refresh")->self();
    }



};

?>


@php
        $academics_year =[["name"=>2024],["name"=>2025],["name"=>2026]];

$month = [
    ["id"=>1,"name"=>date("F",strtotime("01-01-2024"))],
    ["id"=>2,"name"=>date("F",strtotime("01-02-2024"))],
    ["id"=>3,"name"=>date("F",strtotime("01-03-2024"))],
    ["id"=>4,"name"=>date("F",strtotime("01-04-2024"))],
    ["id"=>5,"name"=>date("F",strtotime("01-05-2024"))],
    ["id"=>6,"name"=>date("F",strtotime("01-06-2024"))],
    ["id"=>7,"name"=>date("F",strtotime("01-07-2024"))],
    ["id"=>8,"name"=>date("F",strtotime("01-08-2024"))],
    ["id"=>9,"name"=>date("F",strtotime("01-09-2024"))],
    ["id"=>10,"name"=>date("F",strtotime("01-10-2024"))],
    ["id"=>11,"name"=>date("F",strtotime("01-11-2024"))],
    ["id"=>12,"name"=>date("F",strtotime("01-12-2024"))],
];

@endphp


<x-card title="Student Details" separator progress-indicator>

    <x-modal wire:model="modal" title="Photo Upload" separator>

        <x-file wire:model.live="file"  label="Photo" hint="Only Image" accept="image/*" capture="user" />
        @if ($file)
            <img src="{{ $file->temporaryUrl() }}">
        @endif
        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal = false" />
            <x-button label="Confirm" wire:click="save" class="btn-primary" />
        </x-slot:actions>
    </x-modal>

    <x-modal wire:model="paymodal" class="backdrop-blur">

        <x-form wire:submit="paySave">
            <x-choices label="Payment Type" single wire:model.live="payment.paymentType" :options='
            [["id"=>0,"name"=>"Monthly"],
            ["id"=>1,"name"=>"Due Payment"]
            ]' />

            @if($payment->paymentType==1)
                <x-input label="Due" readonly wire:model="total" />
            @else
                <x-choices label="Payment Year" :options="$academics_year" single wire:model.live="paymentYear" option-value="name"  />
                <x-choices label="Payment Month" :options="$month" single wire:model.live="paymentMonth" option-value="id"  />

            @endif
            <x-input label="Discount" wire:model="payment.discount" />
            <x-input label="Amount" wire:model="payment.paid" />


            <x-choices label="Pay Type" single wire:model="payment.payType" :options='
            [["id"=>"Hand","name"=>"Hand"],
            ["id"=>"Bkash","name"=>"Bkash"],
            ["id"=>"Nagad","name"=>"Nagad"],
            ]' />

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.paymodal = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="paySave" />
            </x-slot:actions>
        </x-form>
    </x-modal>



    <x-slot:menu>
        @can("pay")
            <x-button icon="o-banknotes" @click="$wire.modalOpen({{$this->student->id}},{{$this->student->package_id}})" class="btn-accent btn-xs" >Pay</x-button>
        @endcan
        <x-button icon="o-photo" @click="$wire.modal=true" class="btn-success btn-xs" >Upload</x-button>
        <x-button external link="/print/idcard/{{$this->student->id}}" icon="o-printer" class="btn-warning btn-xs" >ID Card</x-button>
        @can("student.edit")
            <x-button link="/student/edit/{{$student->id}}" icon="o-pencil" class="btn-primary btn-xs" >Update</x-button>
        @endcan
        @can("student.delete")
            <x-button wire:confirm="are you sure?" wire:click="delete({{$student->id}})" icon="o-trash" class="btn-error btn-xs" >Delete</x-button>
        @endcan
         </x-slot:menu>

            <div class="flex columns-2">
                <div class="w-1/2">
                    <div class="bg-white shadow rounded-lg p-6 dark:bg-base-100">
                        <div class="flex flex-col items-center">
                            @if ($this->student->image)

                            <img src="{{asset('storage/'.$this->student->image)}}"
                             class="w-32 h-32 bg-gray-300 rounded-full mb-4 shrink-0"/>

                            @endif
                            <h1 class="text-xl font-bold">{{$student->name}}</h1>
                            <p class="">
                                Courses: {{$student->courses->pluck("name")->implode(",")}}
                            </p>
                            <p class="">
                               Batches: {{$student->batches->pluck("name")->implode(",")}}
                            </p>

                        </div>
                        <hr class="my-6 border-t border-gray-300">
                        <div class="flex flex-col">
                            <span class="text-gray-700 uppercase font-bold tracking-wider mb-2">Profile</span>
                            <table class="table table-zebra">
                                <tr>
                                    <th>Roll</th>
                                    <td>{{$student->roll}}</th>
                                </tr>
                                <tr>
                                    <th>Bn_Name</th>
                                    <td>{{$student->bn_name}}</th>
                                </tr>
                                <tr>
                                    <th>Father name</th>
                                    <td>{{$student->personalDetails->fname}}</th>
                                </tr>

                                <tr>
                                    <th>Mother name</th>
                                    <td>{{$student->personalDetails->mname}}</th>
                                </tr>

                                <tr>
                                    <th>Date of Birth</th>
                                    <td>{{$student->personalDetails->dob}}</th>
                                </tr>
                                <tr>
                                    <th>Blood Group</th>
                                    <td>{{$student->personalDetails->blood}}</th>
                                </tr>
                                @if ($student->personalDetails->group)

                                <tr>
                                    <th>Group</th>
                                    <td>{{$student->personalDetails->group}}</th>
                                </tr>
                                @endif
                                <tr>
                                    <th>Address</th>
                                    <td>{{$student->personalDetails->paddress}}</th>
                                </tr>


                                <tr>
                                    <th>Student no</th>
                                    <td>{{$student->personalDetails->smobile}}</th>
                                </tr>

                                <tr>
                                    <th>Gurdian no</th>
                                    <td>{{$student->personalDetails->gmobile}}</th>
                                </tr>

                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <x-button class="btn-sm" wire:confirm="Are you sure to change status?" wire:click="statusChange">
                                        @if ($student->active)
                                            <div class="bg-green-500 px-3 py-2 rounded-lg">Active</div>
                                        @else

                                        <div class="bg-red-500 px-3 py-2 rounded-lg">Deactive</div>
                                        @endif
                                        </x-button>
                                    </th>
                                </tr>

                                <tr>
                                    <th>HSC SUBS</th>
                                    <td>
                                    {{$student->hscSubs->sub1}},
                                    {{$student->hscSubs->sub2}},
                                    {{$student->hscSubs->sub3}},
                                    {{$student->hscSubs->sub4}},
                                    </th>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="w-1/2">
                    <x-card title="Academic Details">
                        <div class="overflow-x-scroll">
                            <table class="table table-zebra border">
                                <thead>
                                    <tr>
                                        <th>Exam</th>
                                        <th>Board</th>
                                        <th>Institute name</th>
                                        <th>Group</th>
                                        <th>Roll No</th>
                                        <th>Passing Year</th>
                                        <th>GPA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($student->academicDetails as $ad)
                                    <tr>
                                        <th>{{$ad->exam}}</th>
                                        <td>{{$ad->board}}</td>
                                        <td>{{$ad->institue}}</td>
                                        <td>{{$ad->group}}</td>
                                        <td>{{$ad->roll}}</td>
                                        <td>{{$ad->passing_year}}</td>
                                        <td>{{$ad->gpa}}</td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <th>Registration No:</th>
                                        <td colspan="6">
                                            {{$ad->registration}}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </x-card>

                    <x-card title="Payments">
                        <div class="overflow-x-scroll">
                            <table class="table table-zebra border">
                                <thead>
                                    <tr>
                                        <th>Tranaction Id</th>
                                        <th>Payment Type</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Discount</th>
                                        <th>Pay Type</th>
                                        <th>Pay receive</th>
                                        <th>Pay Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($student->payments as $payment)
                                        <tr>
                                            <td>{{$payment->id}}</td>
                                            <td>
                                                @if ($payment->paymentType==0)
                                                    Montly({{date("F",strtotime($payment->month))}})
                                                @elseif ($payment->paymentType==1)
                                                    Due Payment
                                                @elseif ($payment->paymentType==2)
                                                    Admission
                                                @endif
                                            </td>
                                            <td>{{$payment->total}}</td>
                                            <td>{{$payment->paid}}</td>
                                            <td>{{$payment->discount}}</td>
                                            <td>{{$payment->payType}}</td>
                                            <td>{{$payment->recieved_by}}</td>
                                            <td>{{$payment->created_at}}</td>
                                            <td>
                                                <x-button external icon="o-printer" class="btn-primary btn-xs" link="/print/invoice/{{$payment->id}}"/>
                                                @can("payment.edit")
                                                    <x-button icon="o-pencil" class="btn-success btn-xs" @click="$wire.openEditModal({{$payment->id}})" />
                                                @endcan

                                                @can("payment.delete")
                                                    <x-button wire:confirm="Are you sure to delete payment?" wire:click="deletePayment({{$payment->id}})" icon="o-trash" class="btn-error btn-xs" />
                                                @endcan
                                               </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">
                                                Not paid Yet
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </x-card>
                </div>
            </div>

</x-card>
