<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_payment_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('membership_id')->nullable()->constrained('memberships')->nullOnDelete();
            $table->enum('entry_type', ['charge', 'payment']);
            $table->enum('reason', ['joiningfee', 'monthlyfee', 'other'])->default('other');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('handover_person')->nullable();
            $table->string('proof_path')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status'], 'mpl_user_status_idx');
            $table->index(['organization_id', 'entry_type', 'reason'], 'mpl_org_entry_reason_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_payment_ledgers');
    }
};
