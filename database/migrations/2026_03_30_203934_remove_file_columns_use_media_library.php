<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['logo', 'banner']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('image')->nullable();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->string('image')->nullable();
        });
    }
};
