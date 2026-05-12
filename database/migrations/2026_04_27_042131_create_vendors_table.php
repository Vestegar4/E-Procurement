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
    Schema::create('vendors', function (Blueprint $table) {
      $table->id();
      // akun login
      $table->string('name'); // nama PIC
      $table->string('email')->unique();
      $table->string('password');

      // data perusahaan
      $table->string('company_name');
      $table->text('address');
      $table->string('phone');
      $table->string('npwp')->nullable();

      // status verifikasi
      $table->enum('status', [
        'pending',
        'approved',
        'rejected'
      ])->default('pending');

      $table->timestamp('approved_at')->nullable();

      $table->rememberToken();
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('vendors');
  }
};
