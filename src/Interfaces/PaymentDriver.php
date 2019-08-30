<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 *
 */

namespace CodersStudio\Cart\Interfaces;

/**
 * Interface PaymentDriver
 * @package CodersStudio\Cart\Interfaces
 */
interface PaymentDriver
{
    /**
     * Redirect to payment gateway after successful DB initialization
     *
     * @param $purchase_id
     * @return mixed
     */
    public function redirect($purchase_id);

    /**
     * Success payment callback. Invoked by payment gateway after success payment
     *
     * @return mixed
     */
    public function success();

    /**
     * Failed payment callback. Invoked by payment gateway after failed payment
     *
     * @return mixed
     */
    public function fail();
}
