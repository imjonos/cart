<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 *
 */

namespace Tests\Feature;

use CodersStudio\Cart\Models\PaymentMethod;
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


    public function testCheckoutSuccess()
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
    }

    public function testCheckoutFail()
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
            'status' => false,
            'payment_method_id' => 1
        ]);
        $this->assertCount(1, PurchasedProduct::get());
        $this->assertCount(1, Purchase::get());
    }

    public function testSuccessHandler()
    {
        //seeding
        $this->seed(\UsersTableSeeder::class);
        $user = User::firstOrFail();
        $product = Product::create([
            'title' => 'ProductTest',
            'user_id' => 1,
            'sales_count' => 0,
            'category_id' => 1,
            'price' => 777,
        ]);
        Purchase::create([
            'user_id' => $user->id,
            'status_id' => 1,
            'price' => 111,
            'payment_method_id' => 1
        ]);


        $response = $this->actingAs($user)
            ->withMiddleware('web')
            ->withSession([])
            ->get('/checkout/success/1?purchase_id=1&payment_method_id=1');

        //dd(PaymentMethod::get());

        $response->dump();
        $response->assertStatus(200);

        $purchase = Purchase::first();
        $this->assertEquals(2, $purchase->status_id);
    }
}
