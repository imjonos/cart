<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 *
 */

namespace CodersStudio\Cart\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use CodersStudio\Cart\Models\{
    Product,
    Purchase
};
use Illuminate\Database\Eloquent\Model;

class PurchasedProduct extends Model
{
    protected $fillable = [
        'title',
        'user_id',
        'category',
        'sales_count',
        'price',

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
