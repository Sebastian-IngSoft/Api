<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            /**
             * Migration: create_images_table
             *
             * Description: This migration creates the "images" table in the database.
             * The table has the following columns:
             * - id: The primary key of the table.
             * - url: The URL of the image.
             * - imageable_id: The ID of the related model.
             * - imageable_type: The type of the related model.
             * - created_at: The timestamp when the record was created.
             * - updated_at: The timestamp when the record was last updated.
             */
            $table->id();
            $table->string('url');
            $table->morphs('imageable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
