<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            $table->string('mail_id')->unique();
            $table->string('body')->nullable();
            $table->string('from')->nullable();
            $table->string('date')->nullable();
            $table->string('message_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('to')->nullable();
            $table->string('thread_id')->nullable();
            $table->string('label_ids')->nullable();
            $table->string('history_id')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_name')->nullable();
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
        Schema::dropIfExists('mails');
    }
};
