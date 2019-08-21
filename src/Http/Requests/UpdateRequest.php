<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 *
 */

namespace CodersStudio\Cart\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 * @package CodersStudio\Cart
 */
class UpdateRequest extends FormRequest
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
