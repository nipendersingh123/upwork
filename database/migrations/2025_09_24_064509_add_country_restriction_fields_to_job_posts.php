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
        Schema::table('job_posts', function (Blueprint $table) {
            $table->enum('country_restriction_type', ['none', 'include', 'exclude'])
                ->default('none')
                ->after('level');
            $table->json('allowed_countries')->nullable()->after('country_restriction_type');
            $table->json('excluded_countries')->nullable()->after('allowed_countries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->dropColumn(['country_restriction_type', 'allowed_countries', 'excluded_countries']);
        });
    }
};
