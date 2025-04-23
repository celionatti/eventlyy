<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============       ================
 * Article Migration 
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
        Schema::create("articles", function (Blueprint $table) {
            $table->id();
            $table->string('article_id')->index('article_id');
            $table->string('user_id')->nullable();
            $table->bigInteger('views')->default(0);
            $table->string('tag')->nullable();
            $table->string('title')->nullable();
            $table->string('image')->nullable();
            $table->text('content')->nullable();
            $table->string("meta_title");
            $table->string("meta_description");
            $table->string("meta_keywords");
            $table->string("contributors");
            $table->enum('status', ['draft', 'publish'])->default("draft");
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
        Schema::dropIfExists("articles");
    }
};