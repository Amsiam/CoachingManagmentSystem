<?php

use App\Models\AcademicDetail;
use App\Models\Course;
use App\Models\HscSub;
use App\Models\Payment;
use App\Models\PersonalDetail;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class StudentRepository{

    protected Student $student;
    protected PersonalDetail $personal;
    protected AcademicDetail $academics_ssc;
    protected AcademicDetail $academics_hsc;
    protected HscSub $hsc_sub;
    protected Payment $payment;
    protected $other_batchs;
    protected $course_ids;
    protected $roll;


    public function __construct($student,
    $personal,$academics_ssc
    ,$academics_hsc,$hsc_sub,
    $payment,$other_batchs,$course_ids,$roll)
    {
        $this->student = $student;
        $this->personal = $personal;
        $this->academics_ssc = $academics_ssc;
        $this->academics_hsc = $academics_hsc;
        $this->hsc_sub = $hsc_sub;
        $this->payment = $payment;
        $this->other_batchs = $other_batchs;
        $this->course_ids = $course_ids;
        $this->roll = $roll;
    }

    public function addStudent(){
        $this->student->roll = $this->roll;
        $this->student->user_id = auth()->user()->id;
        $this->student->password = Hash::make("12345678");
        $this->student->save();

        array_push($this->other_batchs, $this->student->batch_id);
        $this->student->batches()->sync($this->other_batchs);


        $this->student->courses()->sync($this->course_ids);

        //save personal data
        $this->personal->student_id=$this->student->id;
        $this->personal->bn_name=$this->student->bn_name;
        $this->personal->save();

        //save academics data

        $this->academics_ssc->student_id = $this->student->id;
        $this->academics_hsc->student_id = $this->student->id;
        $this->academics_hsc->registration = $this->academics_ssc->registration;
        $this->academics_ssc->save();
        $this->academics_hsc->save();

        $this->hsc_sub->student_id = $this->student->id;
        $this->hsc_sub->save();


        //payments

        $this->payment->student_roll = $this->roll;

        $total = Course::whereIn("id",$this->course_ids)->sum("price");

        $this->payment->total = $total;
        $this->payment->recieved_by = auth()->user()->name;


        $this->payment->save();
    }
}
