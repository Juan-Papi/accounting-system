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
        Schema::create('accounting_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Código contable (ej: 1.1.01)
            $table->string('name');           // Nombre de la cuenta (ej: Caja General)
            $table->enum('type', ['activo', 'pasivo', 'patrimonio', 'ingreso', 'gasto']);
            $table->boolean('is_parent')->default(true); // Si es una cuenta mayor o auxiliar
            $table->foreignId('parent_account_id')->nullable()->constrained('accounting_accounts')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_accounts');
    }
};
