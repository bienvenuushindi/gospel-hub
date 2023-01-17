<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            TypeTableSeeder::class,
            PostLikeTableSeeder::class,
            PostTableSeeder::class,
            TeachingTableSeeder::class,
            TagTeachingTableSeeder::class,
            PostTagTableSeeder::class,
            PostTypeTableSeeder::class,
            PostViewTableSeeder::class,
            TagTableSeeder::class,
            PostCommentTableSeeder::class,
        ]);
    }
}
