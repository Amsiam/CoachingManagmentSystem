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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();

            $table->foreignId("package_id")->constrained()->cascadeOnDelete();
            $table->foreignId("course_id")->constrained()->cascadeOnDelete();

            $table->foreignId("batch_id")->nullable()->constrained()->nullOnDelete();
            $table->foreignId("group_id")->nullable()->constrained()->nullOnDelete();

            $table->string("name");

            $table->string("year");

            $table->foreignId("user_id")->nullable()->constrained()->nullOnDelete();


            $table->boolean("active")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
