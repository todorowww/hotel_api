<?php

namespace Database\Seeders;

use Faker\Factory;
use Faker\Guesser\Name;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $roomCounter = 0;
        for ($i = 1; $i <= 20; $i++) {
            $roomCounter++;
            DB::table('rooms')->insert(
                [
                    'name' => 'Single room',
                    'room_number' => $this->getRoomNumber($roomCounter),
                    'type_id' => 1,
                    'price' => 39.99
                ]
            );
        }
        for ($i = 1; $i <= 20; $i++) {
            $roomCounter++;
            DB::table('rooms')->insert(
                [
                    'name' => 'Double room',
                    'room_number' => $this->getRoomNumber($roomCounter),
                    'type_id' => 2,
                    'price' => 59.99
                ]
            );
        }
        for ($i = 1; $i <= 10; $i++) {
            $roomCounter++;
            DB::table('rooms')->insert(
                [
                    'name' => $faker->firstNameFemale() . ' Suite',
                    'room_number' => $this->getRoomNumber($roomCounter),
                    'type_id' => 3,
                    'price' => 89.99
                ]
            );
        }
    }

    private function getRoomNumber($roomId) {
        $roomsPerFloor = 10;
        $floor = ceil($roomId / $roomsPerFloor);
        return ($floor * 100) + ($roomId - (($floor - 1) * $roomsPerFloor));
    }
}
