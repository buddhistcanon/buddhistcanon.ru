<div
    x-data="{
        show_modal_create_invite: false,
        show_modal_edit_invite: false
    }
    "
    x-on:invite-created.window="show_modal_create_invite = false; "
    x-on:invite-edited.window="show_modal_edit_invite = false; "
    x-on:edit-invite="show_modal_edit_invite = true; "
    x-on:show-edit-form="show_modal_edit_invite = true;"
>

    <h1 class="text-4xl font-bold mb-8">Инвайты</h1>

{{--    <button @click="show_modal_create_invite = true" class="button mb-8">--}}
{{--        Создать инвайт--}}
{{--    </button>--}}


    <x-button type="primary" @click="show_modal_create_invite = true" class="shadow mb-4">
        <x-icons.icon-plus-circle class="h-5 w-5 mr-2"></x-icons.icon-plus-circle>
        Создать инвайт
    </x-button>

    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider w-32">
                            Создан
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider w-32">
                            Инвайт
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Назначение
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Зарегистрированный пользователь
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 w-16"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    @foreach($invites as $invite)
                        <tr wire:key="{{ $loop->index }}" x-data="{show_loader: false}" x-on:show-edit-form.window="show_loader = false;">
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm text-gray-600">
                                {{$invite->created_at->format("d.m.Y H:i")}}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 font-mono">
                                {{$invite->invite_symbols}}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-600">
                                {{$invite->note}}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm leading-5 text-gray-500">
                                @if($invite->registered_user_if)
                                    <span class="mr-2">{{$invite->user->email}}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right border-b border-gray-200 text-sm leading-5 font-medium">
                                <span wire:click="show_edit_form({{$invite->id}})" @click="show_loader = true;" x-show="show_loader === false" class="text-blue-600 hover:text-blue-900 cursor-pointer">Edit</span>
                                <x-spinner x-show="show_loader" class="h-4 w-4" ></x-spinner>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$invites->links()}}
            </div>
        </div>
    </div>


    <div x-show="show_modal_create_invite" x-cloak >
        <div class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div
                class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Создание инвайта
                        </h3>
                        <div class="mt-4">
                            <p class="text-sm leading-5 text-gray-500 mt-2 mb-2">Назначение инвайта:</p>
                            <input type="text" wire:model.defer="note"
                                   class="form-input @error('note') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red @enderror"/>
                            @error('note')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:ml-10 sm:pl-4 sm:flex">
                    <span class="flex w-full rounded-md shadow-sm sm:w-auto">
                        <x-button type="success" size="sm" wire:click="create_invite" >
                            <x-spinner class="mr-2 h-4 w-4" wire:loading wire:target="create_invite" ></x-spinner>
                            <span>Создать инвайт</span>
                        </x-button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                        <x-button type="default" size="sm" @click="show_modal_create_invite = false" type="button" class="button">
                          Отмена
                        </x-button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div x-show="show_modal_edit_invite" x-cloak >
        <div class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div
                class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" >
                            Редактирование инвайта
                        </h3>
                        <div class="mt-4">
                            <p class="text-sm leading-5 text-gray-500 mt-2 mb-2">Назначение инвайта:</p>
                            <input type="text" wire:model.defer="note"
                                   class="form-input @error('note') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red @enderror"/>
                            @error('note')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:ml-10 sm:pl-4 sm:flex">
                        <span class="flex w-full rounded-md shadow-sm sm:w-auto">
                            <x-button type="success" size="sm" wire:click="edit_invite" >
                                <x-spinner class="mr-2 h-4 w-4" wire:loading wire:target="edit_invite" ></x-spinner>
                              Сохранить
                            </x-button>
                        </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                            <x-button type="default" size="sm" @click="show_modal_edit_invite = false;" >
                              Отмена
                            </x-button>
                        </span>
                </div>
            </div>
        </div>
    </div>

</div>
