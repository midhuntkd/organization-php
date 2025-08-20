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
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
            $table->string('logo')->nullable()->after('slug');                // store path like "organizations/logos/xxx.png"
            $table->string('background_image')->nullable()->after('logo');    // store path like "organizations/backgrounds/xxx.jpg"
            $table->string('org_prefix')->unique()->after('background_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropUnique(['slug', 'org_prefix']);
            $table->dropColumn(['slug', 'logo', 'background_image','org_prefix']);
        });
    }
};
