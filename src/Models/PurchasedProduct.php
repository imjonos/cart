<?php
/**
 *  CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use CodersStudio\Cart\Models\{
    Product,
    Purchase
};
use Illuminate\Database\Eloquent\Model;

/**
 * Test example
 * Class PurchasedProduct
 * @package CodersStudio\Cart\Models
 */
class PurchasedProduct extends Model
{
    protected $fillable = [
        // auto copy from Product model
        'title',
        'user_id',
        'sales_count',
        'price',

        // casted from category_id
        'category',

        // should not be modified
        'purchase_id',
        'product_id',
    ];

    /**
     * The purchase which with product cast is associated
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Product by which prototype cast has been made
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
