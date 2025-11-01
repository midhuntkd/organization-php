<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode', 10)->nullable();

            $table->string('norka_registration_number')->nullable();
            $table->text('permanent_home_address')->nullable();

            $table->string('insurance_provider')->nullable();
            $table->string('policy_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('amount', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
