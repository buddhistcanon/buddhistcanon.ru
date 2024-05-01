<?php

namespace App\Actions\Search;

use App\Data\Search\SearchRequestData;
use App\Data\Search\SearchResultsData;
use App\Data\Search\SuttaResultData;
use App\Data\Search\TextResultData;
use App\Models\Content;
use App\Models\Sutta;
use App\Services\Search\MeilisearchService;

class SearchAction
{
    private MeilisearchService $service;

    public function __construct(MeilisearchService $service)
    {
        $this->service = $service;
    }

    /**
     * Hit:
     * array:6 [▼
    "id" => 11072
    "chunkable_type" => "sutta"
    "chunkable_id" => 120
    "text" => """
    Семь видов благородных учеников
    Вера и постепенность пути
    """
    "mark" => "Bbm4d"
    "_formatted" => array:5 [▶
    "id" => "11072"
    "chunkable_type" => "sutta"
    "chunkable_id" => "120"
    "text" => """
    Семь видов <em>благородных</em> учеников
    Вера и постепенность пути
    """
    "mark" => "Bbm4d"
    ]
    ]
     */
    public function execute(SearchRequestData $searchRequestData): SearchResultsData
    {
        $hits = collect($this->service->searchInSuttas($searchRequestData->q));
        $suttaIds = $hits->map(fn ($item) => $item['chunkable_id'])->unique();
        $contentIds = $hits->map(fn ($item) => $item['content_id'])->unique();
        $suttas = Sutta::query()
            ->whereIn('id', $suttaIds)
            ->get();
        $contents = Content::query()
            ->whereIn('id', $contentIds)
            ->with('translator')
            ->get();
        $groupedBySutta = $hits->groupBy('chunkable_id');

        $suttaResults = collect();
        foreach ($suttas as $i => $sutta) {
            $hits = $groupedBySutta[$sutta->id];
            $textResults = collect();
            foreach ($hits as $hit) {
                /** @var Content */
                $currentContent = $contents->filter(fn ($item) => $item->id === $hit['content_id'])->first();
                if (! $currentContent) {
                    continue;
                }
                $textResultData = new TextResultData(
                    contentChunkId: $hit['id'],
                    html: $hit['_formatted']['text'],
                    url: $sutta->displaySlug().$currentContent->displaySlug().'#'.$hit['mark'],
                    fullUrl: config('app.url').$sutta->displaySlug().$currentContent->displaySlug(),
                    translation: $currentContent->displayTranslatorName()
                );
                $textResults->push($textResultData);
            }
            if ($textResults->count() == 0) {
                continue;
            }
            $suttaResultData = new SuttaResultData(
                name: $sutta->displayName(),
                textResults: TextResultData::collection($textResults)
            );
            $suttaResults->push($suttaResultData);
        }

        $result = new SearchResultsData(SuttaResultData::collection($suttaResults));

        return $result;
    }
}
