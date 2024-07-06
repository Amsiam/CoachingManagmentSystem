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
            $table->foreignId("subject_id")->constrained("result_subjects")->cascadeOnDelete();
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->integer("cq")->default(0);
            $table->integer("mcq")->default(0);
            $table->integer("practical")->default(0);
            $table->boolean("is_optional")->default(false);
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
