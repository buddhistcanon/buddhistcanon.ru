<div>
    <div class="mt-4 mb-4">
        <h1>Буддийские термины</h1>
    </div>

    @forelse ($allTerms as $term)
        <div class="mb-4">
            <div class="title-3">
                #{{$term->id}} <a href="{{route('term',[$term->slug])}}" class="black-link">{{$term->title}}</a>
            </div>
        </div>
    @empty
        <p>Термины не найдены</p>
    @endforelse
</div>
