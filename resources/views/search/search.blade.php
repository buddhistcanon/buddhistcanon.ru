<?php
/**
 * @var \App\Data\Search\SearchResultsData $searchResultsData
 */
?>
<x-app-layout>

    <div class="container">

        <div class="flex flex-row content-between w-full">
            <h1 class="mb-4 flex-1">Поиск</h1>
            <div class="mb-4 text-xs text-right">
                <p>Элементов: {{$meilisearchService->numDocumentsInIndex()}}</p>
                <p>
                    @if($meilisearchService->isIndexing())
                        <span class="text-red-600">идёт индексация..</span>
                    @else
                        <span class="text-green-600">Индексация завершена</span>
                    @endif
                </p>
                <p><a href="/search/status" class="link">Статус поисковой базы</a></p>
            </div>
        </div>

        <form method="GET" action="/search">
        <div class="flex items-center w-full">
            <div class="">
                <input type="text" name="q" value="{{$searchTerm ?? ''}}" class="w-full focus:ring-blue-300 focus:border-blue-300 border-gray-300 rounded-md">
            </div>
            <div class="ml-2 w-24">
                <input type="submit" class="button" value="Искать" />
            </div>
        </div>
        </form>

        @error("q")
            <div class="text-red-600 mt-1 text-sm">{{$error}}</div>
        @enderror

        @if($isSearchPerformed)
        <div class="mt-8" x-data="{ show: 'suttas' }">

            <h2 class="mb-4">Результаты</h2>

            <div class="mb-8">
                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                    <select id="tabs" name="tabs" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option selected>Сутты</option>

                        <option>Книги</option>

                    </select>
                </div>
                <div class="hidden sm:block">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" -->
                               <a href="#"
                                  class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500"
                                  :class="show === 'suttas' ? 'border-blue-500 text-blue-600 ' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                  @click="show = 'suttas'"
                               >
                                Сутты
                            </a>

                            <a href="#"
                               class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500"
                               :class="show === 'books' ? 'border-blue-500 text-blue-600 ' : 'border-transparent hover:text-gray-700 hover:border-gray-300'"
                               @click="show = 'books'"
                            >
                                Книги
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <div x-show="show === 'suttas'">
                @forelse($searchResultsData->suttasResult as $suttaResultData)
                    <?php /** @var \App\Data\Search\SuttaResultData $suttaResultData  */ ?>
                    <div class="meilisearch pb-8 border-b border-gray-300 mb-8">
                        <div class="mb-4">
                            <span class="text-gray-300 text-sm mr-1">#{{$suttaResultData->suttaId}}</span>
                            <span class="text-xl mr-1">{{$suttaResultData->name}}</span>
                            <a href="{{$suttaResultData->url}}" class="link mr-2" target="_blank">открыть</a>
                            @if($suttaResultData->urlTheravadaRu) <a href="{{$suttaResultData->urlTheravadaRu}}" class="link" target="_blank">открыть на theravada.ru</a> @endif

                        </div>
                        <div class="ml-4">
                            @foreach($suttaResultData->textResults as $textResultData)
                                <?php /** @var \App\Data\Search\TextResultData $textResultData  */ ?>
                                <div class="mb-2">
                                    <span class="text-gray-300 text-sm mr-2">#{{$textResultData->contentChunkId}}</span>
                                    <span class="mr-2">... {!! $textResultData->html !!} ...</span>
{{--                                    <a href="{{$textResultData->urlWithMark}}" target="_blank" class="link">открыть абзац</a>--}}
                                </div>
                            @endforeach
                        </div>
{{--                        @dump($suttaResult->toArray())--}}
                    </div>
                @empty
                    <p>ничего не найдено.</p>
                @endforelse
            </div>

            <div x-show="show === 'books'">
                @forelse($searchResultsData->booksResult as $bookResultData)
                    <?php /** @var \App\Data\Search\BookResultData $bookResultData  */ ?>
                    <div class="meilisearch pb-8 border-b border-gray-300 mb-8">
                        <div class="mb-4">
                            <span class="text-gray-300 text-sm mr-1">#{{$bookResultData->bookId}}</span>
                            <span class="text-xl mr-1">{{$bookResultData->author}} "{{$bookResultData->title}}"</span>
                            <a href="{{$bookResultData->url}}" class="link" target="_blank">открыть</a>
                        </div>
                        <div class="ml-4">
                            @foreach($bookResultData->textResults as $textResultData)
                                <?php /** @var \App\Data\Search\TextResultData $textResultData  */ ?>
                                <div class="mb-2">
                                    <span class="text-gray-300 text-sm mr-2">#{{$textResultData->contentChunkId}}</span>
                                    <span class="mr-2">{!! $textResultData->html !!}</span>
                                    {{--                                    <a href="{{$textResultData->urlWithMark}}" target="_blank" class="link">открыть абзац</a>--}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p>ничего не найдено.</p>
                @endforelse
            </div>


        </div>
        @endif

    </div>
</x-app-layout>
