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
    Schema::create('tender_results', function (Blueprint $table) {
      $table->id();

      $table->foreignId('tender_id')
            ->constrained('tenders')
            ->onDelete('cascade');

      $table->foreignId('winner_vendor_id')
            ->constrained('vendors')
            ->onDelete('cascade');

      $table->decimal('winning_bid', 15, 2);

      $table->text('notes')->nullable();

      $table->foreignId('selected_by')
            ->constrained('admins')
            ->onDelete('cascade');

      $table->timestamp('selected_at')->nullable();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tender_results');
  }
};
