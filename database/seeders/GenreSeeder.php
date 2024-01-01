<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Services\RawgApiService;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apiService = new RawgApiService();
        $data = $apiService->getGenres()['results'];
        $createData = [];

        foreach ($data as $genre) {
            $createData[] = [
                'name' => $genre['name'],
                'slug' => $genre['slug'],
            ];
        }

        Genre::query()->upsert($createData, ['name'], ['name']);
    }
}
