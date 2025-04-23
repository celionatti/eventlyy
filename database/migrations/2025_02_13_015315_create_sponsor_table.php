<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * Sponsor Migration 
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
        Schema::create("sponsors", function (Blueprint $table) {
            $table->id();
            $table->string('sponsor_id')->nullable();
            $table->string('name')->unique('name');
            $table->string('image');
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
        Schema::dropIfExists("sponsors");
    }
};