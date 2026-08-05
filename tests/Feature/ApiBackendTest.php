<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiBackendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test public menu tree endpoint.
     */
    public function test_public_menu_returns_nested_structure(): void
    {
        $response = $this->getJson('/api/menu');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'label',
                    'url',
                    'children' => [
                        '*' => [
                            'id',
                            'label',
                            'column_group',
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Test categories public endpoints.
     */
    public function test_public_categories(): void
    {
        $response = $this->getJson('/api/categories');
        $response->assertStatus(200);

        $category = Category::first();
        $detail = $this->getJson("/api/categories/{$category->slug}");
        $detail->assertStatus(200)->assertJsonStructure(['id', 'name', 'slug', 'products']);
    }

    /**
     * Test products catalog and filtering.
     */
    public function test_products_catalog_and_filters(): void
    {
        $response = $this->getJson('/api/products?gender=women');
        $response->assertStatus(200)->assertJsonStructure(['data', 'current_page', 'total']);

        $responseDetail = $this->getJson('/api/products/l-ombre-d-ambre');
        $responseDetail->assertStatus(200)
            ->assertJsonStructure([
                'product' => ['id', 'name', 'scent_family', 'notes_top'],
                'related' => []
            ]);
    }

    /**
     * Test admin login endpoint.
     */
    public function test_admin_login(): void
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@rexxobd.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user']);
    }

    /**
     * Test place order endpoint.
     */
    public function test_place_order(): void
    {
        $product = Product::first();

        $response = $this->postJson('/api/orders', [
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '+8801700000000',
            'shipping_address' => '123 Luxury Ave, Dhaka',
            'city' => 'Dhaka',
            'postal_code' => '1205',
            'payment_method' => 'cod',
            'items' => [
                [
                    'product_id' => $product->id,
                    'size' => '100ml',
                    'quantity' => 1,
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'order' => ['id', 'order_number', 'total_amount', 'items']
            ]);
    }

    /**
     * Test admin protected endpoints (Menu Builder, Products CRUD, Orders view).
     */
    public function test_admin_protected_crud_routes(): void
    {
        $admin = User::where('email', 'admin@rexxobd.com')->first();
        $token = $admin->createToken('test')->plainTextToken;
        $headers = ['Authorization' => "Bearer {$token}"];

        // 1. Get Me
        $this->getJson('/api/admin/me', $headers)->assertStatus(200)->assertJson(['email' => 'admin@rexxobd.com']);

        // 2. Menu items CRUD + Reorder
        $newMenuItem = $this->postJson('/api/admin/menu-items', [
            'label' => 'New Collection',
            'url' => '/perfumes/new',
            'sort_order' => 10,
        ], $headers);
        $newMenuItem->assertStatus(201);
        $menuItemId = $newMenuItem->json('id');

        $this->patchJson('/api/admin/menu-items/reorder', [
            'items' => [['id' => $menuItemId, 'sort_order' => 1]]
        ], $headers)->assertStatus(200);

        $this->deleteJson("/api/admin/menu-items/{$menuItemId}", [], $headers)->assertStatus(200);

        // 3. Category CRUD
        $newCategory = $this->postJson('/api/admin/categories', [
            'name' => 'Limited Extraits',
            'description' => 'Rare scents',
        ], $headers)->assertStatus(201);

        $categoryId = $newCategory->json('id');
        $this->getJson("/api/admin/categories/{$categoryId}", $headers)->assertStatus(200);

        // 4. Product CRUD
        $newProduct = $this->postJson('/api/admin/products', [
            'name' => 'Royal Amber Oud',
            'concentration' => 'Parfum',
            'price' => 450.00,
            'gender' => 'unisex',
            'category_id' => $categoryId,
        ], $headers)->assertStatus(201);

        $productId = $newProduct->json('id');
        $this->getJson("/api/admin/products/{$productId}", $headers)->assertStatus(200);

        // 5. Orders admin endpoints
        $order = Order::first();
        if ($order) {
            $this->getJson('/api/admin/orders', $headers)->assertStatus(200);
            $this->getJson("/api/admin/orders/{$order->id}", $headers)->assertStatus(200);
            $this->patchJson("/api/admin/orders/{$order->id}/status", [
                'status' => 'processing'
            ], $headers)->assertStatus(200);
        }
    }

    /**
     * Test admin web login route and full page authentication.
     */
    public function test_admin_web_login_flow(): void
    {
        // 1. Unauthenticated /admin shows login view
        $guestResponse = $this->get('/admin');
        $guestResponse->assertStatus(200);
        $guestResponse->assertSee('REXXO BD ADMIN PORTAL');

        // 2. Submit credentials via web form POST to /admin/login
        $loginPost = $this->post('/admin/login', [
            'email' => 'admin@rexxobd.com',
            'password' => 'password123',
        ]);
        $loginPost->assertRedirect('/admin/dashboard');

        // 3. Follow redirect to verify full dashboard view
        $this->assertAuthenticated();
        $dashboardResponse = $this->get('/admin/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('EXECUTIVE DASHBOARD');
    }
}

