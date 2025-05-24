<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionIdToCouponCodesTable extends Migration
{
    public function up()
    {
        Schema::table('coupon_codes', function (Blueprint $table) {
            $table->unsignedBigInteger('subscription_id')->nullable()->after('id');

            $table->foreign('subscription_id')
                  ->references('id')
                  ->on('subscriptions')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('coupon_codes', function (Blueprint $table) {
            $table->dropForeign(['subscription_id']);
            $table->dropColumn('subscription_id');
        });
    }
}

