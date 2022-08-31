<div>
{{--    <div class="pb-10">--}}
{{--    <x-library-menu></x-library-menu>--}}
{{--    </div>--}}

    @foreach($authors as $author)
        <?php
        /** @var \App\Models\People $author */
        ?>
        <div class="mb-10">
            <div class="mb-4">
                <div class="title-1">{{$author->displayNameRu()}}</div>
                <a href="{{route('library.author',[$author->slug])}}" class="text-sm gray-link">об авторе</a>
            </div>
            @foreach($author->books as $book)
                <div class="mb-8 pl-8">
                    <div class="title-2 mb-1">
                        <a href="{{route('library.book',[$book->slug])}}" class="black-link">{{$book->title}}</a>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{$book->short_description}}
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

</div>
