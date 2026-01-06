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
        Schema::table('membership_plan_upgrade_requests', function (Blueprint $table) {
            $table->boolean('hide_status')->default(false)->after('reject_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membership_plan_upgrade_requests', function (Blueprint $table) {
            $table->dropColumn('hide_status');
        });
    }
};
