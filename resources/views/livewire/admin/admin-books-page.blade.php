<div
    x-data="{

    }"
>

    <h1 class="text-4xl font-bold mb-8">Книги</h1>

    <x-button type="primary" @click="show_modal_create_invite = true" size="lg" class="shadow mb-4">
        <x-icons.icon-plus-circle class="h-5 w-5 mr-2"></x-icons.icon-plus-circle>
        Добавить книгу
    </x-button>

    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider w-32">
                            id
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider w-32">
                            Автор
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Название
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 w-16"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    @foreach($books as $book)
                        <tr wire:key="{{ $book->index }}" x-data="{show_loader: false}" x-on:show-edit-form.window="show_loader = false;">
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm text-gray-600">
                                {{$book->id}}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                {{$book->author->full_name}}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-600">
                                {{$book->title}}
                            </td>
                            <td class="px-6 py-4 text-right border-b border-gray-200 text-sm leading-5 font-medium">
                                <a href="/admin/edit_book/{{$book->id}}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$books->links()}}
            </div>
        </div>
    </div>

</div>
