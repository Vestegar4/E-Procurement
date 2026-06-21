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
    Schema::create('customer_services', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vendor_id')->nullable()->constrained('vendors')->cascadeOnDelete();
        $table->string('name')->nullable();
        $table->string('email')->nullable();
        
        $table->text('message'); 
        $table->text('admin_reply')->nullable(); // Kolom untuk balasan admin
        $table->enum('status', ['unread', 'read', 'answered', 'resolved'])->default('unread');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_services');
    }
};
