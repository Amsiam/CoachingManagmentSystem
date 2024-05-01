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
        Schema::create('classs_group', function (Blueprint $table) {
            $table->foreignId("classs_id")->constrained()->cascadeOnDelete();
            $table->foreignId("group_id")->constrained()->cascadeOnDelete();
            $table->primary(['classs_id', 'group_id']);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classgroup');
    }
};
