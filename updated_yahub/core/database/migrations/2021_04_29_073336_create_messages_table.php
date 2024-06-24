<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id',11);
            $table->integer('chat_id');
            $table->longText('message');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->integer('is_text');
            $table->integer('is_image')->default(0);
            $table->integer('is_video')->default(0);
            $table->integer('is_file')->default(0);
            $table->string('mime_type',100)->nullable();
            $table->integer('is_delivered')->default(0);
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
        Schema::dropIfExists('messages');
    }
}
