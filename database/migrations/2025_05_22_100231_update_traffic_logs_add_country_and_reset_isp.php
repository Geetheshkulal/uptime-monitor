<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('traffic_logs', function (Blueprint $table) {
            if (Schema::hasColumn('traffic_logs', 'isp')) {
                $table->dropColumn('isp');
            }
            if (Schema::hasColumn('traffic_logs', 'country')) {
                $table->dropColumn('country');
            }
        });

        Schema::table('traffic_logs', function (Blueprint $table) {
            $table->string('isp', 100)->after('user_agent')->nullable();
            $table->string('country', 50)->after('isp')->nullable();     // For flags (e.g. "US")

        });
    }

    public function down(): void
    {
        Schema::table('traffic_logs', function (Blueprint $table) {
            $table->dropColumn(['isp', 'country']);
            $table->string('isp', 100)->nullable();
            $table->string('country', 50)->nullable();
        });
    }
};
