<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment', function (Blueprint $table) {
            $table->enum('discount_type', ['flat', 'percentage'])->nullable()->after('coupon_code');
        });
    }

    public function down(): void
    {
        Schema::table('payment', function (Blueprint $table) {
            $table->dropColumn('discount_type');
        });
    }
};
