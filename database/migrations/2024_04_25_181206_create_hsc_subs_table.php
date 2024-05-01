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
        Schema::create('hsc_subs', function (Blueprint $table) {
            $table->id();

            $table->string("sub1")->nullable();
            $table->string("sub2")->nullable();
            $table->string("sub3")->nullable();
            $table->string("sub4")->nullable();


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
        Schema::dropIfExists('hsc_subs');
    }
};
