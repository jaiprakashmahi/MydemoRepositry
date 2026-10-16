<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')
                ->constrained('properties')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('image', 500)->comment('Storage path');
            $table->string('thumbnail', 500)->nullable();
            $table->string('title', 255)->nullable();
            $table->string('alt_text', 255)->nullable();
            $table->text('caption')->nullable();
            $table->enum('image_type', [
                'gallery',
                'floor_plan',
                'master_plan',
                'site_plan',
                'other',
            ])->default('gallery');
            $table->boolean('is_primary')->default(false)
                ->comment('Featured image shown first');
            $table->integer('sort_order')->default(0);
            $table->integer('file_size')->nullable()->comment('In KB');
            $table->string('mime_type', 100)->nullable();
            $table->timestamps();

            $table->index(['property_id', 'is_primary']);
            $table->index(['property_id', 'sort_order']);
            $table->index('image_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_images');
    }
};
