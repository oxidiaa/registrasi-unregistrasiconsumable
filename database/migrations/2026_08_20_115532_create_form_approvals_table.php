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
        Schema::create('form_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('form_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('requestor_name')->nullable();
            $table->string('requestor_dept')->nullable();
            $table->string('form_date')->nullable();
            $table->string('status')->default('BUTUH APPROVAL STAFF');

            // Step 1: User / Pembuat
            $table->timestamp('user_signed_at')->nullable();
            $table->string('user_signer_name')->nullable();
            $table->text('user_comment')->nullable();

            // Step 2: Staff Approver
            $table->timestamp('staff_signed_at')->nullable();
            $table->string('staff_signer_name')->nullable();
            $table->text('staff_comment')->nullable();

            // Step 3: Accounting Approver
            $table->timestamp('accounting_signed_at')->nullable();
            $table->string('accounting_signer_name')->nullable();
            $table->text('accounting_comment')->nullable();

            // Step 4: Warehouse Consumable (Registrasi)
            $table->timestamp('warehouse_signed_at')->nullable();
            $table->string('warehouse_signer_name')->nullable();
            $table->text('warehouse_comment')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_approvals');
    }
};
