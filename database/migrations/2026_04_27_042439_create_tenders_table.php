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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('specification')->nullable();

            $table->decimal('budget', 15, 2)->nullable();
            

            $table->enum('status', [
                'draft',
                'open',
                'aanwijzing',
                'bidding', 
                'closed',
                'finished',
            ])->default('draft');

            $table->foreignId('created_by')
                  ->constrained('admins')
                  ->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
