<?php

use App\Models\PostLike;
use Illuminate\Database\Seeder;

class PostLikeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(PostLike::class, 5)->create();
    }
}
