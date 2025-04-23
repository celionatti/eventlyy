<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * Event Migration 
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
        Schema::create("events", function (Blueprint $table) {
            $table->id();
            $table->string('event_id')->index('event_id');
            $table->string('user_id')->nullable();
            $table->string('category')->nullable();
            $table->string('tags')->nullable();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('date_time');
            $table->string('phone')->nullable();
            $table->string('mail')->nullable();
            $table->string('socials')->nullable();
            $table->boolean("is_highlighted")->default(0);
            $table->enum('status', ['open', 'closed', 'cancelled', 'pending'])->default("pending");
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
        Schema::dropIfExists("events");
    }
};