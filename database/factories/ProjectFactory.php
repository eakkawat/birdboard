<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'title'       => $faker->sentence(4),
        'description' => $faker->sentence(2),
        'notes'       => $faker->sentence(1),
        'owner_id'    => function () {
            return factory('App\User')->create()->id;
        },
    ];
});
