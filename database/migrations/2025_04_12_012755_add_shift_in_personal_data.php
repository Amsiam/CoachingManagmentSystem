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
        Schema::table('personal_details', function (Blueprint $table) {
            $table->after('group', function (Blueprint $table) {
                $table->foreignId('shift')->nullable()->constrained('shifts')->nullOnDelete();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_details', function (Blueprint $table) {
            //
        });
    }
};
