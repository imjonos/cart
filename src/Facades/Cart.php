<?php
/**
 * CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static add($id, $name, $price, $quantity, $params)
 */
class Cart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }
}
