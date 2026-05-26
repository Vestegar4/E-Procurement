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
    Schema::create('vendor_documents', function (Blueprint $table) {

      $table->id();

      // relasi vendor
      $table->foreignId('vendor_id')
        ->constrained('vendors')
        ->onDelete('cascade');

      // jenis dokumen
      $table->enum('document_type', [
        'npwp',
        'nib',
        'siup',
        'company_profile',
        'domicile_letter',
        'other'
      ]);

      // nama file asli
      $table->string('document_name');

      // lokasi file
      $table->string('file_path');

      // status verifikasi
      $table->enum('status', [
        'pending',
        'approved',
        'rejected'
      ])->default('pending');

      // catatan admin
      $table->text('notes')->nullable();

      // waktu upload
      $table->timestamp('uploaded_at')->nullable();

      // waktu verifikasi admin
      $table->timestamp('verified_at')->nullable();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('vendor_documents');
  }
};
