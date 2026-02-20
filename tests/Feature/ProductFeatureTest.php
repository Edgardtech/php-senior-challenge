<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductFeatureTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_product()
    {
        $payload = [
            'sku' => 'TEST-SKU-001',
            'name' => 'Produto de Teste',
            'description' => 'Descrição para teste automatizado',
            'price' => 99.90,
            'category' => 'Teste',
            'status' => 'active'
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('sku', 'TEST-SKU-001')
                 ->assertJsonPath('name', 'Produto de Teste');

        $this->assertDatabaseHas('products', ['sku' => 'TEST-SKU-001']);
    }

    #[Test]
    public function it_validates_required_fields_on_creation()
    {
        $response = $this->postJson('/api/products', [
            'sku' => 'TEST-SKU-002'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'price']);
    }

    #[Test]
    public function it_can_list_products_with_pagination()
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [['id', 'sku', 'name', 'price']],
                     'current_page',
                     'total'
                 ]);
    }

    #[Test]
    public function it_can_search_products_by_name()
    {
        Product::create([
            'sku' => 'SEARCH-01',
            'name' => 'Teclado Mecânico RGB',
            'price' => 250.00,
            'status' => 'active'
        ]);

        $results = \App\Models\Product::search('Teclado')->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Teclado Mecânico RGB', $results->first()->name);
    }

    #[Test]
    public function it_can_soft_delete_a_product()
    {
        $product = Product::create([
            'sku' => 'DEL-001',
            'name' => 'Para Deletar',
            'price' => 10.00,
            'status' => 'active'
        ]);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);
        
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
    
    #[Test]
    public function it_can_restore_a_deleted_product()
    {
        $product = Product::create([
            'sku' => 'REST-001',
            'name' => 'Para Restaurar',
            'price' => 10.00,
            'status' => 'active'
        ]);
        
        $product->delete();
        
        $response = $this->postJson("/api/products/{$product->id}/restore");
        
        $response->assertStatus(200);
        $this->assertNotSoftDeleted('products', ['id' => $product->id]);
    }
}