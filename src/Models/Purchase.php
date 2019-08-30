<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 *
 */

namespace CodersStudio\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use CodersStudio\Cart\Models\Product;
use CodersStudio\CRUD\Traits\Crudable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Purchase
 * @package CodersStudio\Cart\Models
 */
class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'price',
        'status_id',
        'payment_method_id',
    ];

    /**
     * Get purchased products associated with purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchasedProducts()
    {
        return $this->hasMany(config('cart.purchased_product_model'));
    }

    /**
     * Get the user that owns the Purchase.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Payment status of the purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(PurchaseStatus::class);
    }

}
