<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tag;
use App\Models\TagTeaching;
use App\Models\Teaching;
use Faker\Generator as Faker;

$factory->define(TagTeaching::class, function (Faker $faker) {
    return [
        //
        'tag_id' => factory(Tag::class)->create(),
        'teaching_id' => factory(Teaching::class)->create()
    ];
});
