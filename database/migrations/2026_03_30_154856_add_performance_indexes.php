<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->index('is_active');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->index('is_active');
            $table->index(['business_id', 'is_active']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->index('is_active');
            $table->index(['business_id', 'is_active']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->index('status');
            $table->index('starts_at');
            $table->index(['business_id', 'status']);
            $table->index(['business_id', 'starts_at']);
            $table->index('customer_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('business_id');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['business_id', 'is_active']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['business_id', 'is_active']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['starts_at']);
            $table->dropIndex(['business_id', 'status']);
            $table->dropIndex(['business_id', 'starts_at']);
            $table->dropIndex(['customer_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['business_id']);
        });
    }
};
