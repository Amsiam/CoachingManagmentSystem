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
        Schema::table('courses', function (Blueprint $t) {
            $t->after("package_id",function (Blueprint $table) {
                $table->tinyInteger("type")->default(2);
                $table->text("shortDesc")->default("")->nullable();
                $table->text("longDesc")->default("")->nullable();
                $table->string("image")->default("");
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(["full_name","shortDesc","longDesc","image"]);

        });
    }
};
