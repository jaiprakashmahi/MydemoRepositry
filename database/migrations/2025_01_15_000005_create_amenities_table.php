<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->string('icon', 100)->default('fas fa-check')->comment('Font Awesome icon');
            $table->string('color', 20)->default('#10b981');
            $table->text('description')->nullable();
            $table->enum('group', [
                'basic',
                'safety',
                'luxury',
                'society',
                'utility',
                'outdoor',
                'other',
            ])->default('basic')->comment('Grouping for UI display');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'group']);
            $table->index('is_featured');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
