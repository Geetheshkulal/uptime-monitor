<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubUserColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_user_id')->nullable();

            $table->foreign('parent_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); // optional: delete sub-users if parent is deleted
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_user_id']);
        });
    }
}
