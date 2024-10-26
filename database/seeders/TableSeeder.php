<?php

namespace Database\Seeders;

use App\Enum\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 1;
        $capacity = [2, 4, 6];

        foreach (Location::cases() as $locationCase) {
            $tables = random_int(3, 9);

            for ($i = 1; $i <= $tables; $i++) {
                DB::table('tables')->insert([
                    'id' => (string) Str::ulid(),
                    'location' => $locationCase->value,
                    'table_number' => $count,
                    'max_capacity' => $capacity[array_rand($capacity)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $count++;
            }
        }
    }
}
