<?php

use App\Models\Setting;
use App\Models\Student;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Xenon\LaravelBDSms\Facades\SMS;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


//schedule message
Schedule::call(function () {
    $auto_sms_message = Setting::where("key", "auto_sms_message")->first()?->value ?? "";
    $auto_sms = Setting::where("key", "auto_sms")->first()?->value ?? false;

    if ($auto_sms) {
        $students = Student::with(["courses", "batches", "personalDetails"])->where("package_id", 1)
            ->whereDoesntHave("payments", function ($query) {
                $query->whereBetween("month", [now()->firstOfMonth(), now()->lastOfMonth()]);
            })->get();

        foreach ($students as $student) {
            $message = $auto_sms_message;
            $message = str_replace(
                ["STUDENT_NAME", "STUDENT_ROLL", "STUDENT_COURSE", "STUDENT_BATCH", "DUE_AMOUNT"],
                [
                    $student->name,
                    $student->roll,
                    $student->courses?->pluck("name")->implode(", "),
                    $student->batches?->pluck("name")->implode(", "),
                    $student->courses->sum("price")
                ],
                $message
            );
            SMS::shoot($student->personalDetails?->smobile, $message);
        }
    }
})->daily();
