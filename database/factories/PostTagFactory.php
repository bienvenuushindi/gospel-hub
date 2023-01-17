<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use Faker\Generator as Faker;

$factory->define(PostTag::class, function (Faker $faker) {
    return [
        'tag_id' => factory(Tag::class)->create(),
        'post_id' => factory(Post::class)->create()
    ];
});
