<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 *
 */

namespace CodersStudio\Cart\Http\Requests\Cart;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DestroyRequest
 * @package CodersStudio\Cart
 */
class DestroyRequest extends FormRequest
{
    /**
     * authorize
     */
    public function authorize()
    {
        return true;
    }

    /**
    * rules
    */
    public function rules()
    {
        return [
        ];
    }
}
