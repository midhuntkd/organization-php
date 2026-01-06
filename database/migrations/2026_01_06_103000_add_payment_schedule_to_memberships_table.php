<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->unsignedTinyInteger('payment_day_of_month')->default(1)->after('monthly_fee');
            $table->enum('payment_frequency', ['monthly', 'quarterly', 'biannually', 'annually'])
                ->default('monthly')
                ->after('payment_day_of_month');
        });
    }

    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn(['payment_day_of_month', 'payment_frequency']);
        });
    }
};
