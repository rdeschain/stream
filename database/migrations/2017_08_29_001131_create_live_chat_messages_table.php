<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiveChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_chat_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id')->unique();
            $table->string('author');
            $table->string('text');
            $table->string('author_channel');
            $table->string('published_at');
            $table->string('livemessage_id');
            //$table->string('broadcast_id');
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
        Schema::dropIfExists('live_chat_messages');
    }
}
