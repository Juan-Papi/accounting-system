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
        Schema::create('journal_entriy_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained()->onDelete('cascade'); // Relación con JournalEntry
            $table->foreignId('accounting_account_id')->constrained()->onDelete('cascade'); // Relación con Account
            $table->decimal('credit', 15, 2)->default(0); // Monto de haber
            $table->string('description'); // Descripción de la transacción

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entriy_details');
    }
};
