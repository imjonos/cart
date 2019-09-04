<?php
/**
 *  CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentMethod
 * @package CodersStudio\Cart\Models
 */
class PaymentMethod extends Model
{
    public $fillable = ['name'];
}
