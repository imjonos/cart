<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
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
        Item::truncate();
        factory(Item::class, 10)->create();
        $this->addProduct(1);
        $this->addProduct(2);
        $this->addProduct(3);
        $this->addProduct(1);
        $response = $this->get('/cart');
        $response->assertStatus(200);
        $response->assertJson([
            "meta" => [
                "count" => 3,
                "total" => 30
            ],
            "data" => [
                [
                    "type"=> "items",
                    "id" => 1,
                    "attributes"=> [
                        "params" => [
                            "image_path" => "/test"
                        ]
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
        Item::truncate();
        factory(Item::class, 10)->create();
        $response = $this->addProduct(1);
        $response->assertStatus(204);
    }

    public function testUpdate():void
    {
        Item::truncate();
        factory(Item::class)->create();
        $response = $this->addProduct(1);
        $response->assertStatus(204);

        $response = $this->put('/cart', [
            'item_id' => 1,
            'quantity' => 225,
            'name' => 'new name',
            'price' => 123,
            'params' => [
                'testParam' => 777
            ]
        ]);
        $response->assertStatus(204);

        $response = $this->get('/cart');
        $response->assertJson([
            'data' => [
                [
                    'id' => 1,
                    'attributes' => [
                        'quantity' => 225,
                        'name' => 'new name',
                        'price' => 1223,
                        'params' => [
                            'testParam' => 777
                        ]
                    ]
                ]
            ]
        ]);
    }

    /**
     * Test remove from cart
     *
     * @return void
     */
    public function testDestroy()
    {
        Item::truncate();
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
        Item::truncate();
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
