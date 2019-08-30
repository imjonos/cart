<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 *
 */

namespace CodersStudio\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use CodersStudio\Cart\Models\Purchase;

/**
 * Class PurchaseStatus
 * @package CodersStudio\Cart\Models
 */
class PurchaseStatus extends Model
{
    public $fillable = ['name'];

    /**
     * Purchases associated with status
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
