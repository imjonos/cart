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

/**
 * Class PaypalPaymentDriver
 * @package CodersStudio\Cart\Drivers
 */
class PaypalPaymentDriver implements PaymentDriver
{
    public $paymentMethod;

    /**
     * PaypalPaymentDriver constructor.
     */
    public function __construct()
    {
        $this->paymentMethod = PaymentMethod::where('name', 'paypal')->get()->first();
    }

    /**
     * Redirect to payment gateway after successful DB initialization
     *
     * @param $purchase_id
     */
    public function redirect($purchase_id)
    {
        // redirect to payment system
    }

    /**
     * Success payment callback. Invoked by payment gateway after success payment
     *
     * @return mixed
     */
    public function success()
    {
        $request = request();
        $purchase = Purchase::findOrFail($request->get('purchase_id'));
        $purchase->status_id = 2;
        $purchase->save();

        return $purchase;
    }

    /**
     * Failed payment callback. Invoked by payment gateway after failed payment
     */
    public function fail()
    {
        $request = request();
        $purchase = Purchase::findOrFail($request->get('purchase_id'));
        $purchase->status_id = 3;
        $purchase->save();
    }
}
