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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('membership_id')->nullable()->constrained('memberships')->nullOnDelete()->after('organization_id');
            $table->string('membership_code', 100)->nullable()->after('membership_id');

            // Ensure uniqueness per organization for the code
            $table->unique(['organization_id', 'membership_code'], 'users_org_membership_code_unique');
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_org_membership_code_unique');
            $table->dropConstrainedForeignId('membership_id');
            $table->dropColumn(['membership_id', 'membership_code']);
        });
    }
};
