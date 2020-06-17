<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use Faker\Generator as Faker;
use App\Traits\AppTrait;

$factory->define(Image::class, function (Faker $faker) {
    return [
        'image_path' => store_random_image(),
        'title' => $faker->sentences(5, true)
    ];
});
