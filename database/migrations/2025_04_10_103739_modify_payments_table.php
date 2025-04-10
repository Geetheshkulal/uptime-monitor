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
        Schema::table('payment', function (Blueprint $table) {
            // Drop the client-side amount column (unsafe for pricing)
            $table->dropColumn('amount');

            // Add subscription_id as a foreign key
            $table->unsignedBigInteger('subscription_id')->after('id');
            $table->foreign('subscription_id')
                  ->references('id')
                  ->on('subscriptions')
                  ->onDelete('cascade'); // Optional: cascade delete if subscription is removed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Reverse the changes (for rollback)
            $table->decimal('amount', 10, 2)->nullable(); // Re-add amount column
            $table->dropForeign(['subscription_id']); // Drop foreign key first
            $table->dropColumn('subscription_id');
        });
    }
};