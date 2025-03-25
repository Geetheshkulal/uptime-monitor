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
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropForeign(['monitor_id']); // Drop existing foreign key
            $table->foreign('monitor_id')
                ->references('id')
                ->on('monitors')
                ->onDelete('cascade'); // Add cascade delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropForeign(['monitor_id']);
            $table->foreign('monitor_id')
                ->references('id')
                ->on('monitors');
        });
    }
};
