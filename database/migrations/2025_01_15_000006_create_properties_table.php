<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            // ===== Relations =====
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete()
                ->comment('Owner/agent who listed the property');

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('property_type_id')
                ->constrained('property_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('state_id')
                ->constrained('states')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('city_id')
                ->constrained('cities')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // ===== Basic Info =====
            $table->string('title', 255);
            $table->string('slug', 300)->unique();
            $table->string('reference_no', 50)->unique()->nullable()
                ->comment('Auto-generated property code e.g. RKV-2025-0001');
            $table->text('description')->nullable();
            $table->longText('highlights')->nullable()->comment('Bullet-point highlights');

            // ===== Listing Info =====
            $table->enum('listing_type', ['sale', 'rent'])->default('sale');
            $table->enum('status', [
                'draft',
                'pending',
                'active',
                'inactive',
                'sold',
                'rented',
                'rejected',
            ])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            // ===== Pricing =====
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('price_per_sqft', 10, 2)->nullable();
            $table->decimal('maintenance_charges', 10, 2)->nullable();
            $table->decimal('security_deposit', 12, 2)->nullable()->comment('For rentals');
            $table->boolean('is_negotiable')->default(true);
            $table->string('currency', 5)->default('INR');

            // ===== Area / Dimensions =====
            $table->decimal('area', 12, 2)->default(0)->comment('Built-up area');
            $table->enum('area_unit', ['sqft', 'sqm', 'sqyd', 'acre', 'hectare'])->default('sqft');
            $table->decimal('carpet_area', 12, 2)->nullable();
            $table->decimal('plot_area', 12, 2)->nullable();
            $table->decimal('built_up_area', 12, 2)->nullable();

            // ===== Configuration =====
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(0);
            $table->integer('balconies')->default(0);
            $table->integer('total_floors')->nullable();
            $table->integer('floor_number')->nullable();
            $table->integer('parking_spaces')->default(0);
            $table->integer('age_of_property')->nullable()->comment('In years');
            $table->enum('furnishing', ['unfurnished', 'semi-furnished', 'furnished'])->default('unfurnished');
            $table->enum('facing', [
                'north',
                'south',
                'east',
                'west',
                'north-east',
                'north-west',
                'south-east',
                'south-west',
            ])->nullable();
            $table->enum('ownership', [
                'freehold',
                'leasehold',
                'co-operative',
                'power-of-attorney',
            ])->nullable();

            // ===== Location Details =====
            $table->string('address', 500)->nullable();
            $table->string('locality', 200)->nullable();
            $table->string('landmark', 200)->nullable();
            $table->string('pincode', 10)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // ===== Contact Info =====
            $table->string('contact_name', 150)->nullable();
            $table->string('contact_email', 150)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('contact_whatsapp', 20)->nullable();
            $table->boolean('show_contact_publicly')->default(true);

            // ===== Media =====
            $table->string('featured_image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('virtual_tour_url')->nullable();

            // ===== SEO =====
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 500)->nullable();

            // ===== Analytics =====
            $table->integer('views_count')->default(0);
            $table->integer('inquiries_count')->default(0);
            $table->integer('favorites_count')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // ===== Indexes =====
            $table->index(['status', 'is_active']);
            $table->index('listing_type');
            $table->index('is_featured');
            $table->index('is_verified');
            $table->index(['city_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index(['property_type_id', 'status']);
            $table->index('price');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
