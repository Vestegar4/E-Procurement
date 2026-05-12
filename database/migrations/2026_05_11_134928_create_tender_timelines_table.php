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
    Schema::create('tender_timelines', function (Blueprint $table) {
      $table->id();

      $table->foreignId('tender_id')
            ->constrained('tenders')
            ->onDelete('cascade');

      // fase
      $table->timestamp('registration_start')->nullable();
      $table->timestamp('registration_end')->nullable();

      // aanwijzing
      $table->timestamp('aanwijzing_at')->nullable();

      // bidding
      $table->timestamp('bidding_start')->nullable();
      $table->timestamp('bidding_end')->nullable();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tender_timelines');
  }
};
