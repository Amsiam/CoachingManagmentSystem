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
        Schema::create('personal_details', function (Blueprint $table) {
            $table->id();

            $table->string("bn_name")->nullable();
            $table->string("fname")->nullable();
            $table->string("mname")->nullable();
            $table->string("paddress")->nullable();
            $table->string("dob")->nullable();
            $table->string("blood")->nullable();
            $table->string("group")->nullable();
            $table->string("smobile");
            $table->string("gmobile");
            $table->string("quota")->default(0);
            $table->string("ref_name")->nullable();
            $table->string("ref_mobile")->nullable();

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
        Schema::dropIfExists('personal_details');
    }
};
