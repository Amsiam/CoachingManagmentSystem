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
        Schema::create('batch_student', function (Blueprint $table) {
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->foreignId("batch_id")->constrained()->cascadeOnDelete();
            $table->primary(['student_id', 'batch_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_student');
    }
};
