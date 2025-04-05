<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('s_s_l', function (Blueprint $table) {
            // 🔥 First drop the foreign key constraint
            $table->dropForeign(['monitor_id']);

            // 💥 Then drop the column
            $table->dropColumn('monitor_id');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('s_s_l', function (Blueprint $table) {
            // If you ever roll back, this will restore the column
            $table->unsignedBigInteger('monitor_id')->nullable();

            // Add the foreign key back if needed
            $table->foreign('monitor_id')->references('id')->on('monitors')->onDelete('cascade');
        });
    }
};
