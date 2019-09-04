<?php
/**
 *  CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace Tests\Feature;

use CodersStudio\Cart\Models\PaymentMethod;
use CodersStudio\Cart\Models\Purchase;
use CodersStudio\Cart\Models\PurchasedProduct;
use CodersStudio\Cart\Models\Product;
use App\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function testCheckoutSuccess()
    {
        $purchasedProductCount = PurchasedProduct::count();
        $purchaseCount = Purchase::count();
        $user = factory(User::class)->create();
        $product = Product::create([
            'title' => 'ProductTest',
            'user_id' => $user->id,
            'sales_count' => 0,
            'category_id' => 1,
            'price' => 777,
        ]);
        $session = [
            'cart' => collect([
                "{$product->id}" => collect([
                    'id' => $product->id,
                    'price' => 555,
                    "params" => [
                        'extraFields' => []
                    ],
                ])
            ])
        ];

        $response = $this->actingAs($user)->withSession($session)->post('/checkout', [
            'status' => true,
            'payment_method_id' => 1
        ]);
        $this->assertCount($purchasedProductCount + 1, PurchasedProduct::get());
        $this->assertCount($purchaseCount + 1, Purchase::get());
    }

    public function testCheckoutFail()
    {
        $user = factory(User::class)->create();
        $product = Product::create([
            'title' => 'ProductTest',
            'user_id' => $user->id,
            'sales_count' => 0,
            'category_id' => 1,
            'price' => 777,
        ]);
        $session = [
            'cart' => collect([
                "{$product->id}" => collect([
                    'id' => $product->id,
                    'price' => 555,
                    "params" => [
                        'extraFields' => []
                    ],
                ])
            ])
        ];

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
        $user = factory(User::class)->create();
        $product = Product::create([
            'title' => 'ProductTest',
            'user_id' => $user->id,
            'sales_count' => 0,
            'category_id' => 1,
            'price' => 777,
        ]);
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'status_id' => 1,
            'price' => 111,
            'payment_method_id' => 1
        ]);

        $url = route('checkout.success', [
            'purchase_id' => $purchase->id,
            'payment_method_id' => 1,
        ]);

        $response = $this->actingAs($user)->get($url);
        $purchase = $purchase->fresh();
        $response->assertStatus(200);
        $this->assertEquals(2, $purchase->status_id);
    }

    public function testFailHandler()
    {
        //seeding
        $user = factory(User::class)->create();
        $product = Product::create([
            'title' => 'ProductTest',
            'user_id' => $user->id,
            'sales_count' => 0,
            'category_id' => 1,
            'price' => 777,
        ]);
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'status_id' => 1,
            'price' => 111,
            'payment_method_id' => 1
        ]);

        $url = route('checkout.fail', [
            'purchase_id' => $purchase->id,
            'payment_method_id' => 1,
        ]);

        $response = $this->actingAs($user)->get($url);
        $purchase = $purchase->fresh();
        $response->assertStatus(200);
        $this->assertEquals(3, $purchase->status_id);
    }
}
