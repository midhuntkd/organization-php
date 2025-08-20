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
            $table->unsignedBigInteger('organization_id')->after('email');
            $table->string('phone')->nullable()->after('organization_id');
            $table->string('verification_type')->nullable()->after('phone');
            $table->string('verification_id_number')->nullable()->after('verification_type');
            $table->string('verification_image')->nullable()->after('verification_type');
            $table->boolean('approved')->default(false)->after('verification_image');
            $table->boolean('password_changed')->default(false)->after('approved');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->unique(['organization_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
