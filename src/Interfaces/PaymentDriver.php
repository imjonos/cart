<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 *
 */

namespace CodersStudio\Cart\Interfaces;


interface PaymentDriver
{
    public function redirect($purchase_id);
    public function success();
    public function fail();
}
