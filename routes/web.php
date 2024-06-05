<?php

use App\Models\BookSell;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Picqer\Barcode\BarcodeGeneratorPNG;

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
        });


        Volt::route("/student/list","student.list")->middleware("can:student.list");

        Volt::route("/student/{id}","student.details")->middleware("can:student.list");

        Volt::route("/student/edit/{id}","admission.editStudent")->middleware("can:student.edit");


        Volt::route("/groups","academics.groupTable")->middleware("can:academics.group");
        Volt::route("/classes","academics.classs")->middleware("can:academics.class");
        Volt::route("/courses","academics.courses")->middleware("can:academics.course");
        Volt::route("/batches","academics.batches")->middleware("can:academics.batch");


        Route::prefix("/report")->group(function(){
            Volt::route("/income","reports.income")->middleware("can:report.income");
            Volt::route("/admission","reports.admission")->middleware("can:report.admission");
            Volt::route("/montly","reports.montly")->middleware("can:report.monthly");
        });


        Route::prefix("/user")->group(function(){
            Volt::route("/permission","user.permission")->middleware("can:permission.list");
            Volt::route("/roles","user.role")->middleware("can:role.list");
            Volt::route("/admin","user.admin")->middleware("can:admin.list");
        });


        Volt::route("/slider","slider")->middleware("can:admin.list");


        Route::get('/print/idcard/{id}', function ($id) {

            $student = Student::with(["personalDetails", "batches"])->findOrFail($id);

            $generator = new BarcodeGeneratorPNG();
            $barCode = base64_encode($generator->getBarcode($student->roll, $generator::TYPE_CODE_128));

            return view("pdf.idcard", compact("student", "barCode"));
        })->name("pdf.id");

        Route::get('/print/invoice/{id}', function ($id) {

            $payment = Payment::with(["student", "student.batches", "student.courses", "student.personalDetails"])->findOrFail($id);

            $generator = new BarcodeGeneratorPNG();
            $barCode = '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($payment->student_roll, $generator::TYPE_CODE_128)) . '">';


            return view("pdf.invoice", compact("payment", "barCode"));
        })->name("print.invoice");

        Route::get("/report-income-pdf",function(Request $request){

            $payments = Payment::with("student")
            ->when($request->from,function($q)use($request){
                return $q->whereDate("created_at",">=",$request->from);
            })
            ->when($request->to,function($q)use($request){
                return $q->whereDate("created_at","<=",$request->to);
            })
            ->when($request->filterRecievedBy!="all",function($q)use($request){
                return $q->whereIn("recieved_by",$request->filterRecievedBy);
            })
            ->when($request->filterPayType,function($q)use($request){
                return $q->where("payType",$request->filterPayType);
            })
            ->when($request->filterPaymentType,function($q)use($request){
                return $q->where("paymentType",$request->filterPaymentType);
            })
            ->latest()->get();

            $bookSells = BookSell::when($request->from,function($q)use($request){
                return $q->whereDate("created_at",">=",$request->from);
            })
            ->when($request->to,function($q)use($request){
                return $q->whereDate("created_at","<=",$request->to);
            })
            ->when($request->filterRecievedBy!="all",function($q) use($request){
                return $q->whereIn("added_by",$request->filterRecievedBy);
            })->get();

            return view("exports.income",compact("payments","bookSells"));

        });




    });


require __DIR__ . '/auth.php';
