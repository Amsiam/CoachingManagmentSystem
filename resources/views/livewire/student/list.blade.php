<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Student;
use App\Models\Expense;
use App\Models\Package;
use App\Models\Shift;
use App\Models\Group;
use App\Models\Course;
use App\Models\Batch;
use App\Models\AcademicYear;

use App\Models\Payment;


use App\SMS\PaymentSMS;


use App\Exports\AdmissionStudentExport;


new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $category;
    public $perPage=20;

    public $search;

    public $filterPackage ;
    public $filterGroup ;
    public $filterCourse ;
    public $filterBatch ;
    public $filterDue;
    public $filterAcademicYear;
    public $filterShift;
    public $filterStatus;

    public $paymentMonth;
    public $paymentYear;

    public $student;


    public $type=[];
    public bool $modal = false;
    public $payment;

    public $total;

    public function rules()
    {
        return [
            "payment.paymentType"=>"required",
            "payment.payType"=>"required",
            "payment.paid"=>"required",
            "payment.discount"=>"required",
            "payment.month"=>"",
            "payment.remarks"=>"",
            "payment.student_roll"=>"required",
            "payment.recieved_by"=>"required",
        ];
    }

    public function mount() {
        $this->payment = new Payment();
    }

    #[Computed]
    public function packages()
    {
        return Package::get();
    }

    #[Computed]
    public function groups()
    {
        return  Group::all();
    }

    #[Computed]
    public function courses()
    {
        return  Course::when($this->filterPackage,function($q) {
            $q->where("package_id",$this->filterPackage);
        })->get();
    }

     #[Computed]
     public function batches()
    {
        return  Batch::when($this->filterGroup,function($q) {
            $q->where("group_id",$this->filterGroup);
        })->when($this->filterCourse,function($q) {
            $q->where("course_id",$this->filterCourse);
        })->get();
    }


     #[Computed]
     public function students()
    {
        return  Student::
        with(["package","batches","courses",
        "payments"=>fn($q)=>$q->where("paymentType","0")->orderBy("month","desc")->first()
        ])
        ->withSum("payments","due")
        ->when($this->search,function($q) {
            return $q->where("roll","like","%".$this->search."%")
            ->orWhere("name","like","%".$this->search."%")
            ->orWhere(function ($qq) {
                        return $qq->whereHas("personalDetails", function ($qqq) {
                            return
                                $qqq
                                ->where("smobile", "like", "%" . $this->search . "%")
                                ->orWhere("gmobile", "like", "%" . $this->search . "%");
                        });
        });
        })
        ->when($this->filterPackage,function($q) {
            return $q->where("package_id",$this->filterPackage);
        })
        ->when($this->filterGroup,function($q) {
            return $q->whereHas("personalDetails",function($qq) {
                return $qq->where("group",$this->filterGroup);
            });
        })
        ->when($this->filterShift,function($q) {
            return $q->whereHas("personalDetails",function($qq) {
                return $qq->where("shift",$this->filterShift);
            });
        })
        ->when($this->filterBatch,function($q) {
            return $q->whereHas("batches",function($qq) {
                return $qq->where("id",$this->filterBatch);
            });
        })
        ->when($this->filterCourse,function($q) {
            return $q->whereHas("courses",function($qq) {
                return $qq->where("id",$this->filterCourse);
            });
        })
        ->when($this->filterDue,function($q) {

            if($this->filterDue=="Yes"){
            return $q->whereHas("payments",function($qq) {
                return $qq->select(DB::raw('sum(due) as dueAmount'))->havingRaw("dueAmount>0");
            });
        }else{
            return $q->whereDoesntHave("payments",function($qq) {
                return $qq->select(DB::raw('sum(due) as dueAmount'))->havingRaw("dueAmount>0");
            });
        }
        })->when($this->filterAcademicYear,function($q) {
            return $q->where("year",$this->filterAcademicYear);
        })->when($this->filterStatus, function ($q) {
                $status = $this->filterStatus - 1;
                return $q->where("active", $status);
            })
        ->latest()
        ->paginate($this->perPage);

        // dd($stu);
    }
    public function exportAdmission(){
        return Excel::download(new AdmissionStudentExport(
            $this->filterPackage,
            $this->filterGroup ,
            $this->filterCourse ,
            $this->filterBatch ,
            $this->filterAcademicYear,
            $this->filterStatus,
            $this->filterShift
        ),date("Y-m-d H:s a")."-Student-export.xlsx");
    }

    public function modalClose(){
        $this->modal=false;
    }

    public function save(){


        $this->payment->month = $this->paymentYear."-".$this->paymentMonth."-02";

        $this->validate();


        try {
            DB::transaction(function () {

                if($this->payment->paymentType==0){
                    $this->payment->total = $this->payment->paid;
                }

                $this->payment->save();

            });
            $this->success("Payment Successfully taken.");
            $this->modal = false;

            $student = Student::with("personalDetails:student_id,id,gmobile")
            ->withSum("payments","due")
            ->find($this->payment->student_roll);
            $from = "Tusher's care";
            if($student->package_id==3){
                $from = "DMC Scholar";
            }


            PaymentSMS::sendMessage($student->personalDetails->gmobile,$this->payment,$student->payments_sum_due,$from);
        } catch (\Exception $err) {
            dd($err);

            $this->error("Payment Error occered.");
        }

    }

    public function updatedPaymentPaymentType($value){
        if($value==1){
            $due = Payment::where("student_roll",$this->payment->student_roll)->sum("due");

            if($due>0){
                $this->total = $due;
            }
            $this->payment->paid = $this->total;
        }else{
            $this->paymentMonth = date("m");
            $this->paymentYear = date("Y");

            if ($this->student->fixed_salary) {
                $this->payment->paid = (int) $this->student->monthly_salary;
            }
        }

    }

    public function modalOpen($id,$package){

        $this->payment = new Payment();
        $this->payment->student_roll = $id;
        $this->payment->payType = "Hand";

        $this->student = Student::find($id);

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

            if ($this->student->fixed_salary) {
                $this->payment->paid = (int) $this->student->monthly_salary;
            }

        }
        $this->payment->discount=0;

        $this->payment->recieved_by=auth()->user()->email;

        $this->modal=true;
    }



#[Computed]
public function academics_years(){
        return AcademicYear::where("active",true)->latest()->get();
    }

    #[Computed]
    public function shifts(){
            return Shift::when($this->filterCourse,function($q) {
            $q->where("course_id",$this->filterCourse);
        })->get();
        }
};

?>

@php
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


    $row_dec = [
        'bg-red-500' => fn(Student $student) => !$student->active
];

@endphp


<x-card title="Student List" separator progress-indicator>

    <x-modal wire:model="modal" class="backdrop-blur">

        <x-form wire:submit="save">
            <x-choices label="Payment Type" single wire:model.live="payment.paymentType" :options='
            [["id"=>0,"name"=>"Monthly"],
            ["id"=>1,"name"=>"Due Payment"]
            ]' />

            @if($payment->paymentType==1)
                <x-input label="Due" readonly wire:model="total" />
            @else
            <x-choices label="Payment Year" :options="$this->academics_years" single wire:model.live="paymentYear" option-value="year" option-label="year" />

            <x-choices label="Payment Month" :options="$month" single wire:model.live="paymentMonth" option-value="id"  />

            @endif
            @if(auth()->user()->can('reduce_payment'))
            <x-input label="Discount" wire:model="payment.discount" />
            @endif
            <x-input label="Amount" :readonly="$payment->paymentType==0 && $student?->fixed_salary==1 && !auth()->user()->can('reduce_payment')" wire:model="payment.paid" />
            <x-input label="Remarks" wire:model="payment.remarks" />


            <x-choices label="Pay Type" single wire:model="payment.payType" :options='
            [["id"=>"Hand","name"=>"Hand"],
            ["id"=>"Bkash","name"=>"Bkash"],
            ["id"=>"Nagad","name"=>"Nagad"],
            ]' />

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.modal = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>


    <div>
        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-choices label="Package" :options="$this->packages" single searchable wire:model.live="filterPackage"  />
            </div>
            <div class="lg:w-1/2">
                <x-choices label="Group" :options="$this->groups" single searchable wire:model.live="filterGroup"  />
            </div>
        </div>


        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-choices label="Course" :options="$this->courses" single searchable wire:model.live="filterCourse"  />
            </div>
            <div class="lg:w-1/2">
                <x-choices label="Batch" :options="$this->batches" single searchable wire:model.live="filterBatch"  />
            </div>
        </div>

        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-choices label="Due" :options="[['name'=>'Yes',],['name'=>'No']]" single wire:model.live="filterDue" option-value="name"  />
            </div>
            <div class="lg:w-1/2">
                <x-choices label="Academic Year" :options="$this->academics_years" single wire:model.live="filterAcademicYear" option-value="year" option-label="year"  />
            </div>
        </div>
        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-choices label="Status" :options="[['name'=>'Active','id'=>2],['name'=>'Deactive','id'=>1]]" single wire:model.live="filterStatus" option-value="id" option-label="name"  />
            </div>
            <div class="lg:w-1/2">
                <x-choices label="Shift" :options="$this->shifts" single wire:model.live="filterShift" option-value="id" option-label="name"  />
            </div>
        </div>
    </div>

    <x-button wire:click="exportAdmission" >Export</x-button>

    <div class="flex justify-between">
        <x-choices label="Per page" wire:model.live="perPage" single :options='[
            ["id"=>10,"name"=>10],
            ["id"=>20,"name"=>20],
            ["id"=>100,"name"=>100],
        ]' option-value="name" />

        <div class="w-96">


        </div>
        <div class="flex justify-end">

            <x-input label="Search" wire:model.live.debounce.500ms="search" />
        </div>
    </div>
    <x-table :headers='[
        ["key"=>"roll","label"=>"Roll"],
        ["key"=>"image","label"=>"Image"],
        ["key"=>"name","label"=>"Name"],
        ["key"=>"batch","label"=>"Batches"],
        ["key"=>"course","label"=>"Courses"],
        ["key"=>"package.name","label"=>"Package"],
        ["key"=>"due","label"=>"Due"],
    ]'
    :rows="$this->students"
    :row-decoration="$row_dec"
     with-pagination >


    @scope("cell_batch",$student)
    {{$student->batches->pluck("name")->implode(",")}}
    @endscope
    @scope("cell_course",$student)
    @php
        $names = collect([]);
        foreach($student->courses as $course){
            if ($student->courses->contains("parent_id",$course->id))
                continue;
            $names->push($course->name);
        }
    @endphp
    {{$names->join(",")}}
    @endscope

    @scope("cell_image",$student)
    @if($student->image)
   <img src="{{asset("storage/$student->image")}}" class="w-12 h-12 rounded-full"/>
   @endif
    @endscope


    @scope("cell_due",$student)

    @if($student->package_id==1 && count($student->payments)==1)

    <div>{{date("F",strtotime($student->payments[0]->month))}}</div>
    @endif
    <div class="text-error">
        @if ($student->payments_sum_due)

        {{$student->payments_sum_due}}
        @endif
    </div>
    @endscope

    @scope('actions', $student)
    <div class="flex">

        <x-button icon="o-eye" link="/student/{{$student->id}}" tooltip-bottom="View" class="btn-xs btn-primary text-white" />
            @can("pay")
                @if ($student->payments_sum_due>0 || $student->package_id==1)
                <x-button  icon="o-banknotes" tooltip-bottom="Pay" class="btn-xs btn-warning" @click="$wire.modalOpen({{$student->id}},{{$student->package_id}})" />
                @endif
            @endcan
      </div>
    @endscope
    </x-table>
</x-card>
