<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Services\RawgApiService;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This is a costly action since it will execute a lot of http requests to RAWG API
     */
    public function run(): void
    {
        $apiService = new RawgApiService();

        $data = $apiService->getTags();
        $next = $data['next'];
        $tags = $data['results'];

        while (!is_null($next)) {
            $createData = [];

            foreach ($tags as $tag) {
                $createData[] = [
                    'name' => $tag['name'],
                    'slug' => $tag['slug'],
                ];
            }
            Tag::query()->upsert($createData, ['name'], ['name']);

            $data = $apiService->getNextPage($next);
            $next = $data['next'];
            $tags = $data['results'];
        }
    }
}
