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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            // Relasi ke tender dan vendor yang sudah ada
            $table->foreignId('tender_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            
            $table->string('po_number')->unique(); // Nomor surat PO
            $table->decimal('total_amount', 15, 2); // Total nilai kontrak
            $table->enum('status', ['draft', 'issued', 'completed', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
