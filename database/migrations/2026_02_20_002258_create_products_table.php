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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique(); // SKU único
            $table->string('name');          // Nome obrigatório
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Preço com 2 casas decimais
            $table->string('category')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes(); // Soft delete (diferencial)
            $table->timestamps();

            // Índices para performance nas buscas
            $table->index('status');
            $table->index('category');
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};