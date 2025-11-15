<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            if (!Schema::hasColumn('user_details', 'blood_group')) {
                $table->string('blood_group', 5)->nullable()->after('zipcode');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            if (Schema::hasColumn('user_details', 'blood_group')) {
                $table->dropColumn('blood_group');
            }
        });
    }
};