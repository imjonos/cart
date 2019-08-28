<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 *
 */

namespace CodersStudio\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Product
 * @package App
 */
class Product extends Model
{
    protected $fillable = [
        'title',
        'user_id',
        'productable_type',
        'productable_id',
        'usage_id',
        'description',
        'city',
        'location',
        'camera_program',
        'be_first',
        'extra',
        'comment',
        'on_sale',
        'approved',
        'country_id',
        'category_id',
        'sales_count',
        'be_first_active_at',
        'price',
        'actors',
    ];
}
