<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable; // <--- 1. Importar isso

class Product extends Model
{
    use HasFactory, SoftDeletes, Searchable; // <--- 2. Adicionar Searchable aqui

    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'category',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public static function rules($id = null)
    {
        $uniqueRule = $id ? "unique:products,sku,{$id}" : 'unique:products,sku';
        return [
            'sku' => "required|string|max:255|{$uniqueRule}",
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|gt:0',
            'category' => 'nullable|string|max:255',
            'status' => 'sometimes|in:active,inactive',
        ];
    }

    // 3. Definir o que será indexado no ElasticSearch
    public function toSearchableArray()
    {
        return [
            'id' => (string) $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'price' => (float) $this->price,
        ];
    }
}