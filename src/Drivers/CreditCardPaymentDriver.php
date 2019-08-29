<?php
/**
 *  CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart\Drivers;
use CodersStudio\Cart\Interfaces\PaymentDriver;
use CodersStudio\Cart\Models\PaymentMethod;
use CodersStudio\Cart\Models\Purchase;


class CreditCardPaymentDriver implements PaymentDriver
{
    public $paymentMethod;

    public function __construct()
    {
        $this->paymentMethod = PaymentMethod::where('name', 'card')->get()->first();
    }

    public function redirect($purchase_id)
    {
        $status = request()->has('status') && request()->get('status') === 'true';

        if($status) {
            file_get_contents(route('checkout.success', [
                'payment_method_id' => $this->paymentMethod->id,
                'purchase_id' => $purchase_id
            ]));
            return response()->redirectTo(route('payment.success', [
                'purchase_id' => $purchase_id
            ]));
        } else {
            file_get_contents(route('checkout.fail', [
                'payment_method_id' => $this->paymentMethod->id,
                'purchase_id' => $purchase_id
            ]));
            return response()->redirectTo(route('payment.fail'));
        }


    }
    public function success()
    {
        $request = request();
        $purchase = Purchase::findOrFail($request->get('purchase_id'));
        $purchase->status_id = 2;
        $purchase->save();

        $purchase->purchasedProducts->map(function($productCast) {

            $product = $productCast->product;
            $product->sales_count++;
            $product->sold_be_first = true;
            $product->save();

            $profile = $product->user->userSetting;
            $profile->balance += $productCast->price;
            $profile->save();
        });

        return $purchase;
    }
    public function fail()
    {
        $request = request();
        $purchase = Purchase::findOrFail($request->get('purchase_id'));
        $purchase->status_id = 3;
        $purchase->save();
    }
}
