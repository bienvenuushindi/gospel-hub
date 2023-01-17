<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use App\Models\PostLike;
use App\Models\Users;
use Faker\Generator as Faker;

$factory->define(PostLike::class, function (Faker $faker) {
    return [
        //

        'user_id' => factory(Users::class)->create(),
        'post_id' => factory(Post::class)->create(),
        'is_liked' => $faker->boolean
    ];
});
