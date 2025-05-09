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
        $table->string('coupon_code')->nullable()->after('subscription_id');
        $table->decimal('coupon_value')->nullable()->after('coupon_code');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment', function (Blueprint $table) {
            //
        });
    }
};
