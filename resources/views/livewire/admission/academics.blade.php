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
use App\Models\{HscSub, Subject};
use App\Models\Payment;
use App\Models\AcademicYear;

use App\SMS\AdmissionSms;

use Illuminate\Support\Facades\DB;

new
#[Layout('layouts.app')]
#[Title('Admission')]
class extends Component {
    use Toast;

    protected $package_id = 1;

    public $academic_year;

    public $selectedQuota = 0;

    public $course_ids = [];

    public $other_batchs = [];

    public $selected_subjects = [];
    public $student;
    public $academics_ssc;
    public $academics_hsc;
    public $personal;
    public $hsc_sub;

    public $payment;

    public $page = 1;

    public function rules()
    {
        return [
            'student.year' => 'required',
            'student.name' => 'required',
            'student.batch_id' => 'required',
            'student.package_id' => 'required',
            'student.bn_name' => '',
            'student.monthly_salary' => '',
            'student.fixed_salary' => '',

            'personal.student_id' => '',
            'personal.fname' => '',
            'personal.mname' => '',
            'personal.smobile' => 'required',
            'personal.gmobile' => 'required',
            'personal.paddress' => '',
            'personal.dob' => '',
            'personal.blood' => '',
            'personal.group' => 'required',
            'personal.quota' => '',
            'personal.ref_name' => '',
            'personal.ref_mobile' => '',

            'academics_ssc.exam' => '',
            'academics_ssc.student_id' => '',
            'academics_ssc.board' => '',
            'academics_ssc.institue' => '',
            'academics_ssc.group' => '',
            'academics_ssc.roll' => '',
            'academics_ssc.passing_year' => '',
            'academics_ssc.gpa' => '',
            'academics_ssc.registration' => '',

            'academics_hsc.exam' => '',
            'academics_hsc.student_id' => '',
            'academics_hsc.board' => '',
            'academics_hsc.institue' => '',
            'academics_hsc.group' => '',
            'academics_hsc.roll' => '',
            'academics_hsc.passing_year' => '',
            'academics_hsc.gpa' => '',
            'academics_hsc.registration' => '',

            'hsc_sub.sub1' => '',
            'hsc_sub.sub2' => '',
            'hsc_sub.sub3' => '',
            'hsc_sub.sub4' => '',

            'payment.student_roll' => '',
            'payment.total' => '',
            'payment.paid' => 'required',
            'payment.payType' => 'required',
            'payment.paymentType' => '',
            'payment.discount' => 'required',
            'payment.due_date' => '',
            'payment.remarks' => '',
        ];
    }

    public function mount()
    {
        $this->academic_year = date('Y');

        $this->student = new Student();
        $this->student->year = date('Y');
        $this->student->package_id = $this->package_id;
        $this->personal = new PersonalDetail();
        $this->personal->quota = 0;
        $this->academics_ssc = new AcademicDetail();
        $this->academics_hsc = new AcademicDetail();

        $this->academics_ssc->exam = 'SSC';
        $this->academics_hsc->exam = 'HSC';
        $this->hsc_sub = new HscSub();

        $this->payment = new Payment();

        $this->payment->payType = 'Hand';
        $this->payment->paymentType = 2;
        $this->payment->paid = 0;
        $this->payment->discount = 0;
    }

    public function next()
    {
        $this->validate([
            'student.year' => 'required',
            'student.name' => 'required',
            'student.batch_id' => 'required',
            'student.package_id' => 'required',
            'student.bn_name' => '',
            'personal.smobile' => 'required',
            'personal.gmobile' => 'required',
            'course_ids' => 'required',
            'personal.group' => 'required'
        ]);
        array_push($this->other_batchs, $this->student->batch_id);
        $this->page++;
    }
    public function prev()
    {
        $this->page--;
    }

    #[Computed]
    public function selectedCourses()
    {
        return Course::with(['batches' => fn($q) => $q->whereIn('id', $this->other_batchs),"subCourses"])
            ->whereIn('id', $this->course_ids)
            ->get();
    }

    #[Computed]
    public function selectedSubCoursesParent()
    {
        return Course::whereIn('id', $this->course_ids)
            ->whereNotNull("parent_id")
            ->get();
    }

    #[Computed]
    public function courses()
    {
        return Course::where('package_id', $this->package_id)->whereNull("parent_id")->get();
    }


    #[Computed]
    public function academics_years()
    {
        return AcademicYear::where('active', true)->latest()->get();
    }

    #[Computed]
    public function batches()
    {
        return Batch::when($this->personal->group, function ($q) {
            return $q->where('group_id', $this->personal->group);
        })
            ->whereIn('course_id', $this->course_ids)
            ->get();
    }

    #[Computed]
    public function groups()
    {
        return Group::all();
    }

    #[Computed]
    public function subjects()
    {
        if (!$this->personal->group) {
            return [];
        }

        $subs = Subject::where('group_id', $this->personal->group)
        ->get();

        $this->selected_subjects = $subs->where("auto_selected","1")->pluck('id')->toArray();
        return $subs;
    }




    public function save()
    {
        if ($this->payment->discount == '') {
            $this->payment->discount = 0;
        }
        if ($this->payment->paid == '') {
            $this->payment->paid = 0;
        }

        $this->validate();

        $roll = Student::where('package_id', $this->student->package_id)
            ->where('batch_id', $this->student->batch_id)
            ->where('year', $this->student->year)
            ->max('roll');

        if (!$roll) {
            $batch = Batch::find($this->student->batch_id);

            if (!$batch) {
                $this->error('Batch Not found');

                return;
            }

            $roll = $this->student->year % 1000 . $batch->roll_current;
        } else {
            $roll = $roll + 1;
        }
        // dd($this->selected_subjects);

        try {
            DB::transaction(function () use ($roll) {
                $this->student->roll = $roll;
                $this->student->user_id = auth()->user()->id;
                $this->student->password = Hash::make('12345678');
                $this->student->save();

                array_push($this->other_batchs, $this->student->batch_id);
                $this->student->batches()->sync($this->other_batchs);

                $this->student->courses()->sync($this->course_ids);

                //save personal data
                $this->personal->student_id = $this->student->id;
                $this->personal->bn_name = $this->student->bn_name;
                $this->personal->save();

                //save academics data

                $this->academics_ssc->student_id = $this->student->id;
                $this->academics_hsc->student_id = $this->student->id;
                $this->academics_hsc->registration = $this->academics_ssc->registration;
                $this->academics_ssc->save();
                $this->academics_hsc->save();


                $this->student->subjects()->sync($this->selected_subjects);

                //payments

                $this->payment->student_roll = $this->student->id;

                $total = Course::whereIn('id', $this->course_ids)->sum('price');

                if (count($this->course_ids) == 2) {
                    $total = 30000;
                }

                $this->payment->total = $total;
                $this->payment->recieved_by = auth()->user()->email;

                $this->payment->save();
            });

            $this->success(title: 'Student added successfully');

            AdmissionSms::sendMessage($this->personal->smobile, $this->student->name, $this->student->id,$roll, '12345678', $this->payment);

            return $this->redirect('/student' . '/' . $this->student->id);
        } catch (\Exception $err) {
            DB::rollback();
            // dd($err->getMessage());
            $this->error(title: 'Error', description: $err->getMessage());
        }
    }
};
?>

@php
    $blood_groups = [
        ['name' => 'O+'],
        ['name' => 'B+'],
        ['name' => 'A+'],
        ['name' => 'AB+'],
        ['name' => 'A-'],
        ['name' => 'B-'],
        ['name' => 'O-'],
        ['name' => 'AB-'],
    ];
    $quotas = [
        ['name' => ' Medhabritty ', 'id' => 1],
        ['name' => ' Academic Olympiad', 'id' => 3],
         ['name' => ' Accounting Olympiad', 'id' => 4],
          ['name' => ' Medhagroz ', 'id' => 5],
        ['name' => ' English Olympiad', 'id' => 2],
    ];

    $payTypes = [['name' => 'Bkash'], ['name' => 'Nagad'], ['name' => 'Hand']];
@endphp

<div>

    <x-card title="Admission" separator progress-indicator>

        <x-form wire:confirm="Are you sure to save?" wire:submit="save">

            @if ($page == 1)
                <div>
                    <x-choices class="select-sm" label="Academic Year" wire:model="student.year" :options="$this->academics_years"
                        option-value="year" option-label="year" single />

                    <div class="grid grid-cols-4 gap-4 justify-around p-4">
                        @foreach ($this->courses as $course)
                            <x-checkbox class="checkbox-xs" label="{{ $course->name }}" value="{{ $course->id }}"
                                wire:model.live="course_ids" />
                        @endforeach
                    </div>
                    @if (count($course_ids) > 0)

         <br>

       <style>
        .down-arrow {
            display: inline-block;
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 15px solid red; /* Red down arrow */
            animation: bounce 1s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(10px);
            }
        }
    </style>

    &nbsp  &nbsp
    <font face="Hind Siliguri" color="ffeb00" size="5px" >         গ্রুপ /



    সাব কোর্স সিলেক্ট  করুন </b> </font>  &nbsp
        <span class="down-arrow"></span>



       </font>
    </p>




                    <div class="grid grid-cols-4 gap-4 justify-around p-4">

                        @foreach ($this->selectedCourses as $course)
                            @foreach ($course->subCourses as $subCourse)
                                <x-checkbox class="checkbox-xs" label="{{ $subCourse->name }}" value="{{ $subCourse->id }}"
                                    wire:model.live="course_ids" />
                            @endforeach
                        @endforeach
                    </div>
                    @endif

                    <x-radio class="w-full bg-red-50 ring-0" label="Group" :options="$this->groups"
                        wire:model.live="personal.group" />
<br>

                    @if (count($course_ids) > 0)

                        <x-choices label="Main Batch" wire:model="student.batch_id" :options="$this->batches" single />
                        @if (count($course_ids) > 1)
                            <x-choices label="Other's Batch" wire:model="other_batchs" :options="$this->batches" />
                        @endif

                    @endif
<br>

                    <x-input class="input-sm" label="Name (English)" wire:model="student.name" />


                    <x-input class="input-sm" label="Name (Bangla)" wire:model="student.bn_name" />


                    <x-input class="input-sm" label="Father's Name" wire:model="personal.fname" />

                    <x-input class="input-sm" label="Mother's Name" wire:model="personal.mname" />
<br>

                    <div class="lg:flex gap-2 ">
                        <div class="lg:w-1/2">
                            <x-input class="input-sm" label="Student's Mobile No" wire:model="personal.smobile" />
                        </div>
                        <div class="lg:w-1/2">
                            <x-input class="input-sm" label="Guardian's Mobile No" wire:model="personal.gmobile" />
                        </div>
                    </div>



                    <x-input class="input-sm" label="Mailing/Present Address" wire:model="personal.paddress" />


                    <div class="lg:flex gap-2">
                        <div class="lg:w-1/2">
                            <x-datetime label="Date Of Birth" wire:model="personal.dob" />
                        </div>
                        <div class="lg:w-1/2">
                            <x-choices label="Blood Group" wire:model="personal.blood" :options="$blood_groups"
                                option-value="name" single />
                        </div>
                    </div>

                  <br>
                   <style>
        .gradient-text-background {
            background: linear-gradient(to right, #3e7b27, #ff9d23); /* Gradient background */
            color: white;
            width:;
            padding: 10px;
            font-size: 20px;
            font-weight: bold;
        }
    </style>

    <p class="gradient-text-background"> <font face="Hind Siliguri">  একাডেমিক ইনফরমেশন  </font>  </p>

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
                                    <th>{{ $academics_ssc->exam }}</th>
                                    <td> <x-input class="input-sm" wire:model="academics_ssc.board" /></td>
                                    <td> <x-input class="input-sm" wire:model="academics_ssc.institue" /></td>
                                    <td> <x-input class="input-sm" wire:model="academics_ssc.group" /></td>
                                    <td> <x-input class="input-sm" wire:model="academics_ssc.roll" /></td>
                                    <td> <x-input class="input-sm" wire:model="academics_ssc.passing_year" /></td>
                                    <td> <x-input class="input-sm" wire:model="academics_ssc.gpa" /></td>
                                </tr>
                                <tr>
                                    <th>{{ $academics_hsc->exam }}</th>
                                    <td> <x-input class="input-sm" wire:model="academics_hsc.board" /></td>
                                    <td> <x-input class="input-sm" wire:model="academics_hsc.institue" /></td>
                                    <td> <x-input class="input-sm" wire:model="academics_hsc.group" /></td>
                                    <td> <x-input class="input-sm" wire:model="academics_hsc.roll" /></td>
                                    <td> <x-input class="input-sm" wire:model="academics_hsc.passing_year" /></td>
                                    <td> <x-input class="input-sm" wire:model="academics_hsc.gpa" /></td>
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

<br>
                    <div class="mt-2">

                                    <style>
        .gradient-text-background {
            background: linear-gradient(to right, #3e7b27, #123524); /* Gradient background */
            color: white;
            width:;
            padding: 10px;
            font-size: 20px;
            font-weight: bold;
        }
    </style>

    <p class="gradient-text-background"> <font face="Hind Siliguri">

    সাবজেক্ট সিলেক্ট করুন   </font>  </p>



                        <br>

                        <div class="lg:grid lg:grid-cols-3 lg:items-center lg:gap-2">
                            @foreach ($this->subjects as $subject)
                            <div>
                                <x-checkbox label="{{ $subject->name }}" wire:model="selected_subjects" value="{{ $subject->id }}" />
                            </div>
                            @endforeach
                        </div>
                    </div>
<br>
                    <div class="lg:flex gap-2 items-center">
                        <div class="lg:w-1/2">
                            <x-input type="number" min="0" class="input-sm" label="Monthly Salary" wire:model="student.monthly_salary" />

                            </div>
                            <br>
                        <div class="lg:w-1/2">
                            <x-checkbox label="Fixed Salary" wire:model="student.fixed_salary" value="1" />
                        </div>
                    </div>


<br>

                    <div class="w-full">
                        <x-radio class="w-full bg-red-50 ring-0" label=" Scholarship " :options="$quotas"
                            wire:model="personal.quota" />
                    </div>

<br>
                    <div class="lg:flex gap-2">
                        <div class="lg:w-1/2">
                            <x-input class="input-sm" label="Reference Name" wire:model="personal.ref_name" />
                        </div>
                        <br>
                        <div class="lg:w-1/2">
                            <x-input class="input-sm" label="Mobile" wire:model="personal.ref_mobile" />
                        </div>
                    </div>


                </div>

            @elseif ($page == 2)
                <div class="w-full overflow-x-scroll"></div>
                <table class="table table-zebra border">
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Batch Name</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                            $numCourses = count($this->course_ids);
                        @endphp
                        @foreach ($this->selectedCourses as $course)
                            @if ($this->selectedSubCoursesParent->contains('parent_id', $course->id))
                                    @php
                                        $numCourses--;
                                    @endphp
                                @continue
                            @endif
                            <tr>
                                <th>{{ $course->name }}</th>
                                <td>{{ $course->batches->pluck('name')->implode(',') }}</td>
                                <td>{{ $course->price }}</td>
                            </tr>
                            @php
                                $total += $course->price;
                            @endphp
                        @endforeach

                        @php
                            if ($numCourses == 2) {
                                $total = 30000;
                            }
                        @endphp

                        <tr>
                            <th class="text-right" colspan="2">মোট টাকা</th>
                            <td> <b>   {{ $total }} </b>  </td>
                        </tr>

                        <tr>
                            <th class="text-right" colspan="2">ছাড়</th>
                            <td>
                                <x-input class="input-sm" wire:model.live="payment.discount" />
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="2">পরিশোধ</th>
                            <td><x-input class="input-sm" wire:model.live="payment.paid" /></td>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="2">পরিশোধের ধরণ</th>
                            <td>
                                <x-choices-offline class="input-sm" wire:model="payment.payType" :options="$payTypes"
                                    option-value="name" single searchable />

                            </td>
                        </tr>

                        <tr>
                            <th class="text-right" colspan="2">বকেয়া</th>
                            <td>

                              <p style="color: red;">

                                {{ $total - ($payment->discount == '' ? 0 : $payment->discount) - ($payment->paid == '' ? 0 : $payment->paid) }} </p>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-right" colspan="2">বকেয়া পরিশোধের তারিখ</th>
                            <td><x-datetime class="input-sm" wire:model="payment.due_date" /></td>
                        </tr>

                        <tr>
                            <th class="text-right" colspan="2">Remarks</th>
                            <td><x-input class="input-sm" wire:model="payment.remarks" /></td>
                        </tr>


                    </tbody>
                </table>


            @endif
            <x-slot:actions>
                @if ($page == 1)
                    <x-button label="Next" @click="$wire.next()" class="btn-primary" type="button" />
                @elseif($page == 2)

                    <div class="flex justify-between w-full">

                        <x-button label="Prev" @click="$wire.prev()" class="btn-warning" type="button" />
                        <x-button label="Save" type="submit" class="btn-primary" />
                    </div>
                @endif
            </x-slot:actions>

        </x-form>
    </x-card>


</div>
