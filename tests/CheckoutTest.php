<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 *
 */

namespace Tests\Feature;

use CodersStudio\Cart\Models\Purchase;
use CodersStudio\Cart\Models\PurchasedProduct;
use CodersStudio\Cart\Models\Product;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;


    public function testCheckout()
    {
        $session = [
            'cart' => collect([
                1 => collect([
                    'id' => 1,
                    'price' => 555,
                    "params" => [
                        'extraFields' => []
                    ],
                ])
            ])
        ];

        //seeding
        $this->seed(\UsersTableSeeder::class);
        $product = Product::create([
            'title' => 'ProductTest',
            'user_id' => 1,
            'sales_count' => 0,
            'category_id' => 1,
            'price' => 777,
        ]);

        $user = User::firstOrFail();
        $response = $this->actingAs($user)->withSession($session)->post('/checkout', [
            'status' => true,
            'payment_method_id' => 1
        ]);
        $this->assertCount(1, PurchasedProduct::get());
        $this->assertCount(1, Purchase::get());
        $this->assertEquals(1, Purchase::first()->status_id);


        dd($product->toArray(), ' to ' ,PurchasedProduct::get()->toArray());
    }
}
