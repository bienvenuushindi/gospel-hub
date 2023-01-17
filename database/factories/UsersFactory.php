<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Users;
use Faker\Generator as Faker;

//use Illuminate\Support\Str;

$factory->define(Users::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'blocked' => $faker->boolean,
        'type_id' => 1,
        'country' => $faker->country,
        'image' => $faker->imageUrl(),
        'ministry_church' => $faker->word,
        'ministry_type' => 'Eglise',
        'phone' => $faker->unique()->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'contact_visibility' => $faker->boolean(true),
        'email_verified_at' => now(),
        'password' => bcrypt('password'), // password
        'remember_token' => Str::random(10),
    ];
});
