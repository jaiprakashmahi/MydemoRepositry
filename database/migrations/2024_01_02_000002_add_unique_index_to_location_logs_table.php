<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('location_logs', function (Blueprint $table) {
            // A given employee can only have one ping at a given timestamp.
            // Lets the API safely use updateOrCreate() so a retried/re-sent
            // ping (common on flaky mobile networks) never double-inserts.
            $table->unique(['employee_id', 'recorded_at'], 'location_logs_employee_recorded_unique');
        });
    }

    public function down(): void
    {
        Schema::table('location_logs', function (Blueprint $table) {
            $table->dropUnique('location_logs_employee_recorded_unique');
        });
    }
};
