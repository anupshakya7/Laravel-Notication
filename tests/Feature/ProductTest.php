<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->admin = $this->createUser(1);
    }

    public function test_productpage_contains_empty_table(): void
    {        
        $response = $this->actingAs($this->user)->get('/product');

        $response->assertStatus(200);
        $response->assertSee('No Record Found');
    }

    public function test_productpage_contains_non_empty_table(): void
    {
        $product = Product::create([
            'name'=>'Product 1',
            'price'=>'1000',
            'status'=>1,
        ]);
        
        $response = $this->actingAs($this->user)->get('/product');

        $response->assertStatus(200);
        $response->assertDontSee('No Record Found');
        $response->assertSee('Product 1');
        $response->assertViewHas('products',function($collection) use($product){
            return $collection->contains($product);
        });
    }

    public function test_paginated_products_table_doesnt_contain_11th_record(){
        // for($i=1;$i<=11;$i++){
        //     $product = Product::create([
        //         'name'=>'Product '.$i,
        //         'price'=>$i*1000,
        //         'status'=>1
        //     ]);
        // }

        $products = Product::factory(11)->create();
        $lastProduct = $products->last();
     
        $response = $this->actingAs($this->user)->get('/product');

        $response->assertStatus(200);
        $response->assertViewHas('products',function($collection) use($lastProduct){
            return !$collection->contains($lastProduct);
        });
    }

    public function test_admin_can_see_products_create_button(){
        $response = $this->actingAs($this->admin)->get('product');

        $response->assertStatus(200);
        $response->assertSee('Add Product');
    }

    public function test_non_admin_cannot_see_products_create_button(){
        $response = $this->actingAs($this->user)->get('product');

        $response->assertStatus(200);
        $response->assertDontSee('Add Product');
    }

    public function test_admin_can_access_product_create_page(){
        $response = $this->actingAs($this->admin)->get('/product/create');

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_product_create_page(){
        $response = $this->actingAs($this->user)->get('/product/create');

        $response->assertStatus(403);
    }

    private function createUser($isAdmin = 0){
        return User::factory()->create([
            'is_admin'=>$isAdmin
        ]);
    }
}
