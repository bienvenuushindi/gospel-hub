<?php

use App\Models\PostType;
use Illuminate\Database\Seeder;

class PostTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(PostType::class, 4)->create();
    }
}
