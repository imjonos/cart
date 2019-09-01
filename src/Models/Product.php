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
 * Test example model
 * Class Product
 * @package App
 */
class Product extends Model
{
    // trait which should be used to use $this->castModel() method
    use Castable;

    protected $castableRelations = [
        [
            'method' => 'category', // relationship method name
            'field' => 'name' // field of the related table. $this->category->name instead of category_id
        ],
    ];

    protected $fillable = [
        'title',
        'user_id',
        'category_id', // category_id will be casted to category
        'sales_count',
        'price',
    ];

    /**
     * Won't be casted cause of name of the method is not registered in $this->castableRelations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Will be casted if category_id exists in $this->fillable array
     * and name of the method registered in $this->castableRelations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
