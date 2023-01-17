<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use App\Models\PostType;
use App\Models\Teaching;
use App\Models\Users;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        //
        'title'=>$faker->paragraph(1,true),
        'img' => $faker->imageUrl(10, 100),
        'published'=>$faker->boolean(true),
        'views'=>$faker->numberBetween(10,100),
        'description'=>$faker->paragraph(15),
        'short_description'=>$faker->paragraph(2,true),
        'teaching_id' => factory(Teaching::class)->create(),
        'user_id' => factory(Users::class)->create(),
        'post_type_id' => factory(PostType::class)->create()
    ];
});
