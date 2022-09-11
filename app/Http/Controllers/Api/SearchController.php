<?php

namespace App\Http\Controllers\Api;

use App\Actions\Search\SearchAction;
use App\Data\Search\SearchRequestData;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchApiRequest;
use App\Services\Search\MeilisearchService;

class SearchController extends Controller
{
    public function __invoke(SearchApiRequest $request)
    {
        $meilisearchService = new MeilisearchService(config('scout.meilisearch.host'), config('scout.meilisearch.key'));
        $meilisearchService->createIndexIfNeeded();
        $meilisearchService->createFilterIfNeeded();

        $searchRequestData = new SearchRequestData($request->q);
        $action = new SearchAction($meilisearchService);
        $searchResultsData = $action->execute($searchRequestData);

        return $searchResultsData->suttasResult;
    }
}
