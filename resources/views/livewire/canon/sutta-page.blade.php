<?php /** @var \App\Models\Sutta $sutta */?>

{{--layout.wide--}}
<div>
<div class="max-w-5xl mx-auto py-2 px-2 sm:px-6 lg:px-8">

    <h1 class="mb-4">
        {{$suttaTitle}}
    </h1>

    <div class="mb-4">
        <div class="property_title mb-2">
            Перевод
        </div>
        <table class="w-full border-b border-white">
            @foreach($contentMenu as $contentId => $data)
                <tr class="border-b border-gray-200">
                    <td class="p-2 w-4"><input type="checkbox" @if($data['is_show']) checked @endif wire:click="toggleShowSuttaContent({{$contentId}})" class="mr-2"/></td>
                    <td class="">
                        <div>{{$data['title']}}</div>
                        <div class="text-gray-600 text-sm">{{$data['description']}}</div>
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="mt-1 h-4">
            <div wire:loading wire:target="toggleShowSuttaContent" class="text-sm text-gray-400">Загрузка..</div>
        </div>
    </div>

</div>


<div class="flex flex-row justify-center @if($contentChunks AND count($contentChunks[0]) > 1) max-w-9xl @else max-w-5xl @endif mx-auto px-2 sm:px-6 lg:px-8 ">

    <div class="py-4 content-text">

        @forelse($contentChunks as $row)
            <div class="flex flex-col lg:flex-row" wire:key="loop{{$loop->index}}">
                @foreach($row as $chunk)
                    <livewire:canon.sutta-chunk :text="$chunk['text']" :num-columns="count($row)" :content-id="$chunk['content_id']" :mark="$chunk['mark']" :key="$chunk['id']"/>
                @endforeach
            </div>
        @empty
            <p class="text-gray-600">Выберите перевод для отображения..</p>
        @endforelse

    </div>

</div>
</div>
