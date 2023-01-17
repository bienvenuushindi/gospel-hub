<?php

use App\Models\PostView;
use Illuminate\Database\Seeder;

class PostViewTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(PostView::class, 5)->create();
    }
}
