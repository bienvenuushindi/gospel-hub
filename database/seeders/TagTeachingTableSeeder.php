<?php

use App\Models\TagTeaching;
use Illuminate\Database\Seeder;

class TagTeachingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(TagTeaching::class, 5)->create();
    }
}
