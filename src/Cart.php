<?php
/**
 * CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart;


use Illuminate\Support\Collection;

class Cart
{
    /**
     * Add product to Cart
     * @param int $id
     * @param string $name
     * @param float $price
     * @param int $quantity
     * @param array $params
     * @return void
     */
    public function add(int $id, string $name, float $price = 0, int $quantity = 1, array $params = []):void
    {
        $productCollection = collect([
            "id" => $id,
            "name" => $name,
            "price" => $price,
            "quantity" => $quantity,
            "params" => $params
        ]);
        $cartCollection = session("cart", collect([]));
        if ($cartCollection->has($id)){
            $cartCollection = $cartCollection->map(function ($item, $key) use ($id, $quantity) {
                $result = $item;
                if($key == $id){
                    $result = $item->map(function($value, $valueKey) use ($quantity) {
                        $result = $value;
                        if($valueKey == "quantity") {
                            $result += $quantity;
                        }
                        return $result;
                    });
                }
                return $result;
            });
        }
        else
            $cartCollection->put($id, $productCollection);

        session(['cart' => $cartCollection]);
    }

    /**
     * Get list of items
     * @return Collection
     */
    public function all():Collection
    {
        return session("cart", collect([]));
    }

    /**
     * Remove item from cart
     * @param int $id item id
     */
    public function remove(int $id):void
    {
        $cartCollection = session("cart", collect([]));
        $cartCollectionResult = $cartCollection->forget($id);
        session(['cart' => $cartCollectionResult]);
    }
}
