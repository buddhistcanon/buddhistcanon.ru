<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md mb-4">
        <a href="{{ route('home') }}">
            <x-heroicon-s-arrow-narrow-left class="inline h-6 w-6"></x-heroicon-s-arrow-narrow-left>
            назад на сайт
        </a>

        <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900 leading-9">
            Войдите в свой аккаунт
        </h2>
        <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w">
            или
            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                зарегистрируйтесь
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 leading-5">
                        Email
                    </label>

                    <div class="mt-1 rounded-md shadow-sm">
                        <input wire:model.defer="email" id="email" name="email" type="email" required autofocus class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-300 focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('email') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red-300 @enderror" />
                    </div>

                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 leading-5">
                        Пароль
                    </label>

                    <div class="mt-1 rounded-md shadow-sm">
                        <input wire:model.defer="password" id="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-300 focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('password') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red-300 @enderror" />
                    </div>

                    @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mt-6">
                    <div class="flex items-center">
                        <input wire:model.defer="remember" id="remember" type="checkbox" class="form-checkbox w-4 h-4 text-indigo-600 transition duration-150 ease-in-out" />
                        <label for="remember" class="block ml-2 text-sm text-gray-900 leading-5">
                            запомнить меня
                        </label>
                    </div>

                    <div class="text-sm leading-5">
{{--                        <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">--}}
{{--                            Забыли пароль ?--}}
{{--                        </a>--}}
                    </div>
                </div>

                <div class="mt-6">
                    <span class="block w-full rounded-md shadow-sm">
                        <button wire:click="authenticate" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring-indigo-300 active:bg-indigo-900 transition duration-150 ease-in-out">
                            <svg wire:loading wire:target="authenticate" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Вход
                        </button>
                    </span>
                </div>
{{--                <div class="mt-6" x-data="{show_loader: false}" @authenticate-done.window="show_loader = false">--}}
{{--                    <span class="block w-full rounded-md shadow-sm">--}}
{{--                        <button wire:click="authenticate" @click="show_loader = true" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring-indigo active:bg-indigo-900 transition duration-150 ease-in-out">--}}
{{--                            <svg x-show="show_loader" x-cloak class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">--}}
{{--                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>--}}
{{--                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>--}}
{{--                            </svg>--}}
{{--                            wire:click, alpine.js @click and x-show--}}
{{--                        </button>--}}
{{--                    </span>--}}
{{--                </div>--}}

        </div>
    </div>
</div>
