<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteCascadeSsl extends Migration
{
    public function up()
    {
        Schema::table('s_s_l', function (Blueprint $table) {
            // First drop the existing foreign key if it exists
            $table->dropForeign(['user_id']);

            // Then re-add it with ON DELETE CASCADE
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('s_s_l', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            // Optionally re-add without cascade (or leave it out)
            $table->foreign('user_id')
                  ->references('id')->on('users');
        });
    }
}
