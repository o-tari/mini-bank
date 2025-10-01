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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('purpose');
            $table->integer('duration_months');
            $table->decimal('monthly_payment', 15, 2);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->decimal('income', 15, 2);
            $table->enum('status', ['pending', 'approved', 'rejected', 'disbursed', 'completed', 'defaulted'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->text('approval_comments')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('disbursed_at')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
