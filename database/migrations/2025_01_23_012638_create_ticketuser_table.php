<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * TicketUser Migration 
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
        Schema::create("ticket_users", function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('ticket_id')->index('ticket_id');
            $table->integer('quantity');
            $table->string('token')->nullable();
            $table->string('assign_to')->nullable();
        });
    }

    /**
     * The Down method is to drop table
     *
     * @return void
     */
    public function down():void
    {
        Schema::dropIfExists("ticket_users");
    }
};