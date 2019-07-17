<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 */

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use CodersStudio\Cart\Models\Item;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price' => 10,
        'image_path' => "/test"
    ];
});
