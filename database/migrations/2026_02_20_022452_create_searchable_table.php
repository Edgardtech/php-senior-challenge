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
        Schema::create('searchable', function (Blueprint $table) {
            $table->morphs('searchable'); // Cria searchable_type e searchable_id
            $table->string('content');    // Texto da busca
            $table->index('content');     // Índice de performance
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('searchable');
    }
};