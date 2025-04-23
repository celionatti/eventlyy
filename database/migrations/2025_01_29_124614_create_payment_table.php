<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * Payment Migration 
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
        Schema::create("payments", function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->index('payment_id');
            $table->string('user_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->unique('account_number');
            $table->string('account_name');
            $table->decimal('balance', 10, 2);
            $table->enum('status', ['active', 'disable'])->default('disable');
        });
    }

    /**
     * The Down method is to drop table
     *
     * @return void
     */
    public function down():void
    {
        Schema::dropIfExists("payments");
    }
};