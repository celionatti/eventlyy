<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * Setting Migration 
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
        Schema::create("settings", function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique('name');
            $table->text('value')->nullable();
            $table->enum('status', ['active', 'disable'])->default("disable");
        });
    }

    /**
     * The Down method is to drop table
     *
     * @return void
     */
    public function down():void
    {
        Schema::dropIfExists("settings");
    }
};