<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('room_types')->insert(
            [
                'name' => "Single",
                'beds' => 1,
                'spaces' => 1,
                'max_people' => 1
            ]
        );
        DB::table('room_types')->insert(
            [
                'name' => "Double",
                'beds' => 2,
                'spaces' => 1,
                'max_people' => 2
            ]
        );
        DB::table('room_types')->insert(
            [
                'name' => "Suite",
                'beds' => 4,
                'spaces' => 2,
                'max_people' => 4
            ]
        );
    }
}
