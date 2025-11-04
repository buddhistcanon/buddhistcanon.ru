<script setup>
import { ref } from 'vue';
import { Link, router, usePage } from "@inertiajs/vue3";
import { Dialog, Listbox, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue';
import { MagnifyingGlassIcon, CheckIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { PlayIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    search: String,
});

const page = usePage();

const menuBarOpen = ref(false);
const menuBarOpenShow = () => menuBarOpen.value = true;
const menuBarOpenClose = () => menuBarOpen.value = false;

const langs = [
    { id: 1, name: 'Русский', available: true },
    { id: 2, name: 'Английский', available: false },
];
const selectedLang = ref(langs[0]);

// const searchInputOpen = ref(false);
// const searchInputOpenShow = () => searchInputOpen.value = true;
// const searchInputOpenClose = () => searchInputOpen.value = false;

const search = ref(props.search);
const searchFocused = ref(false);
const showSearchX = ref(false);

const onSearchFocus = () => {
    searchFocused.value = true;
    showSearchX.value = true;
}

const onSearchBlur = () => {
    searchFocused.value = false;
    if (search.value == '') showSearchX.value = false;
}

const handleSearch = () => {
    if (search.value)
        router.get('/search', { search: search.value });
}

const clearSearch = () => {
    search.value = '';
    showSearchX.value = false;
}

const showChangeScale = ref(false);
const showChangeScaleOpen = () => showChangeScale.value = true;
const showChangeScaleClose = () => showChangeScale.value = false;

</script>

<template>
    <div class="sticky top-0 h-16 bg-white flex flex-row justify-between items-center">

        <div class="ml-3 h-11 w-14 flex justify-center items-center" @click="menuBarOpenShow">
            <div class="tribar"></div>
        </div>

        <div v-if="!showSearchX" class="bc-button h-11 w-14 flex justify-center items-center" @click="onSearchFocus">
            <MagnifyingGlassIcon class="block h-6 w-6" />
        </div>
        <div v-if="showSearchX" class="bc-button h-11 flex flex-1 justify-center items-center">
            <div class="inset-y-0 left-0 flex items-center pl-3">
                <XMarkIcon v-if="showSearchX" class="h-6 bc-text-gray cursor-pointer" aria-hidden="true"
                    @click="clearSearch" />
                <MagnifyingGlassIcon v-else class="h-6 bc-text-gray" aria-hidden="true" />
            </div>
            <input @focus="onSearchFocus" @blur="onSearchBlur" type="text"
                class="h-9 bc-text-gray w-full bc-button-background focus:outline-hidden border-none placeholder:text-center border-transparent focus:border-transparent focus:ring-0 placeholder:text-sm text-sm"
                v-model="search" @keyup.enter="handleSearch" placeholder="Поиск">
        </div>

        <div v-if="!showSearchX" class="bc-button h-11 w-14 flex justify-center items-center"
            @click="showChangeScaleOpen">
            <div class="font-[Open_Sans] text-xl ml-1">A</div>
            <div class="flex flex-col h-6">
                <div class="block h-3 w-3 translate-y-1">
                    <svg width="8" height="11" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4.35355 0.646446C4.15829 0.451184 3.84171 0.451184 3.64645 0.646446L0.464466 3.82843C0.269204 4.02369 0.269204 4.34027 0.464466 4.53553C0.659728 4.7308 0.976311 4.7308 1.17157 4.53553L4 1.70711L6.82843 4.53553C7.02369 4.7308 7.34027 4.7308 7.53553 4.53553C7.7308 4.34027 7.7308 4.02369 7.53553 3.82843L4.35355 0.646446ZM4.5 11L4.5 1L3.5 1L3.5 11L4.5 11Z"
                            fill="#272B2D" />
                    </svg>
                </div>
                <div class="block h-3 w-3 -translate-y-1">
                    <svg width="8" height="11" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3.64645 10.3536C3.84171 10.5488 4.15829 10.5488 4.35355 10.3536L7.53553 7.17157C7.7308 6.97631 7.7308 6.65973 7.53553 6.46447C7.34027 6.2692 7.02369 6.2692 6.82843 6.46447L4 9.29289L1.17157 6.46447C0.97631 6.2692 0.659728 6.2692 0.464466 6.46447C0.269204 6.65973 0.269204 6.97631 0.464466 7.17157L3.64645 10.3536ZM3.5 -2.24377e-08L3.5 10L4.5 10L4.5 2.24377e-08L3.5 -2.24377e-08Z"
                            fill="#272B2D" />
                    </svg>
                </div>
            </div>
            <Dialog :open="showChangeScale">
                <!-- fixed inset-0 bc-background min-h-screen w-full flex flex-col -->
                <div class="absolute mt-1 max-h-60 w-5/6 overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">

                    


                    <div class="h-11 w-11 flex justify-center items-center" @click="showChangeScaleClose">
                        <div class="xmark"></div>
                    </div>
                    <button></button>


                </div>

            </Dialog>

        </div>


        <div v-if="!showSearchX" class="bc-button h-11 w-14 flex justify-center items-center">
            <div class="block h-5 w-5 -translate-y-1 -translate-x-1">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 9V5L22 12L14 19V15C14 15 2 14.069 2 19.737C2 8.4 14 9 14 9Z" stroke="#272B2D"
                        stroke-width="1.3" stroke-miterlimit="10" />
                </svg>
            </div>
        </div>

        <div v-if="!showSearchX" class="bc-button h-11 w-14 flex justify-center items-center mr-5">
            <div class="block h-5 w-5 -translate-y-1 -translate-x-1">
                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M8.58911 4H17.4104C18.6628 4 19.6788 5.0152 19.679 6.26758V22.874L13.4534 18.1514L13.0002 17.8076L12.5471 18.1514L6.32153 22.874V6.26758C6.32168 5.01529 7.33683 4.00015 8.58911 4Z"
                        stroke="#272B2D" stroke-width="1.5" />
                </svg>
            </div>
        </div>

    </div>
    <Dialog :open="menuBarOpen">
        <div class="fixed inset-0 bc-background min-h-screen w-full flex flex-col">
            <div class="flex-1 flex flex-col px-4">
                <div class="flex flex-row">
                    <div class="w-11 py-2">
                        <div class="h-11 w-11 flex justify-center items-center" @click="menuBarOpenClose">
                            <div class="xmark"></div>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-row justify-center pt-5">
                        <div class="flex flex-col items-center w-36">
                            <img src="/logo-white.svg" class="size-3/4 fill-current text-gray-500">
                            <div class="mt-3 text-gray-800 text-center text-2xl font-serif leading-7">
                                <div>ФОНД</div>
                                <div>КАНОНА</div>
                                <div>БУДДИЗМА</div>
                            </div>
                        </div>
                    </div>
                    <div class="w-11"></div>
                </div>
                <div class="flex-1 flex flex-col px-2">

                    <Link v-if="!$page.props.auth.user"
                        class="bc-border bc-button-background bc-rounded text-center mt-7 py-4 text-base"
                        :href="'/login'">Вход / Регистрация</Link>
                    <Link v-else class="bc-border bc-button-background bc-rounded text-center mt-7 py-4 text-base"
                        :href="'/profile'">Профиль</Link>

                    <Link class="bc-border bc-button-background bc-rounded text-center mt-7 py-4 text-base" :href="'#'">
                    Мои закладки</Link>
                    <Link class="bc-border bc-button-background bc-rounded text-center mt-7 py-4 text-base" :href="'/'">
                    Главная страница</Link>
                    <Link class="bc-border bc-button-background bc-rounded text-center mt-7 py-4 text-base"
                        :href="'/about'">О Фонде</Link>

                    <div class="bc-border bc-button-background bc-rounded text-center mt-7 py-4 text-base">
                        <Listbox v-model="selectedLang">
                            <ListboxButton class="w-full h-full flex items-center">
                                <span class="w-4 ml-8"></span>
                                <span class="flex-1">{{ selectedLang.name }}</span>
                                <span class="w-4 mr-8">
                                    <PlayIcon class="rotate-90 h-4 text-gray-400" />
                                </span>
                            </ListboxButton>
                            <ListboxOptions
                                class="absolute mt-1 max-h-60 w-5/6 overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                <ListboxOption v-slot="{ active, selected }" v-for="lang in langs" :key="lang.id"
                                    :value="lang" :disabled="!lang.available" as="template">
                                    <li
                                        :class="[active ? 'bg-indigo-50 bc-text-indigo' : 'text-gray-700', 'relative cursor-default select-none py-2 pl-10 pr-4']">
                                        <span :class="[selected ? 'font-medium' : 'font-normal', 'block truncate',]">
                                            {{ lang.name }}
                                        </span>
                                        <span v-if="selected"
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 bc-text-indigo">
                                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                        </span>
                                    </li>
                                </ListboxOption>
                            </ListboxOptions>
                        </Listbox>
                    </div>

                    <Link
                        class="bc-border bc-button-background bc-rounded text-center mt-7 py-4 text-base text-gray-400"
                        :href="'#'">
                    Включить тёмное оформление</Link>

                </div>
                <div class="px-2">
                    <div class="text-center text-xs bc-mobile-bottom-color bc-mobile-bottom-line p-4">
                        <Link href="/policy" class="no-underline">Политика обработки персональных данных</Link>
                        <br /><span> и </span>
                        <Link href="/user_agreement" class="no-underline">пользовательское соглашение</Link>
                    </div>
                </div>
            </div>

            <!-- "fixed inset-0 z-10 overflow-y-auto bg-gray-800 bg-opacity-50" -->
            <!-- "min-h-screen px-4 text-center" -->
            <!-- "inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle
                    transition-all transform bg-white shadow-md rounded" -->
        </div>
    </Dialog>
</template>