<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class RawgApiService
{
    protected function generateApiUrl(string $uri, int $pageSize, ?int $pageNumber = null): string
    {
        $host = config('rawg.host');
        $apiKey = config('rawg.api_key');

        $url = "{$host}/{$uri}?key={$apiKey}&page_size={$pageSize}";

        if ($pageNumber) {
            $url .= "&page={$pageNumber}";
        }

        return $url;
    }

    public function getGenres()
    {
        $url = $this->generateApiUrl(config('rawg.routes.genres'), 100);

        $response = Http::get($url);

        if (!$response->successful()) {
            throw new UnprocessableEntityHttpException('Fetching genres failed.');
        }

        return $response->json();
    }

    public function getTags(?int $pageNumber = null)
    {
        $url = $this->generateApiUrl(config('rawg.routes.tags'), 100, $pageNumber);

        $response = Http::get($url);

        if (!$response->successful()) {
            throw new UnprocessableEntityHttpException('Fetching genres failed.');
        }

        return $response->json();
    }

    protected function setGamesFilters(string $url, array $filters): string
    {
        foreach ($filters as $filter => $value) {
            $url .= "&{$filter}={$value}";
        }

        return $url;
    }

    public function getGames(?int $pageNumber = null)
    {
        $filters = config('rawg.games_filters');
        $pageSize = $filters['page_size'];
        unset($filters['page_size']);

        $url = $this->generateApiUrl(config('rawg.routes.games'), $pageSize, $pageNumber);
        $url = $this->setGamesFilters($url, $filters);

        $response = Http::get($url);

        if (!$response->successful()) {
            throw new UnprocessableEntityHttpException('Fetching genres failed.');
        }

        return $response->json();
    }
}
