<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')
                ->constrained('properties')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('title', 255);
            $table->string('file_path', 500);
            $table->string('file_name', 255);
            $table->string('mime_type', 100)->nullable();
            $table->integer('file_size')->nullable()->comment('In KB');

            $table->enum('document_type', [
                'brochure',
                'floor_plan',
                'sale_deed',
                'title_deed',
                'noc',
                'tax_receipt',
                'encumbrance_certificate',
                'khata',
                'mutation',
                'agreement',
                'rera_certificate',
                'other',
            ])->default('other');

            $table->boolean('is_public')->default(false)
                ->comment('Visible to non-logged in users');
            $table->boolean('is_verified')->default(false);
            $table->date('issued_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['property_id', 'document_type']);
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_documents');
    }
};
