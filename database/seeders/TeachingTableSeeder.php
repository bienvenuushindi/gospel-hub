<?php

use App\Models\Teaching;
use Illuminate\Database\Seeder;

class TeachingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(Teaching::class, 10)->create();
    }
}
