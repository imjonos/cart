<?php
/**
 * CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use CodersStudio\Cart\Facades\Cart;
use CodersStudio\Cart\Http\Requests\Cart\{
    StoreRequest,
    UpdateRequest,
    IndexRequest,
    DestroyRequest,
    ClearRequest
};

use CodersStudio\Cart\Http\Resources\ItemsResource;

class CartController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Item model class
     */
    protected $model;

    /**
     * Return all items
     *
     * @return ItemsResource
     */
    public function index(IndexRequest $request)
    {
        return new ItemsResource(Cart::all());
    }

    /*
     * Add to cart
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $id = $request->get("item_id");
        $quantity = $request->get("quantity");
        $item = $this->getModel()::findOrFail($id);
        $name = $item->getName();
        $price = $item->getPrice();
        $params = $item->getParams();

        Cart::add($id, $name, $price, $quantity, $params);
        return response()->json([],204);
    }


    /*
     * Update product in the cart
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $id = $request->get("item_id");
        $quantity = $request->get("quantity");
        $item = $this->getModel()::findOrFail($id);
        $name = $request->get('name') ?? $item->getName();
        $price = $request->get('price') ?? $item->getPrice();
        $params = $item->getParams();

        if($request->has('params')) {
            $customParams = $request->get('params');
            $params = collect($params)->merge($customParams)->toArray();
        }

        Cart::update($id, $name, $price, $quantity, $params);
        return response()->json([],204);
    }

    /*
    * Remove from cart
    * @param DestroyRequest $request
    * @param int $item item id
    */
    public function destroy(DestroyRequest $request, int $item)
    {
        Cart::remove($item);
        return response()->json([],204);
    }

    /*
   * Remove all items from cart
   * @param DestroyRequest $request
   */
    public function clear(ClearRequest $request)
    {
        Cart::clear();
        return response()->json([],204);
    }

    /**
     * Get the model
     * @return Model
     */
    protected function getModel(){
        if(!$this->model){
            $this->model = config('cart.model');
        }
        return $this->model;
    }

}
