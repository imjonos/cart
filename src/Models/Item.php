<?php
/**
 * CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use CodersStudio\Cart\Interfaces\Item AS ItemInterface;

/**
 * Class Item
 * This is Test class for Cart
 * @package CodersStudio\Cart\Models
 */
class Item extends Model implements ItemInterface
{
    protected $guarded = ['id'];

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getParams(): array
    {
        return [
            "image_path" => $this->image_path
        ];
    }
}
