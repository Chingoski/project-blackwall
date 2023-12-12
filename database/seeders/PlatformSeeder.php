<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Platform::query()
            ->firstOrCreate(['name' => 'PlayStation 4'], ['slug' => 'PS4']);

        Platform::query()
            ->firstOrCreate(['name' => 'PlayStation 5'], ['slug' => 'PS5']);
    }
}
