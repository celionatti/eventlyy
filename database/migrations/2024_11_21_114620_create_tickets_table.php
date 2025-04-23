<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * Tickets Migration 
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
        Schema::create("tickets", function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->index('ticket_id');
            $table->string('event_id');
            $table->string('type');
            $table->string('details');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
        });
    }

    /**
     * The Down method is to drop table
     *
     * @return void
     */
    public function down():void
    {
        Schema::dropIfExists("tickets");
    }
};