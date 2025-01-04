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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bn_name')->nullable();
            $table->string('roll');
            $table->string('password');


            $table->string('image')->nullable();


            $table->integer('year');

            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete();


            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();


            $table->boolean("active")->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['package_id', 'batch_id', "year", "roll", "deleted_at"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
