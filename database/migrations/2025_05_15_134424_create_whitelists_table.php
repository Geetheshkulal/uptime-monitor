<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhitelistsTable extends Migration
{
    public function up()
    {
        Schema::create('whitelist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('whitelist'); // Stores IPs as a JSON array
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('whitelist');
    }
}
