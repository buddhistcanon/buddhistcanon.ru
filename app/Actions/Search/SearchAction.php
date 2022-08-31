<?php

namespace App\Actions\Search;

use App\Data\Search\BookResultData;
use App\Data\Search\SearchRequestData;
use App\Data\Search\SearchResultsData;
use App\Data\Search\SuttaResultData;
use App\Data\Search\TextResultData;
use App\Models\Book;
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
     *
     */

    public function execute(SearchRequestData $searchRequestData) : SearchResultsData
    {
        $hits = collect($this->service->searchInSuttas($searchRequestData->q));
        $suttaIds = $hits->map(fn($item)=>$item['chunkable_id'])->unique();
        $contentIds = $hits->map(fn($item)=>$item['content_id'])->unique();
        $suttas = Sutta::query()
            ->whereIn("id", $suttaIds)
            ->get();
        $contents = Content::query()
            ->whereIn("id", $contentIds)
            ->with("translator")
            ->get();
        $groupedBySutta = $hits->groupBy("chunkable_id");

        $result = new SearchResultsData();
        $suttaResults = collect();
        foreach($suttas as $i=>$sutta){
            $hits = $groupedBySutta[$sutta->id];
            $textResults = collect();
            foreach($hits as $hit){
                /** @var Content */
                $currentContent = $contents->filter(fn($item)=>$item->id === $hit['content_id'])->first();
                $textResultData = new TextResultData(
                    contentChunkId: $hit['id'],
                    html: $hit['_formatted']['text'],
                    urlWithMark: $sutta->displaySlug().$currentContent->displaySlug()."#".$hit['mark'],
                    translatorName: $currentContent->displayTranslatorName()
                );
                $textResults->push($textResultData);
            }
            $suttaResultData = new SuttaResultData(
                suttaId: $sutta->id,
                name: $sutta->displayName(),
                paliTitle: null, enTitle: null, ruTitle: null,
                url: $sutta->displaySlug(),
                urlTheravadaRu: $contents->filter(fn($item)=> $item->contentable_id === $sutta->id AND $item->translator->slug === "sv")->first()->link_url,
                textResults: TextResultData::collection($textResults)
            );
            $suttaResults->push($suttaResultData);
        }


        $hits = collect($this->service->searchInBooks($searchRequestData->q));
        $bookIds = $hits->map(fn($item)=>$item['chunkable_id'])->unique();
        $contentIds = $hits->map(fn($item)=>$item['content_id'])->unique();
        $books = Book::query()
            ->whereIn("id", $bookIds)
            ->with("author")
            ->get();
        $contents = Content::query()
            ->whereIn("id", $contentIds)
            ->with("translator")
            ->get();
        $groupedByBook = $hits->groupBy("chunkable_id");

        $bookResults = collect();
        foreach($books as $i=>$book){
            $hits = $groupedByBook[$book->id];
            $textResults = collect();
            foreach($hits as $hit){
                /** @var Content */
                $currentContent = $contents->filter(fn($item)=>$item->id === $hit['content_id'])->first();
                $textResultData = new TextResultData(
                    contentChunkId: $hit['id'],
                    html: $hit['_formatted']['text'],
                    urlWithMark: "/".$book->slug.$currentContent->displaySlug()."#".$hit['mark'],
                    translatorName: $currentContent->displayTranslatorName()
                );
                $textResults->push($textResultData);
            }
            $bookResultData = new BookResultData(
                bookId: $book->id,
                author: $book->author->displayNameRu(),
                title: $book->title,
                url: "/".$book->slug,
                textResults: TextResultData::collection($textResults)
            );
            $bookResults->push($bookResultData);
        }

        $result = new SearchResultsData(SuttaResultData::collection($suttaResults), BookResultData::collection($bookResults));
        return $result;
    }
}
