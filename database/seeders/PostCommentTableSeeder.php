<?php

use App\Models\PostComment;
use Illuminate\Database\Seeder;

class PostCommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(PostComment::class, 2)->create();
    }
}
