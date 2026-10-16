<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->string('icon', 100)->default('fas fa-home')->comment('Font Awesome icon class');
            $table->string('color', 20)->default('#d8232a');
            $table->text('description')->nullable();
            $table->enum('listing_type', ['sale', 'rent', 'both'])->default('both')
                ->comment('Is this type for sale, rent, or both');
            $table->boolean('is_residential')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
            $table->index('listing_type');
            $table->index('is_residential');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_types');
    }
};
