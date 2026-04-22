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
        Schema::table('businesses', function (Blueprint $table) {
            $table->unsignedSmallInteger('cancellation_min_hours')->default(2)->after('monthly_appointment_limit');
            $table->unsignedSmallInteger('reschedule_min_hours')->default(2)->after('cancellation_min_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['cancellation_min_hours', 'reschedule_min_hours']);
        });
    }
};
