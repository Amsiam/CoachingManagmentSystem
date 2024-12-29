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
        Schema::table('students', function (Blueprint $table) {

            $table->after("password", function (Blueprint $t) {
                $t->decimal('monthly_salary', 10, 2)->nullable();
                $t->boolean("fixed_salary")->nullable()->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('monthly_salary');
            $table->dropColumn('fixed_salary');
        });
    }
};
