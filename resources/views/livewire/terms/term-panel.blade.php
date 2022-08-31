<div>

    <div x-data="{ open: false }" x-on:show-panel.window="open = true" x-on:hide-panel.window="open = false; @this.set('title',''); @this.set('content','')" @keydown.window.escape="open = false; @this.set('title',''); @this.set('content','')"
         x-show="open" class="fixed inset-0 overflow-hidden bg-transparent" style="display:none">
        <div class="absolute inset-0 overflow-hidden bg-gray-300 bg-opacity-75 transition-opacity">
            <div x-show="open" x-description="Background overlay, show/hide based on slide-over state."
                 class="absolute inset-0 overflow-hidden"
            ></div>

            <section @click.away="open = false; @this.set('title',''); @this.set('content','')" class="absolute inset-y-0 right-0 pl-10 max-w-full flex">
                <div class="w-screen max-w-md" x-description="Slide-over panel, show/hide based on slide-over state." x-show="open"
                     x-transition:enter="transform transition ease-in-out duration-100 sm:duration-100" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-100 sm:duration-100" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                >
                    <div class="h-full flex flex-col space-y-6 py-6 bg-white shadow-xl overflow-y-scroll">
                        <header class="px-4 sm:px-6">
                            <div class="flex items-start justify-between space-x-3">
                                <h2 class="text-lg leading-7 font-medium text-gray-900">
                                    {{$title}}
                                </h2>
                                <div class="h-7 flex items-center">
                                    <button @click="open = false; @this.set('title',''); @this.set('content','')" aria-label="Close panel" class="text-gray-400 hover:text-gray-500 transition ease-in-out duration-150">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </header>
                        <div class="relative flex-1 px-4 sm:px-6">
                            <!-- Replace with your content -->
                            <div class="absolute inset-0 px-4 sm:px-6">
                                <div class="h-full border-2 border-dashed border-gray-200"></div>
                            </div>
                            <!-- /End replace -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

</div>
