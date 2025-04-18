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
        Schema::table('payment', function (Blueprint $table) {
            $table->string('transaction_id')->change();
        });
    }

    public function down()
    {
        Schema::table('payment', function (Blueprint $table) {
            $table->bigInteger('transaction_id')->change(); // Rollback to integer if needed
        });
    }
};
