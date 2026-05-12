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
    Schema::create('tender_participants', function (Blueprint $table) {
      $table->id();

      $table->foreignId('tender_id')
        ->constrained('tenders')
        ->onDelete('cascade');

      $table->foreignId('vendor_id')
        ->constrained('vendors')
        ->onDelete('cascade');

      $table->timestamp('joined_at')->nullable();

      $table->timestamps();

      $table->unique(['tender_id', 'vendor_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tender_participants');
  }
};
