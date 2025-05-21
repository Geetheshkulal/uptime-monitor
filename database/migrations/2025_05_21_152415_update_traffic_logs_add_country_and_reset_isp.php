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

            if (Schema::hasColumn('traffic_logs', 'isp')) {
                $table->dropColumn('isp');
            }

            $table->string('isp')->after('user_agent')->nullable();
            $table->string('country', 2)->after('isp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
    }
};
