<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * Payout Migration 
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
        Schema::create("payouts", function (Blueprint $table) {
            $table->id();
            $table->string('payout_id')->index('payout_id');
            $table->string('user_id')->nullable();
            $table->string('to_user')->nullable();
            $table->string('account_number')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('reference_id')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default("pending");
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
        Schema::dropIfExists("payouts");
    }
};