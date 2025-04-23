<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * Team Migration 
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
        Schema::create("teams", function (Blueprint $table) {
            $table->id();
            $table->string('team_id')->index('team_id');
            $table->string('name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('email')->unique('email');
            $table->string('image')->nullable();
            $table->string('role')->nullable();
            $table->string('socials');
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
        Schema::dropIfExists("teams");
    }
};