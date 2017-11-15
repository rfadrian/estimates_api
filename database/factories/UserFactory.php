<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'address' => $faker->address,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
    ];
});

$factory->define(App\Models\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(2),
    ];
});


$factory->define(App\Models\Estimate::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(6),
        'description' => $faker->paragraph,
        'user_id' => function () {
            return factory('App\Models\User')->create()->id;
        },
        'category_id' => function() {
            return factory('App\Models\Category')->create()->id;
        },
//        'state_id' => $faker->randomElement($array = [1,2,3]),
        'state_id' => 1
    ];
});
