<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * FailedLogin Migration 
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
        Schema::create("failed_logins", function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->integer('attempts')->default(0);
            $table->datetime('blocked_until')->nullable();
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
        Schema::dropIfExists("failed_logins");
    }
};