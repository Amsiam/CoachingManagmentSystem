<?php

use App\Models\BookSell;
use App\Models\Exam;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Payment;
use App\Models\Result;
use App\Models\ResultMark;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Volt;
use Picqer\Barcode\BarcodeGeneratorPNG;


Route::get('/link', function(){
    
    Artisan::call('storage:link');
    
});



    Route::prefix("/")->middleware("auth")->group(function(){
        Volt::route('/dashboard', 'dashboard')
        ->name('dashboard');


        Route::prefix("/expense")->group(function() {
            Volt::route("/list","expense.list")->middleware("can:expense.list");
            Volt::route("/categories","expense.categories")->middleware("can:expense.category.create");;
            Volt::route("/salary","expense.salarylist")->middleware("can:expense.salary.list");
        });

        Route::prefix("/book")->group(function() {
            Volt::route("/list","book.list")->middleware("can:book.list");
            Volt::route("/sell","book.sell")->middleware("can:book.sell");
        });

        Route::prefix("/admission")->group(function() {
            Volt::route("/academics","admission.academics")->middleware("can:student.academics");
            Volt::route("/admission","admission.admission")->middleware("can:student.admission");
            Volt::route("/dmc","admission.dmc")->middleware("can:student.dmc");

            Volt::route("/request","admission.admissionRequest")->middleware("can:student.admissionRequest");

        });


        Volt::route("/student/list","student.list")->middleware("can:student.list");

        Volt::route("/student/{id}","student.details")->middleware("can:student.list");

        Route::get("/student/image/{id}",function(Request $request, $id) {

            $student = Student::findOrFail($id);
            return view("student.image",compact("student"));
        })->middleware("can:student.list")->name("student.image");

        Route::post("/student/image/{id}",function(Request $request, $id) {

            $student = Student::findOrFail($id);

            $img = $request->image;
            $folderPath = "public/";

            $image_parts = explode(";base64,", $img);



            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid().$student->roll . '.png';

            $file = $folderPath . $fileName;
            Storage::put($file, $image_base64);

            $student->image = $fileName;

            $student->save();


            return redirect("student/".$student->id);

        })->middleware("can:student.list")->name("student.image");



        Volt::route("/student/edit/{id}","admission.editStudent")->middleware("can:student.edit");

        Volt::route("/auto-sms", "autosms")->name("auto.sms");

        Volt::route("/groups","academics.groupTable")->middleware("can:academics.group");
        Volt::route("/classes","academics.classs")->middleware("can:academics.class");
        Volt::route("/courses","academics.courses")->middleware("can:academics.course");
        Volt::route("/batches","academics.batches")->middleware("can:academics.batch");
        Volt::route("/academic_year","academics.academic_year")->middleware("can:academics.academic_year");



        Volt::route("/exam","exam.list")->middleware("can:exam.list");
        Volt::route("/result","exam.result")->middleware("can:exam.result");
        Volt::route("/result/mark/{id}","exam.mark")->middleware("can:exam.result")->name("exam.result.mark");
        Volt::route("/result/subject/mark/{id}", "exam.subjectMark")->middleware("can:exam.result")->name("exam.result.subject.mark");



        Route::prefix("/report")->group(function(){
            Volt::route("/income","reports.income")->middleware("can:report.income");
            Volt::route("/admission","reports.admission")->middleware("can:report.admission");
            Volt::route("/montly","reports.montly")->middleware("can:report.monthly");
            Volt::route("/activity_log", "activity_log");
        });


        Route::prefix("/user")->group(function(){
            Volt::route("/permission","user.permission")->middleware("can:permission.list");
            Volt::route("/roles","user.role")->middleware("can:role.list");
            Volt::route("/admin","user.admin")->middleware("can:admin.list");
        });

    Volt::route("/sendsms", "sendsms")->middleware("can:sendsms");


        Volt::route("/slider","slider")->middleware("can:slider");
    Volt::route("/student_review", "student_review")->middleware("can:student_review");


        Route::get('/print/idcard/{id}', function ($id) {

            $student = Student::with(["personalDetails", "batches"])->findOrFail($id);

            $generator = new BarcodeGeneratorPNG();
            $barCode = base64_encode($generator->getBarcode($student->roll, $generator::TYPE_CODE_128));

            return view("pdf.idcard", compact("student", "barCode"));
        })->name("pdf.id");



        Route::get('/print/invoice/{id}', function ($id) {

            $payment = Payment::with(["student", "student.batches", "student.courses", "student.personalDetails"])->findOrFail($id);
        $prevDue = 0;

        if ($payment->paymentType == 1) {
            $prevDue = Payment::where("id", "<", $payment->id)
                ->where("student_roll", $payment->student_roll)
                ->sum("total");

            $prevDue -= Payment::where("id", "<", $payment->id)
            ->where("student_roll", $payment->student_roll)
            ->sum("paid");
            $prevDue -= Payment::where("id", "<", $payment->id)
            ->where("student_roll", $payment->student_roll)
            ->sum("discount");

        }

            $generator = new BarcodeGeneratorPNG();
            $barCode = '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($payment->student_roll, $generator::TYPE_CODE_128)) . '">';


        return view("pdf.invoice", compact("payment", "barCode", "prevDue"));
        })->name("print.invoice");


        Volt::route("/exam/single/{id}","exam.single")->name("exam.single");

        Route::get('/print/admit_card/{id}/{student?}', function ($id,$student=null) {
            $exam = Exam::with(["exam_routines", "batch:id,name","course:id,name"])
            ->findOrFail($id);

            $exam->load(["batch.students"=>fn($q)=>$q->when($student!="all",function($qq)use($student){
                return $qq->where("id",$student);
            })->where("year",$exam->year),"batch.students.personalDetails:id,student_id,smobile"]);



            return view("pdf.admit.index", compact("exam"));
        })->name("print.admit_card");

    Route::get('/print/result_sheet/{id}/{student_id?}', function ($id, $student_id = null) {
        $result = Result::with(["exam","exam.course", "resultSubjects" => fn ($q) => $q->where("first_part_id", null), "resultSubjects.has2ndPart", "resultSubjects.marks", "resultSubjects.has2ndPart.marks"])->findOrFail($id);
        $resultStudents = Student::with(["personalDetails", "package"])->whereHas("result_marks", function ($q) use ($id) {
            return $q->where("result_id", $id);
        })->when($student_id != "all", function ($q) use ($student_id) {
            return $q->where("id", $student_id);
        })->get();

        return view("pdf.result.index", compact("result", "resultStudents"));
    })->name("print.result_sheet");

        Route::get("/report-income-pdf",function(Request $request){

            $recievedBy = $request->filterRecievedBy;
            if($request->filterRecievedBy!="all"){
                $recievedBy =  explode(",", $recievedBy);
            }

        $payments = Payment::with("student", "recievedBy")
            ->when($request->from,function($q)use($request){
                return $q->whereDate("created_at",">=",$request->from);
            })
            ->when($request->to,function($q)use($request){
                return $q->whereDate("created_at","<=",$request->to);
            })
            ->when($request->filterRecievedBy!="all",function($q)use($request,$recievedBy){
                return $q->whereIn("recieved_by",$recievedBy);
            })
            ->when($request->filterPayType,function($q)use($request){
                return $q->where("payType",$request->filterPayType);
            })
            ->when($request->filterPaymentType,function($q)use($request){
                return $q->where("paymentType",$request->filterPaymentType);
            })
            ->latest()->get();

        $bookSells = BookSell::with("addedBy")->when($request->from, function ($q) use ($request) {
                return $q->whereDate("created_at",">=",$request->from);
            })
            ->when($request->to,function($q)use($request){
                return $q->whereDate("created_at","<=",$request->to);
            })
            ->when($request->filterRecievedBy!="all",function($q) use($request,$recievedBy){
                return $q->whereIn("added_by",$recievedBy);
            })->get();

        $dates = Expense::whereBetween("date", [$request->from, $request->to])
        ->select("date")
        ->groupBy("date")
        ->get();

        $expenses =  Expense::when(
            $request->types != [],
            function ($q) use ($request) {
                return $q->whereIn("type", $request->types);
            }
        )->whereBetween("date", [$request->from, $request->to])
        ->get();


        $expenseCategories = ExpenseCategory::where("active", 1)->get();


        return view("exports.income", compact("payments", "bookSells", "expenses", "expenseCategories", "dates"));

        });




    });


require __DIR__ . '/auth.php';
