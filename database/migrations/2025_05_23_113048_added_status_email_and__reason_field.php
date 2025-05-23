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
        Schema::table('traffic_logs', function (Blueprint $table) {
            Schema::table('traffic_logs', function (Blueprint $table) {
                $table->string('email')->after('browser')->nullable();
                $table->string('status')->after('email')->nullable(); // 'success', 'failed_login', 'failed_register'
                $table->string('reason')->after('status')->nullable(); // Optional reason description
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('traffic_logs', function (Blueprint $table) {
            //
        });
    }
};
