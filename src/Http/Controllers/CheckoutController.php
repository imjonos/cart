<?php
/**
 *  CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart\Http\Controllers;

use CodersStudio\Cart\Http\Requests\Checkout\CheckoutRequest;
use CodersStudio\Cart\Interfaces\PaymentDriver;
use CodersStudio\Cart\Models\Purchase;
use CodersStudio\Cart\Facades\Cart;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

/**
 * Class CheckoutController
 * @package CodersStudio\Cart\Http\Controllers
 */
class CheckoutController extends Controller
{
    /**
     * Product class
     * @var
     */
    protected $productModel;

    /**
     * PurchasedProduct class
     * @var
     */
    protected $purchasedProductModel;

    /**
     * Success payment handler. Invoked by payment gateway
     *
     * @param Request $request
     * @param PaymentDriver $driver
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success(PaymentDriver $driver)
    {
        $purchase = $driver->success();
        return response(null, 200);
    }

    /**
     * Fail payment handler. Invoked by payment gateway
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fail(PaymentDriver $driver)
    {
        $driver->fail();
    }

    /**
     * Static success page render
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function successPage(Request $request)
    {
        $purchase = Purchase::find($request->get('purchase_id')) ?? null;
        return view('codersstudio.cart::success', [
            'purchase' => $purchase
        ]);
    }

    /**
     * Static fail page render
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function failPage()
    {
        return view('codersstudio.cart::fail');
    }

    /**
     * Prepare db and redirect to the payment service if not XHR
     *
     * @param CheckoutRequest $request
     * @param PaymentDriver $driver
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkout(CheckoutRequest $request, PaymentDriver $driver)
    {
        $cart = Cart::all();

        if ($cart->isNotEmpty()) {
            $purchase = null;

            // Init database
            DB::transaction(function () use (&$purchase, &$cart, &$driver) {
                $purchase = Purchase::create([
                    'user_id' => auth()->user()->id,
                    'price' => 0,
                    'status_id' => 1,
                    'payment_method_id' => $driver->paymentMethod->id
                ]);
                $totalPrice = 0;

                $cart->map(function($cartItem) use ($purchase, &$totalPrice) {
                    $params = $cartItem->get('params');
                    $product = $this->getProductModel()::findOrFail($cartItem['id']);

                    // cast product model
                    $fields = collect($product->castModel());

                    $this->getPurchasedProductModel()::create($fields->merge([
                        'product_id' => $product->id,
                        'purchase_id' => $purchase->id,
                    ])->merge($params['extraFields'])->toArray());
                    $totalPrice += $cartItem['price'];
                });

                $purchase->price = $totalPrice;
                $purchase->save();
            });

            // clear the cart before redirect to a payment gateway
            Cart::clear();

            // response forming
            if ($request->ajax()) {
                $status = $request->has('status') && $request->get('status');
                $jsonResponseData = [
                    'purchase_id' => $purchase->id,
                    'payment_method_id' => $driver->paymentMethod->id,
                ];
                return response()->json($jsonResponseData, $status ? 200 : 422);
            } else {
                return $driver->redirect($purchase->id);
            }
        } else {
            if($request->ajax()) {
                return response()->json(null, 422);
            } else {
                return redirect()->back();
            }
        }
    }

    /**
     * Get Product class name
     * @return mixed
     */
    protected function getProductModel()
    {
        if(!$this->productModel){
            $this->productModel = config('cart.product_model');
        }
        return $this->productModel;
    }

    /**
     * Get PurchasedProduct class name
     * @return mixed
     */
    protected function getPurchasedProductModel()
    {
        if(!$this->purchasedProductModel){
            $this->purchasedProductModel = config('cart.purchased_product_model');
        }
        return $this->purchasedProductModel;
    }

}
