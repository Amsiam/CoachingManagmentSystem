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
        Schema::create('academic_details', function (Blueprint $table) {
            $table->id();

            $table->string("exam");
            $table->string("board")->nullable();
            $table->string("institue")->nullable();
            $table->string("group")->nullable();
            $table->string("roll")->nullable();
            $table->string("passing_year")->nullable();
            $table->string("gpa")->nullable();
            $table->string("registration")->nullable();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();


            $table->boolean("active")->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_details');
    }
};
