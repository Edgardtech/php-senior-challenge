<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        // Cache por 60 segundos para performance (Requisito de Cache)
        return Cache::remember('products_list', 60, function () {
            $products = Product::query()
                ->when(request('status'), fn ($q, $status) => $q->where('status', $status))
                ->when(request('category'), fn ($q, $cat) => $q->where('category', $cat))
                ->orderBy('name')
                ->paginate(15);

            return response()->json($products);
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(Product::rules());

        $product = Product::create($validated);

        // Limpar cache da lista ao criar novo produto
        Cache::forget('products_list');
        
        // Aqui entraria a sincronização com ElasticSearch (veremos no próximo passo)
        // $product->syncWithElastic(); 

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        
        // Regra de validação ignora o SKU do próprio produto sendo editado
        $validated = $request->validate(Product::rules($id));

        $product->update($validated);

        // Limpar cache
        Cache::forget('products_list');
        Cache::forget("product_{$id}");

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete(); // Soft Delete

        Cache::forget('products_list');
        Cache::forget("product_{$id}");

        return response()->json(null, 204);
    }
    
    /**
     * Restore a soft deleted product (Diferencial).
     */
    public function restore(string $id): JsonResponse
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        
        Cache::forget('products_list');
        
        return response()->json($product);
    }
}