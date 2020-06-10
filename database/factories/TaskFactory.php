<?php

use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence(4),
        'completed' => false,
        'project_id' => function(){
            return factory('App\Project')->create()->id;
        }

    ];
});
