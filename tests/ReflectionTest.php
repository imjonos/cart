<?php

namespace Tests\Feature;

use CodersStudio\Cart\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReflectionTest extends TestCase
{
    use RefreshDatabase;


    public function testCastableTrait()
    {
        $this->seed(\UsersTableSeeder::class);
        $product = Product::create([
            'title' => 'ProductTest',
            'user_id' => 1,
            'sales_count' => 0,
            'category_id' => 1,
            'price' => 777,
        ]);
        dd($product->castModel());
    }

}
