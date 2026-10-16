<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')
                ->constrained('states')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->string('pincode', 10)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_popular')->default(false)->comment('Show on homepage');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['state_id', 'is_active']);
            $table->index('is_popular');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
