<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use App\Models\PostView;
use App\Models\Users;
use Faker\Generator as Faker;

$factory->define(PostView::class, function (Faker $faker) {
    return [
        //
        'user_id' => factory(Users::class)->create(),
        'post_id' => factory(Post::class)->create(),
        'visited-times' => $faker->numberBetween(0, 3)
    ];
});
