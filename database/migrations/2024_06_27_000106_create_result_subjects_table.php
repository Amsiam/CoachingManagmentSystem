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
        Schema::create('result_subjects', function (Blueprint $table) {
            $table->id();
            $table->boolean("active")->default(1);
            $table->foreignId("result_id")->constrained()->cascadeonDelete();
            $table->string("name");
            $table->string("code");
            $table->string("cq_mark")->default(0);
            $table->string("mcq_mark")->default(0);
            $table->string("practical_mark")->default(0);
            $table->foreignId("first_part_id")->nullable()->constrained("result_subjects")->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_subjects');
    }
};
