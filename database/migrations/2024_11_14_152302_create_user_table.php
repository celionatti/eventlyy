<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * User Migration 
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
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->index('user_id');
            $table->string('first_name')->index('first_name')->nullable();
            $table->string('last_name')->index('last_name')->nullable();
            $table->string('email')->unique('email');
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('business_name')->nullable();
            $table->string('registration_number')->nullable();
            $table->enum('role', ['admin', 'user', 'organiser'])->default("user");
            $table->boolean('is_blocked')->default(0);
            $table->string('country')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('session_token')->nullable();
            $table->string('reset_token')->nullable();
            $table->string('reset_token_expiry')->nullable();
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
        Schema::dropIfExists("users");
    }
};