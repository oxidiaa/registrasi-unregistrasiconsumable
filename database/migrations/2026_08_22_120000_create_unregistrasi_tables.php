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
        // 1. Unregistrasi Items Table
        Schema::create('unregistrasi_items', function (Blueprint $table) {
            $table->id();
            $table->string('form_number')->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('created_by_name')->nullable();
            $table->string('created_by_dept')->nullable();
            $table->string('kode_barang')->nullable();
            $table->string('nama_barang');
            $table->string('spesifikasi')->nullable();
            $table->string('kategori')->nullable();
            $table->text('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // 2. Unregistrasi Approvals Table (3-Step Workflow: User -> Staff -> Warehouse)
        Schema::create('unregistrasi_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('form_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('requestor_name')->nullable();
            $table->string('requestor_dept')->nullable();
            $table->string('form_date')->nullable();
            $table->string('status')->default('Butuh Approval Staff / Section Head');

            // User stage
            $table->timestamp('user_signed_at')->nullable();
            $table->string('user_signer_name')->nullable();
            $table->text('user_comment')->nullable();

            // Staff stage
            $table->timestamp('staff_signed_at')->nullable();
            $table->string('staff_signer_name')->nullable();
            $table->text('staff_comment')->nullable();

            // Warehouse stage (Discontinue)
            $table->timestamp('warehouse_signed_at')->nullable();
            $table->string('warehouse_signer_name')->nullable();
            $table->text('warehouse_comment')->nullable();

            $table->timestamps();
        });

        // 3. Unregistrasi Comments Table
        Schema::create('unregistrasi_comments', function (Blueprint $table) {
            $table->id();
            $table->string('form_number')->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name');
            $table->string('user_dept')->nullable();
            $table->string('user_role')->nullable();
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unregistrasi_comments');
        Schema::dropIfExists('unregistrasi_approvals');
        Schema::dropIfExists('unregistrasi_items');
    }
};
