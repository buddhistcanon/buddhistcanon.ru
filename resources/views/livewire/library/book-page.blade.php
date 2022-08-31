<?php /** @var \App\Models\Book $book */?>
<?php /** @var \App\Models\Author $book->author */?>

{{--layout.wide--}}
<div x-data="{ open_right_panel: false }">
<div class="max-w-6xl mx-auto py-2 px-2 sm:px-6 lg:px-8">

    <div class="">
        <div class="py-5 border-b border-gray-200">
            <p class="mt-1 max-w-2xl font-bold mb-4">
                <a href="{{route("library.author", [$book->author->slug])}}" class="link">{{$book->author->full_name}}</a>
            </p>
            <h1 class="">
                {{$book->title}}
            </h1>
        </div>

        @if($book->is_copyrighted)
            <div class="mb-8">
                <div class="property_title">
                    Книга не лицензирована для свободного распространения.
                </div>
                <dd class="mt-1 property_text">
                    <div class="mb-4">{{$book->copyright_info}}</div>
                    <div>Вы можете купить её здесь: <a href="{{$book->buy_url}}">{{$book->buy_url}}</a></div>
                </dd>
            </div>
        @endif

        @if($numContents > 1)
            {{--  Книга с несколькими переводами или оригиналом и переводом  --}}
            <div class="py-5">
                @if($book->description)
                    <div class="w-full mb-8">
                        <div class="property_text">
                            {{$book->description}}
                        </div>
                    </div>
                @endif
                <div class="flex flex-col md:flex-row">
                    <div class="md:flex-1">
                        <div class="mb-8">
                            <div class="property_title">
                                Оригинальное название:
                            </div>
                            <div class="mt-1 property_text">
                                @if($book->original_url)<a target="_blank" class="link" href="{{$book->original_url}}">{{$book->original_title}}</a>@else{{$book->original_title}}@endif
                            </div>
                        </div>
                        <div class="mb-8">
                            <div class="property_title">
                                @if($book->getTranslatorsNames()->count() > 1) Переводчики: @else Переводчик: @endif
                            </div>
                            <div class="mt-1 property_text">
                                @foreach($book->getTranslatorsNames() as $name)
                                    <div>{{$name}}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="md:flex-1">
                        @if($book->link_url)
                        <div class="mb-8">
                            <div class="property_title">
                                Взято с ресурса:
                            </div>
                            <div class="mt-1 property_text">
                                <a target="_blank" class="link" href="{{$book->link_url}}">{{parse_url($book->link_url, PHP_URL_HOST)}}</a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <div class="property_title mb-2">
                        Контент книги
                    </div>
                    <table class="w-full border-b border-white">
                    @foreach($contentMenu as $contentId => $data)
                        <tr class="border-b border-gray-200">
                            <td class="p-2 w-4"><input type="checkbox" @if($data['is_show']) checked @endif wire:click="toggleShowContent({{$contentId}})" class="mr-2"/></td>
                            <td class="">{{$data['title']}} @if($data['url']) <span class="text-sm ml-4">Источник: <a target="_blank" class="link" href="{{$data['url']}}">{{parse_url($data['url'], PHP_URL_HOST)}}</a></span> @endif</td>
                            <td class="text-right"><a href="#" class="link text-sm">Скачать в FB2</a></td>
                        </tr>
                    @endforeach
                    </table>
                    <div class="mt-1 h-4">
                        <div wire:loading wire:target="toggleShowContent" class="text-sm text-gray-400">Загрузка контента..</div>
                    </div>
                </div>

            </div>


        @else
            {{--  Книга без оригинала, с одним переводом  --}}
            <div class="py-5 border-b border-gray-200">
                @if($book->description)
                    <div class="w-full mb-8">
                        <div class="property_text">
                            {{$book->description}}
                        </div>
                    </div>
                @endif
                <div class="flex flex-col md:flex-row">
                    <div class="md:flex-1">
                        <div class="mb-8">
                            <div class="property_title">
                                Оригинальное название:
                            </div>
                            <div class="mt-1 property_text">
                                @if($book->original_url)<a target="_blank" class="link" href="{{$book->original_url}}">{{$book->original_title}}</a>@else{{$book->original_title}}@endif
                            </div>
                        </div>
                        <div class="mb-8">
                            <div class="property_title">
                                @if($book->getTranslatorsNames()->count() > 1) Переводчики: @else Переводчик: @endif
                            </div>
                            <div class="mt-1 property_text">
                                @foreach($book->getTranslatorsNames() as $name)
                                    <div>{{$name}}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="md:flex-1">
                        @if($book->link_url)
                            <div class="mb-8">
                                <div class="property_title">
                                    Взято с ресурса:
                                </div>
                                <div class="mt-1 property_text">
                                    <a target="_blank" class="link" href="{{$book->link_url}}">{{parse_url($book->link_url, PHP_URL_HOST)}}</a>
                                </div>
                            </div>
                        @endif

                        <div class="mb-8">
                            <div class="mt-1 property_text">
                                <div>
                                    <span class="rounded-md shadow-sm">
                                      <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring-blue-300 active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150">
                                        Скачать книгу в FB2
                                      </button>
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>



            </div>


        @endif


</div>

</div>


<div class="flex flex-row justify-center @if($contentChunks AND count($contentChunks[0]) > 1) max-w-9xl @else max-w-5xl @endif mx-auto px-2 sm:px-6 lg:px-8">

    <div class="py-4 content-text">

        @foreach($contentChunks as $row)
        <div class="flex flex-col lg:flex-row" {{--wire:key="loop{{$loop->index}}"--}} >
            @foreach($row as $chunk)
{{--                <livewire:library.book-chunk :text="$chunk['text']" :num-columns="count($row)" :book-id="$book->id" :content-id="$chunk['content_id']" :key="$chunk['id']"/>--}}
{{--                @include("library/chunk", ['numColumns'=>count($row), 'html'=>$textParser->parse($chunk['text'], $book->id)])--}}
                @include("library/chunk", ['numColumns'=>count($row), 'html'=>$chunk['html']])
            @endforeach
        </div>
        @endforeach

    </div>

    <livewire:terms.term-panel />

</div>

</div>
