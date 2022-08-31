<div>
    @if($success)
        <div class="rounded-md bg-green-50 p-4 mt-2">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm leading-5 font-medium text-green-800">
                        {{$success}}
                    </p>
                </div>
            </div>
        </div>
    @else
    <p class="text-base font-medium text-gray-900">
        Проект находится в стадии разработки. Оставьте email, если хотите получить уведомление об открытии проекта.
    </p>
    <div x-data="{show_loader: false}" class="mt-3 sm:flex">
        <input aria-label="Email"
               class="appearance-none block w-full px-3 py-3 border border-gray-300 text-base leading-6 rounded-md placeholder-gray-500 shadow-sm focus:outline-none focus:placeholder-gray-400 focus:ring focus:border-blue-300 transition duration-150 ease-in-out sm:flex-1"
               placeholder="Email"
               wire:model="email"
        />
        @error('email')
        <p class="sm:hidden mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <button
            type="submit"
            class="mt-3 w-full px-6 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-gray-800 shadow-sm hover:bg-gray-700 focus:outline-none focus:ring active:bg-gray-900 transition duration-150 ease-in-out sm:mt-0 sm:ml-3 sm:flex-shrink-0 sm:inline-flex sm:items-center sm:w-auto"
            wire:click="submit_email()" wire:loading.attr="disabled"
        >
            Подписаться
        </button>
    </div>
        @error('email')
        <p class="hidden sm:block mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

    @endif
</div>
