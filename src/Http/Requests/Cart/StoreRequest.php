<?php
/**
 *  CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart\Http\Requests\Cart;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 * @package CodersStudio\Cart
 */
class StoreRequest extends FormRequest
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
            'item_id' => 'required|integer',
            'quantity' => 'integer'
        ];
    }
}
