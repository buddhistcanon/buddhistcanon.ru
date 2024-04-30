<?php

namespace App\Http\Controllers;

use App\Actions\Search\SearchAction;
use App\Data\Search\SearchRequestData;
use App\Services\Search\MeilisearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string|min:3',
        ], [
            'search.min' => 'Минимальный размер поискового запроса - 3 символа.',
        ]);

        $search = $request->input('search');
        $result = null;
        $meilisearchService = new MeilisearchService(config('scout.meilisearch.host'), config('scout.meilisearch.key'));

        if ($search) {

            $meilisearchService->createIndexIfNeeded();
            $meilisearchService->createFilterIfNeeded();

            $searchRequestData = new SearchRequestData($search);
            $action = new SearchAction($meilisearchService);
            $searchResultsData = $action->execute($searchRequestData);

            $result = $searchResultsData->suttasResult;
        }

        return inertia('Search/SearchPage', [
            'search' => $search,
            'result' => $result,
            'numDocumentsInIndex' => $meilisearchService->numDocumentsInIndex(),
            'isIndexing' => $meilisearchService->isIndexing(),
        ]);
    }

    public function status()
    {
        $meilisearchService = new MeilisearchService(config('scout.meilisearch.host'), config('scout.meilisearch.key'));
        dd($meilisearchService->stats());

        return inertia('Search/SearchStatus', [
            'search' => $search,
            'result' => $result,
            'numDocumentsInIndex' => $meilisearchService->numDocumentsInIndex(),
            'isIndexing' => $meilisearchService->isIndexing(),
        ]);
    }
}
