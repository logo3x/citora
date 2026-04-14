<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('plan_type')->default('monthly')->after('period');
            $table->unsignedSmallInteger('plan_days')->default(30)->after('plan_type');
            $table->string('origin')->default('citora')->after('plan_days');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['plan_type', 'plan_days', 'origin']);
        });
    }
};
