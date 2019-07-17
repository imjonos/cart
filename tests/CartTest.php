<?php
/**
 * CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace  CodersStudio\Notifications\Tests;

use CodersStudio\Cart\Models\Item;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\User;

class CartTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    /**
     * Test index action
     *
     * @return void
     */
    public function testIndex():void
    {
        factory(Item::class, 10)->create();
        $this->addProduct(1);
        $this->addProduct(2);
        $this->addProduct(3);
        $this->addProduct(1);
        $response = $this->get('/cart');
        $response->assertStatus(200);
        $response->assertJson([
            "data" => [
                [
                    "type"=> "items",
                    "attributes"=> [
                        "image_path" => "/test"
                    ]
                ]
            ]
        ]);
    }

    /**
     * Test add to cart
     *
     * @return void
     */
    public function testStore():void
    {
        factory(Item::class, 10)->create();
        $response = $this->addProduct();
        $response->assertStatus(204);
    }

    /**
     * Test remove from cart
     *
     * @return void
     */
    public function testDestroy()
    {
        factory(Item::class, 10)->create();
        $this->addProduct(1);
        $response = $this->json('delete', '/cart/1');
        $response->assertStatus(204);
        $response = $this->get('/cart');
        $response->assertStatus(200);
        $response->assertJson([
            "data" => []
        ]);
    }


    /**
     * Test remove all items from cart
     *
     * @return void
     */
    public function testClear()
    {
        factory(Item::class, 10)->create();
        $this->addProduct(1);
        $this->addProduct(2);
        $this->addProduct(3);
        $response = $this->json('delete', '/cart');
        $response->assertStatus(204);
        $response = $this->get('/cart');
        $response->assertStatus(200);
        $response->assertJson([
            "data" => []
        ]);
    }

    /**
     * Add test product to cart
     * @param int $id
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function addProduct(int $id = 1)
    {
       return $this->json('post','/cart',
            [
                'item_id' => $id,
                'quantity' => 2,
            ]);
    }

    /**
     * Login with fake user
     *
     * @return void
     */
    public function loginWithFakeUser()
    {
        $user = new User();
        $user->id = 1;
        $this->be($user);
    }


}
