<?php
/**
 *  CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart\Http\Requests\Cart;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IndexRequest
 * @package CodersStudio\Cart
 */
class IndexRequest extends FormRequest
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
