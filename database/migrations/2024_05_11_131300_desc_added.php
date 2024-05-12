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
        Schema::table('book_sells', function (Blueprint $table) {

            $table->after("id",function($t) {

                $t->string("description")->default("")->nullable();

            });

            $table->after("id",function($t) {

                $t->string("added_by");

            });
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_sells', function (Blueprint $table) {
            //
        });
    }
};
