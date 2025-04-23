<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * Category Migration 
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
        Schema::create("categories", function (Blueprint $table) {
            $table->id();
            $table->string('category_id');
            $table->string('name')->unique('name');
            $table->enum('status', ['active', 'inactive'])->default("inactive");
        });
    }

    /**
     * The Down method is to drop table
     *
     * @return void
     */
    public function down():void
    {
        Schema::dropIfExists("categories");
    }
};