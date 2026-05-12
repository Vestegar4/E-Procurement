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
    Schema::create('bids', function (Blueprint $table) {
      $table->id();

      $table->foreignId('tender_id')
        ->constrained('tenders')
        ->onDelete('cascade');

      $table->foreignId('vendor_id')
        ->constrained('vendors')
        ->onDelete('cascade');

      $table->decimal('bid_amount', 15, 2);

      $table->string('bid_document')->nullable();

      $table->text('notes')->nullable();

      $table->timestamp('submitted_at');

      $table->timestamps();

      $table->unique(['tender_id', 'vendor_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('bids');
  }
};
