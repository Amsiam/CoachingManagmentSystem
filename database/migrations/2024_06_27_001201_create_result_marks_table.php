<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('result_marks', function (Blueprint $table) {
            $table->foreignId("result_id")->constrained()->cascadeOnDelete();
            $table->foreignId("subject_id")->constrained("exam_routines")->cascadeOnDelete();
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->integer("cq")->default(0);
            $table->integer("mcq")->default(0);
            $table->timestamps();

            $table->primary(["result_id","subject_id","student_id"]);
            $table->unique(["result_id","subject_id","student_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_marks');
    }
};
