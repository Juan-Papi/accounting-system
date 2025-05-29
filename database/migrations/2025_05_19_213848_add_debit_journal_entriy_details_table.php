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
        Schema::table('journal_entriy_details', function (Blueprint $table) {
            $table->decimal('debit', 15, 2)->default(0); // Monto de haber

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal_entriy_details', function (Blueprint $table) {
        $table->dropColumn('debit');

        });
    }
};
