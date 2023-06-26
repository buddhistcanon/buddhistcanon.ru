<script setup>
import {Link} from "@inertiajs/inertia-vue3";
import LogoTitle from "@/Common/LogoTitle.vue";
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="py-8">
            <LogoTitle />
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
</template>
