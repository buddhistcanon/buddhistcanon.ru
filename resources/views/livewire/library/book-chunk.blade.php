<div class="flex flex-row py-2 @if($numColumns>1) pr-4 @endif"
     x-data="{show_controls: false}" x-on:mouseover="show_controls = true" x-on:mouseout="show_controls = false">
    <div class="w-4 pt-1 mr-1" >
        <div x-show="show_controls" style="display: none">
            <div x-data="{ open_dropdown: false }" @keydown.escape="open_dropdown = false" @click.away="open_dropdown = false" class="relative inline-block text-left">
                <div>
                    <button @click="open_dropdown = !open_dropdown" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="Options" id="options-menu" aria-haspopup="true" x-bind:aria-expanded="open_dropdown">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>

                <div x-show="open_dropdown" x-description="Dropdown panel, show/hide based on dropdown state."
                     x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95"
                     class="origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg z-10" style="display: none;">
                    <div class="rounded-md bg-white shadow-xs">
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <a href="#" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">Ссылка на абзац</a>
                            <a href="#" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">Добавить в избранное</a>
                            <a href="#" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">Оставить комментарий</a>
                            <div class="border-t border-gray-100"></div>
                            <a href="#" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">Редактировать</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div x-show="!show_controls" class="w-4">&nbsp;</div>
    </div>

    <div class="text-justify @if($numColumns>1) w-column @else max-w-4xl @endif">
        {!! $html !!}
    </div>
</div>
