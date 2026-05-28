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
    Schema::create('aanwijzings', function (Blueprint $table) {
        $table->id();
        // Terhubung ke tender yang sedang dibahas
        $table->foreignId('tender_id')->constrained('tenders')->cascadeOnDelete();
        // Terhubung ke vendor yang bertanya
        $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
        
        $table->text('question'); // Pertanyaan dari vendor
        $table->text('answer')->nullable(); // Jawaban dari admin (awalnya kosong)
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aanwijzings');
    }
};
