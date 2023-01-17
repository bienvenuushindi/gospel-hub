<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Teaching;
use App\Models\Users;
use Faker\Generator as Faker;

$factory->define(Teaching::class, function (Faker $faker) {
    return [
        //
        'theme' => $faker->words(7, true),
        'user_id' => factory(Users::class)->create()
    ];
});
