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
    Schema::create('tender_announcements', function (Blueprint $table) {
      $table->id();

      $table->foreignId('tender_id')
            ->constrained('tenders')
            ->onDelete('cascade');

      $table->string('title');

      $table->text('message');

      $table->foreignId('created_by')
            ->constrained('admins')
            ->onDelete('cascade');

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tender_announcements');
  }
};
