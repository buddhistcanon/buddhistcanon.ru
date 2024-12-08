<script setup>
import ApplicationLayout from "@/Layouts/ApplicationLayout.vue";
import {textToHtml} from "@/helpers.js";
import {Link, Head, usePage} from "@inertiajs/vue3";
import LogoTitle from "@/Common/LogoTitle.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import {useWindowScroll} from '@vueuse/core';
import {ref, reactive, computed, onMounted} from "vue";
import {ChevronDownIcon, ChevronUpIcon, PencilSquareIcon} from "@heroicons/vue/24/outline";
import {onKeyStroke} from '@vueuse/core'

const page = usePage();
const user = computed(() => page.props.auth.user);
const roles = computed(() => page.props.auth.roles);

const props = defineProps({
    sutta: {type: Object, required: true},
    contents: {type: Array, required: true},
    nikayaTitle: {type: String, required: true},
    suttaSlug: {type: String, required: true},
    lang: {type: String, required: true},
    breadcrumbs: {type: Array, required: true},
    selectedContentId: {type: Number, required: true},
    chunksByContentId: {type: Object, required: true},
})

const selectedContentId = ref(props.selectedContentId);
const content = computed(() => {
    return props.contents.find(content => content.id === selectedContentId.value);
})
const contentChunks = computed(() => {
    return props.chunksByContentId[selectedContentId.value];
})

// Закрепление меню на сайдбаре при скролле страницы
const {x, y} = useWindowScroll();
const needSticky = computed(() => {
    return y.value > 267;
})

// Выбор перевода сутты
const openContentSelector = ref(false);
const handleContentSelectorClick = () => {
    openContentSelector.value = !openContentSelector.value;
}
const handleContentSelect = (contentId) => {
    selectedContentId.value = contentId;
    openContentSelector.value = false;
}
onKeyStroke(['1', '2', '3', '4', '5', '6', '7', '8', '9'], (e) => {
    e.preventDefault();
    let selectedContent = props.contents.filter((c, index) => {
        return index === parseInt(e.key) - 1
    });
    if (selectedContent[0]) {
        handleContentSelect(selectedContent[0].id);
    }
})

let anchorMark = "";
onMounted(() => {
    console.log(window.location);
    anchorMark = window.location.href.split('#')[1];
    if (anchorMark) {
        document.getElementById(anchorMark).scrollIntoView();
        console.log(anchorMark);
    }
});

const showEditIcon = () => {
    if (!user) return false;
    if (user.is_superadmin) return true;
    if (roles && (roles.includes('editor_russian') || roles.includes('editor_english'))) return true;
    return false;
}

</script>

<template>


    <ApplicationLayout>

        <Head :title="props.sutta.name"/>

        <div class="flex flex-col lg:flex-row w-full">
            <div class="bg-white p-4 w-full">

                <Breadcrumbs :items="props.breadcrumbs" class="mb-1"/>

                <div class="flex flex-row justify-between">
                    <div class="">
                        <h1 class="text-3xl font-normal font-serif">{{ nikayaTitle }}</h1>
                        <h2 class="text-lg font-normal">{{ sutta.title_transcribe_ru }} "{{
                                sutta.title_translate_ru
                            }}"</h2>
                    </div>
                    <div class="p-2">
                        <Link :href="'/admin/edit_sutta/'+sutta.name" v-if="user">
                            <PencilSquareIcon class="w-6 h-6 text-gray-400"/>
                        </Link>
                    </div>
                </div>

                <div v-if="content.translator_name" class="mt-1 text-sm">
                    Перевод: {{ content.translator_name }}
                    <span v-if="content.link_url" class="ml-2">
                        <a class="link" :href="content.link_url" target="_blank">Источник</a>
                    </span>
                </div>

                <div class="mt-8 content-text">
                    <div v-for="(chunk, index) of contentChunks">
                        <div v-if="content.is_synced !== '0'">
                            <div class="text-gray-400 text-xs">{{ index + 1 }}</div>
                            <div :id="chunk.mark" class="border-b pb-2"
                                 :class="chunk.mark === anchorMark ? 'bg-yellow-50' : ''" v-html="chunk.html"></div>
                        </div>
                        <div v-else>
                            <div :id="chunk.mark" class="my-4" :class="chunk.mark === anchorMark ? 'bg-yellow-50' : ''"
                                 v-html="chunk.html"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:ml-4 lg:w-96 flex flex-col items-center ">
                <div class="mb-8">
                    <LogoTitle/>
                </div>
                <div :class="[needSticky ? 'fixed' : 'relative', '']">
                    <div class="flex flex-col items-center">
                        <div class="bc-button w-72 text-sm py-2 px-2 flex flex-col items-center">
                            <div class="flex flex-row items-start cursor-pointer"
                                 @click="handleContentSelectorClick()">
                                <div class="">{{ content.short_description }}</div>
                                <ChevronDownIcon v-if="!openContentSelector" class="w-4 text-gray-800"/>
                                <ChevronUpIcon v-if="openContentSelector" class="w-4 text-gray-800"/>
                            </div>
                            <div v-if="openContentSelector" class="flex flex-col items-start">
                                <div v-for="(content, index) of contents" class="mt-4 cursor-pointer"
                                     @click="handleContentSelect(content.id)">{{ index + 1 }}.
                                    {{ content.short_description }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-2 mb-4 w-72 text-xs text-gray-500">
                            Переключать переводы также можно при помощи нажатия клавиш 1, 2, 3 и т.д.
                        </div>

                        <!--                        <div class="button w-72 text-center my-4">Поделиться суттой</div>-->
                        <!--                        <div class="button w-72 text-center my-4">Добавить в закладки</div>-->

                        <!--                        <div class="border-b border-gray-200 mt-4 mb-3 w-72"></div>-->
                        <!--                        <div class="text-sm text-gray-800 cursor-pointer">сообщить об ошибке перевода</div>-->
                        <!--                        <div class="border-b border-gray-200 mt-3 mb-4 w-72"></div>-->
                    </div>
                </div>


            </div>
        </div>
    </ApplicationLayout>
</template>
