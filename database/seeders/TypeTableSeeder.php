<?php

use App\Models\Type;
use App\Models\Users;
use Illuminate\Database\Seeder;

class TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $types = factory(Type::class, 4)->create();
        $types->each(function ($type) {
            factory(Users::class, 2)->create([
                'type_id' => $type->id
            ]);
        });
    }
}
