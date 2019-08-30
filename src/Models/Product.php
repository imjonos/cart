<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 *
 */

namespace CodersStudio\Cart\Models;

use App\User;
use CodersStudio\Cart\Traits\Castable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Product
 * @package App
 */
class Product extends Model
{
    use Castable;

    protected $castableRelations = [
        [
            'method' => 'category',
            'field' => 'name'
        ],
    ];

    protected $fillable = [
        'title',
        'user_id',
        'category_id',
        'sales_count',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
