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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer("total")->default(0);
            $table->integer("discount")->default(0);
            $table->integer("paid")->default(0);
            $table->string("payType")->default("Hand");
            $table->string("paymentType")->default(0)->comment("0:Montly,1:Due Payment, 2:Admission");

            $table->date("month")->nullable();

            $table->integer("due")->virtualAs("total-discount-paid");
            $table->date("due_date")->nullable();


            $table->string("remarks")->nullable();

            $table->integer("student_roll");

            $table->string("recieved_by")->default("Admin");
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
        Schema::dropIfExists('payments');
    }
};
