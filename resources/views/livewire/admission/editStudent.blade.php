<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title, Computed, Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;

use App\Models\Group;
use App\Models\Course;
use App\Models\Classs;
use App\Models\Batch;
use App\Models\Student;
use App\Models\AcademicDetail;
use App\Models\PersonalDetail;
use App\Models\HscSub;
use App\Models\Payment;


use App\SMS\AdmissionSms;


use Illuminate\Support\Facades\DB;

new
#[Layout('layouts.app')]
#[Title("Admission")]
class extends Component {
    use Toast;

    protected $package_id=1;

    public $academic_year;

    public $selectedQuota=0;


    public $course_ids;

    public $other_batchs;

    public $student;
    public $academics_ssc;
    public $academics_hsc;
    public $personal;
    public $hsc_sub;


    public $payment;

    public $page=1;



    public function rules()
    {
        return [
            'student.year' => 'required',
            'student.name' => 'required',
            'student.package_id' => 'required',
            "student.bn_name"=>"required",


            "student.batch_id"=>"required",

            "course_ids" =>"",
            "other_batchs" =>"",

            "personal.student_id"=>"",
            "personal.fname"=>"",
            "personal.mname"=>"",
            "personal.smobile"=>"required",
            "personal.gmobile"=>"required",
            "personal.paddress"=>"",
            "personal.dob"=>"",
            "personal.blood"=>"",
            "personal.group"=>"",
            "personal.quota"=>"",
            "personal.ref_name"=>"",
            "personal.ref_mobile"=>"",

            "academics_ssc.exam"=>"",
            "academics_ssc.student_id"=>"",
            "academics_ssc.board"=>"",
            "academics_ssc.institue"=>"",
            "academics_ssc.group"=>"",
            "academics_ssc.roll"=>"",
            "academics_ssc.passing_year"=>"",
            "academics_ssc.gpa"=>"",
            "academics_ssc.registration"=>"",


            "academics_hsc.exam"=>"",
            "academics_hsc.student_id"=>"",
            "academics_hsc.board"=>"",
            "academics_hsc.institue"=>"",
            "academics_hsc.group"=>"",
            "academics_hsc.roll"=>"",
            "academics_hsc.passing_year"=>"",
            "academics_hsc.gpa"=>"",
            "academics_hsc.registration"=>"",

            "hsc_sub.sub1" => "",
            "hsc_sub.sub2" => "",
            "hsc_sub.sub3" => "",
            "hsc_sub.sub4" => "",
        ];
    }






    public function mount($id) {
        $this->student = Student::with(["courses","batches"])->findOrFail($id);



        $this->course_ids = $this->student->courses->pluck("id");

        // dd($this->course_ids);

        $this->other_batchs = $this->student->batches->pluck("id")->toArray();

        $this->personal = PersonalDetail::where("student_id",$id)->first();

        $this->academics_ssc = AcademicDetail::where("student_id",$id)->where("exam","SSC")->first();
        $this->academics_hsc = AcademicDetail::where("student_id",$id)->where("exam","HSC")->first();

        $this->hsc_sub = HscSub::where("student_id",$id)->first();
    }



    #[Computed]
    public function courses(){
        return Course::where("package_id",$this->student->package_id)->get();
    }

    #[Computed]
    public function batches(){
        return Batch::when($this->personal->group,function($q){
            return $q->where("group_id",$this->personal->group);
        })->whereIn("course_id",$this->course_ids)->get();
    }

    #[Computed]
    public function groups(){
        return Group::all();
    }



    public function save() {

        $this->validate();

        $currentStudent = Student::find($this->student->id);

        $roll = $currentStudent->roll;
        $Cbatch = $currentStudent->batch_id;
        if($currentStudent->batch_id!=$this->student->batch_id){
            $roll = Student::where("package_id",$this->student->package_id)
            ->where("batch_id",$this->student->batch_id)
            ->where("year",$this->student->year)
            ->max("roll");

            if(!$roll){
                $batch = Batch::find($this->student->batch_id);

                if(!$batch){
                    $this->error("Batch Not found");

                    return;
                }

                $roll = ($this->student->year%1000).$batch->roll_current;

            }else{

            $roll = $roll + 1;
            }
        }


        try {

            DB::transaction(function () use($roll,$Cbatch){

                $this->student->roll = $roll;

                $this->student->save();
                $this->student->batches()->detach($Cbatch);

                $this->other_batchs = array_diff($this->other_batchs, [$Cbatch]);

                array_push($this->other_batchs, $this->student->batch_id);
                $this->student->batches()->sync($this->other_batchs);


                $this->student->courses()->sync($this->course_ids);

                //save personal data
                $this->personal->save();

                //save academics data
                $this->academics_ssc->save();
                $this->academics_hsc->save();

                $this->hsc_sub->save();

            });

            $this->success(title:"Student Updated successfully");

            return $this->redirect("/student"."/".$this->student->id);

        } catch (\Exception $err) {
            DB::rollback();
            dd($err->getMessage());
            $this->error(title:"Error",description:$err->getMessage());
        }


    }



};
?>

@php

$academics_year =[["name"=>2024],["name"=>2025],["name"=>2026]];


    $blood_groups = [
        ["name"=>"O+"],
        ["name"=>"B+"],
        ["name"=>"A+"],
        ["name"=>"AB+"],
        ["name"=>"A-"],
        ["name"=>"B-"],
        ["name"=>"O-"],
        ["name"=>"AB-"]];
$quotas=[
    ["name"=>"Freedom Fighter","id"=>1],
    ["name"=>"Tribal","id"=>2],
    ["name"=>"No Quota","id"=>0]
];

$payTypes=[
    ["name"=>"Bkash"],
    ["name"=>"Nagad"],
    ["name"=>"Hand"],
];
@endphp

    <div>

        <x-card title="Student Edit" separator progress-indicator>

        <x-form wire:submit="save">

            @if($page==1)
            <div>
        <x-choices class="input-sm" label="Academic Year" wire:model="student.year" :options="$academics_year" option-value="name" single />

            <div class="grid grid-cols-4 gap-4 justify-around p-4">
                @foreach ($this->courses as $course)
                    <x-checkbox class="checkbox-xs" label="{{$course->name}}" value="{{$course->id}}" wire:model.live="course_ids" />
                @endforeach
            </div>

            @if ($this->student->package_id==1)

                <x-radio class="w-full bg-red-50 ring-0" label="Group" :options="$this->groups" wire:model.live="personal.group" />

            @endif

            <x-choices label="Main Batch" wire:model="student.batch_id" :options="$this->batches" single />


        @if (count($other_batchs)>1)
                <x-choices label="Other's Batch" wire:model="other_batchs" :options="$this->batches" />
        @endif



        <x-input class="input-sm" label="Name(English)" wire:model="student.name"  />

        <x-input class="input-sm" label="Name(Bangla)" wire:model="student.bn_name" />

        <x-input class="input-sm" label="Father's Name" wire:model="personal.fname" />

        <x-input class="input-sm" label="Mother's Name" wire:model="personal.mname" />


        <div class="lg:flex gap-2 ">
            <div class="lg:w-1/2">
                <x-input  class="input-sm" label="Student's Mobile No" wire:model="personal.smobile" />
            </div>
            <div class="lg:w-1/2">
                <x-input  class="input-sm" label="Guardian's Mobile No" wire:model="personal.gmobile" />
            </div>
        </div>


        <x-input  class="input-sm" label="Mailing/Present Address" wire:model="personal.paddress" />


        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-datetime label="Date Of Birth" wire:model="personal.dob" />
            </div>
            <div class="lg:w-1/2">
                <x-choices label="Blood Group" wire:model="personal.blood" :options="$blood_groups" option-value="name" single />
            </div>
        </div>

        <div class="w-full mt-2 overflow-x-scroll">
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
                        <tr>
                            <th>{{$academics_ssc->exam}}</th>
                            <td> <x-input  class="input-sm" wire:model="academics_ssc.board" /></td>
                            <td> <x-input class="input-sm" wire:model="academics_ssc.institue" /></td>
                            <td> <x-input class="input-sm" wire:model="academics_ssc.group"/></td>
                            <td> <x-input class="input-sm" wire:model="academics_ssc.roll"/></td>
                            <td> <x-input class="input-sm" wire:model="academics_ssc.passing_year"/></td>
                            <td> <x-input class="input-sm" wire:model="academics_ssc.gpa"/></td>
                        </tr>
                        <tr>
                            <th>{{$academics_hsc->exam}}</th>
                            <td> <x-input  class="input-sm" wire:model="academics_hsc.board" /></td>
                            <td> <x-input class="input-sm" wire:model="academics_hsc.institue" /></td>
                            <td> <x-input class="input-sm" wire:model="academics_hsc.group"/></td>
                            <td> <x-input class="input-sm" wire:model="academics_hsc.roll"/></td>
                            <td> <x-input class="input-sm" wire:model="academics_hsc.passing_year"/></td>
                            <td> <x-input class="input-sm" wire:model="academics_hsc.gpa"/></td>
                        </tr>

                    <tr>
                        <th>Registration No:</th>
                        <td colspan="6">
                            <x-input class="input-sm" wire:model="academics_ssc.registration" />
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>


        <div class="mt-2">
            <h1 class="font-bold text-sm">HSC Subject</h1>
        <div class="lg:flex lg:justify-between lg:items-center lg:gap-2">
            <x-input  class="input-sm" wire:model="hsc_sub.sub1" />
            <x-input  class="input-sm" wire:model="hsc_sub.sub2" />
            <x-input  class="input-sm" wire:model="hsc_sub.sub3" />
            <x-input  class="input-sm" wire:model="hsc_sub.sub4" />
        </div>
        </div>



        <div class="w-full">
            <x-radio class="w-full bg-red-50 ring-0" label="Quota" :options="$quotas"  wire:model="personal.quota" />
        </div>

        <div class="lg:flex gap-2">
            <div class="lg:w-1/2">
                <x-input  class="input-sm" label="Reference Name" wire:model="personal.ref_name" />
            </div>
            <div class="lg:w-1/2">
                <x-input  class="input-sm" label="Mobile" wire:model="personal.ref_mobile" />
            </div>
        </div>
    </div>

        @endif
        <x-slot:actions>
            <div class="flex justify-end w-full">

                <x-button label="Save"  class="btn-primary" type="submit"  />
            </div>
        </x-slot:actions>

        </x-form>
    </x-card>


</div>
