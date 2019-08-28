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

class PurchasedProduct extends Product
{
    protected $fillable = [
        'title',
        'usage',
        'description',
        'city',
        'location',
        'camera_program',
        'be_first',
        'extra',
        'comment',
        'on_sale',
        'approved',
        'country',
        'category',
        'sales_count',
        'be_first_active_at',
        'price',
        'actors',
        'resolution',

        'purchase_id',
        'product_id',
        'file_id',
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

    /**
     * Get purchased product file
     */
    public function file()
    {
        return $this->product->productable->files()->where('id', $this->file_id);
    }

    /**
     * Scope to get filtered chart data
     *
     * @param $query
     * @param $range
     * @param null $category
     * @return mixed
     */
    public function scopeChartData($query, $range, $category_id = null)
    {
        $query = $query->select([
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as sales')
        ])->whereHas('product.user', function ($query) {
            $query->where('id', auth()->user()->id);
        })->whereHas('purchase', function($query) {
            $query->where('status_id', 2);
        })->whereBetween('created_at', $range)->groupBy('date')->orderBy('date');

        if ($category_id) {
            $query = $query->whereHas('product', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            });
        }

        return $query;
    }

    public function scopeFilterByDateRangeAndCategory($query, $range, $category_id = null)
    {
        $query = $query->select([
            'title',
            'created_at',
            'price',
            'usage',
            'product_id'
        ])->whereHas('product.user', function ($query) {
            $query->where('id', auth()->user()->id);
        })->whereHas('purchase', function($query) {
            $query->where('status_id', 2);
        })->whereBetween('created_at', $range)->with('product.user');

        if ($category_id) {
            $query = $query->whereHas('product', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            });
        }

        return $query;
    }

}
