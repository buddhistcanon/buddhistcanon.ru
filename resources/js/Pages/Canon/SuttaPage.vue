<script setup>
import ApplicationLayout from "@/Layouts/ApplicationLayout.vue";
import { textToHtml } from "@/helpers.js";
import {Link} from "@inertiajs/inertia-vue3";

defineProps({
    sutta: { type: Object, required: true },
    content: { type: Object, required: true },
    nikayaTitle: { type: String, required: true },
    contentChunks: { type: Array, required: true },
    suttaSlug: { type: String, required: true },
    lang: { type: String, required: true },
})
</script>

<template>
    <ApplicationLayout>
        <div class="flex flex-col lg:flex-row w-full">
            <div class="bg-white p-4 w-full">
                <h1 class="text-xl">{{nikayaTitle}}</h1>
                <h2 class="text-lg">{{sutta.title_transcribe_ru}} "{{sutta.title_translate_ru}}"</h2>
                <div class="mt-4 content-text">
                    <div class="my-4" v-for="chunk of contentChunks" v-html="chunk">
                    </div>
                </div>
            </div>
            <div class="lg:ml-4 lg:w-96 flex flex-col items-center">
                <div class="py-8">
                    лого
                </div>
                <Link v-if="lang !== 'en'" :href="suttaSlug+'/en/sujato'" class="button w-80 text-center my-4">Эта сутта на английском языке</Link>
                <Link v-if="lang !== 'ru'" :href="suttaSlug+'/ru/sv'" class="button w-80 text-center my-4">Эта сутта на русском языке</Link>
                <Link v-if="lang !== 'pali'" :href="suttaSlug+'/pali'" class="button w-80 text-center my-4">Эта сутта на языке пали </Link>
                <div class="button w-80 text-center my-4">Поделиться суттой</div>
                <div class="button w-80 text-center my-4">Добавить в закладки</div>

                <div class="border-b border-gray-200 mt-4 mb-3 w-80"></div>
                <div class="text-sm text-gray-800 cursor-pointer">сообщить об ошибке перевода</div>
                <div class="border-b border-gray-200 mt-3 mb-4 w-80"></div>

            </div>
        </div>
    </ApplicationLayout>
</template>
