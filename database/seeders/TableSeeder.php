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
        DB::table('tables')->insert([
            'id' => (string) Str::ulid(),
            'location' => Location::A,
            'table_number' => 1,
            'max_capacity' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
