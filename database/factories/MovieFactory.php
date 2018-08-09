<?php

use Faker\Generator as Faker;

$factory->define(App\Movie::class, function (Faker $faker) {
    return [
        'id' => 'tt' . $faker->regexify('[0-9]{7}'),
        'title' => $faker->sentence(4),
        'poster' => 'http://via.placeholder.com/75x100?text=NO+IMAGE',
        'type' => 'movie',
        'year' => $faker->year()
    ];
});
