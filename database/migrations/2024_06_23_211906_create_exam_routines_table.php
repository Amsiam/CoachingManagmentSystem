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
        Schema::create('exam_routines', function (Blueprint $table) {
            $table->id();
            $table->foreignId("exam_id")->constrained()->cascadeOnDelete();
            $table->string("code");
            $table->string("name");
            $table->date("date");
            $table->time("time");
            $table->string("cq_mark")->default(0);
            $table->string("mcq_mark")->default(0);
            $table->string("practical_mark")->default(0);
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
        Schema::dropIfExists('exam_routines');
    }
};
