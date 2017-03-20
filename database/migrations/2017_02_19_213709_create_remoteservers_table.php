<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemoteServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remote_servers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');
            $table->string('domain');
            $table->string('username');
            $table->string('plan-name');
            $table->integer('max-emails');
            $table->integer('disk-used');
            $table->integer('disk-limit');
            $table->boolean('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remote_servers');
    }
}
