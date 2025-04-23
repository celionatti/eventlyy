<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * Transactions Migration 
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
        Schema::create("transactions", function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->index('transaction_id');
            $table->string('user_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('event_id');
            $table->string('ticket_id');
            $table->integer('quantity');
            $table->decimal('amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('reference_id')->nullable();
            $table->string('token')->nullable()->unique('token');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'reversed'])->default("pending");
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
        Schema::dropIfExists("transactions");
    }
};