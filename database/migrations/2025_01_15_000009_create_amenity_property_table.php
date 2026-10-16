<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amenity_property', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                ->constrained('properties')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('amenity_id')
                ->constrained('amenities')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Optional extra data on the pivot
            $table->string('value')->nullable()
                ->comment('e.g. "Yes", "2", "24x7" for a specific amenity');
            $table->text('note')->nullable();

            $table->timestamps();

            // Prevent duplicate amenity on same property
            $table->unique(['property_id', 'amenity_id'], 'uniq_property_amenity');

            $table->index('amenity_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amenity_property');
    }
};
