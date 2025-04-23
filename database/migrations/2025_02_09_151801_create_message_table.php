<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * Message Migration 
 * ===============       ================
 * ======================================
 */

 use celionatti\Bolt\Migration\Migration;
 use celionatti\Bolt\illuminate\Schema\Schema;
 use celionatti\Bolt\illuminate\Schema\Blueprint;

return new class extends Migration
{
    /**
     * The Up method is to create table.
     *
     * @return void
     */
    public function up():void
    {
        Schema::create("messages", function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable('email');
            $table->text('message');
            $table->enum('status', ['open', 'close'])->default('close');
            $table->timestamps();
        });
    }

    /**
     * The Down method is to drop table
     *
     * @return void
     */
    public function down():void
    {
        Schema::dropIfExists("messages");
    }
};