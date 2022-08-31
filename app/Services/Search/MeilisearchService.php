<?php

namespace App\Services\Search;

use MeiliSearch\Client;

class MeilisearchService
{
    private Client $client;

    public function __construct($meiliHost, $meiliKey)
    {
        $this->client = new Client($meiliHost, $meiliKey);
    }

    public function searchInSuttas(string $searchTerm, int $limit = 100): array
    {
        $meiliResults = $this->client->index("content_chunks")->search($searchTerm, [
            'filter'=>"chunkable_type = sutta",
            'attributesToHighlight' => ['text'],
            'limit' => $limit
        ]);
        return $meiliResults->getHits();
    }

    public function searchInBooks(string $searchTerm, int $limit = 100): array
    {
        $meiliResults = $this->client->index("content_chunks")->search($searchTerm, [
            'filter'=>"chunkable_type = book",
            'attributesToHighlight' => ['text'],
            'limit' => $limit
        ]);
        return $meiliResults->getHits();
    }

    public function createIndexIfNeeded($indexUid = "content_chunks"): bool
    {
        try{
            $this->client->index($indexUid)->stats();
        }catch(\MeiliSearch\Exceptions\ApiException $exception){
            $this->client->createIndex($indexUid, ['primaryKey' => 'id']);
            return true;
        }
        return false;
    }

    public function createFilterIfNeeded($indexUid = "content_chunks", $filter = "chunkable_type"): bool
    {
        if (!in_array($filter, $this->client->index($indexUid)->getFilterableAttributes())) {
            $this->client->index($indexUid)->updateFilterableAttributes([$filter]);
            return true;
        }
        return false;
    }

    public function isIndexing($indexUid = "content_chunks"): bool
    {
        return $this->client->index($indexUid)->stats()['isIndexing'];
    }

    public function numDocumentsInIndex($indexUid = "content_chunks"): int
    {
        return $this->client->index($indexUid)->stats()['numberOfDocuments'];
    }

    public function stats()
    {
        return $this->client->stats();
    }
}
