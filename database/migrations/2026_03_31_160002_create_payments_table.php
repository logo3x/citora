<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('provider')->default('wompi');
            $table->unsignedInteger('amount');
            $table->string('currency', 3)->default('COP');
            $table->string('status')->default('pending');
            $table->string('period');
            $table->string('provider_transaction_id')->nullable();
            $table->json('provider_data')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'period']);
            $table->index('status');
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->unsignedInteger('monthly_appointment_limit')->default(200)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');

        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('monthly_appointment_limit');
        });
    }
};
