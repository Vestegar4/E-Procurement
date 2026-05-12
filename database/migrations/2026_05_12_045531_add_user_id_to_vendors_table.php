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
    Schema::table('vendors', function (Blueprint $table) {
      $table->foreignId('user_id')
        ->after('id')
        ->constrained('users')
        ->onDelete('cascade');

      // Remove duplicate fields that are now in users table
      $table->dropColumn(['email', 'password']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('vendors', function (Blueprint $table) {
      $table->dropForeignIdFor('users');
      $table->dropColumn('user_id');

      $table->string('email')->unique()->after('id');
      $table->string('password')->after('email');
    });
  }
};
