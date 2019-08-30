<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 *
 */

namespace CodersStudio\Cart\Traits;

/**
 * Trait Castable
 * @package CodersStudio\Cart\Traits
 */
trait Castable
{
    /**
     * Cast fillable product attributes to fields, which can be used to mass create/update on casted product model
     * Relation, which listed in $castableRelations will be casted by given field
     * For example: category_id can be casted to category name
     *
     * @return \Illuminate\Support\Collection
     * @throws \ReflectionException
     */
    public function castModel()
    {
        // sanitize fields. only fillables accepted
        $fields = collect($this->toArray());
        $fillable = collect($this->getFillable())->merge(['created_at', 'updated_at']);
        $fields = $fields->filter(function($item, $index) use ($fillable) {
            return $fillable->search($index) !== false;
        });

        $reflector = new \ReflectionClass(self::class);
        $castableRelations = collect($this->castableRelations);


        // cast relations
        foreach ($reflector->getMethods() as $reflectionMethod) {

            $founded = $castableRelations->search(function ($relation) use ($reflectionMethod) {
                return $relation['method'] === $reflectionMethod->name;
            });

            if($founded !== false) {
                $foreign = $this->{$reflectionMethod->name}()->getForeignKeyName();
                $castedForeign = preg_replace('/(\w+)_id$/u', '${1}', $foreign);
                $fields->forget($foreign);
                $fields->put($castedForeign, $this->{$reflectionMethod->name}->{$castableRelations[$founded]['field']});
            }
        }

        return $fields;
    }
}
