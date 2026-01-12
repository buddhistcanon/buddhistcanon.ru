<script setup>
import { Link } from "@inertiajs/vue3";
import LogoTitle from "@/Common/LogoTitle.vue";
import { useTranslation } from '@/composables/useTranslation.js';

const { t } = useTranslation();
</script>

<template>
    <div class="flex flex-col items-center mt-2 w-1/4">
        <LogoTitle />
        <div class="flex flex-col self-stretch mx-16">
            <Link href="/about" class="py-2 text-center w-full mt-7 bc-button">{{ t('nav.about') }}</Link>
            <Link href="#" class="py-2 text-center w-full mt-7 bc-button-disabled">{{ t('nav.dark_theme') }}</Link>
            <!--        <Link href="/news" class="bc-button py-2 text-center w-full my-4">Новое на сайте</Link>-->
            <Link v-if="$page.props.auth?.user" href="/bookmarks" class="bc-button py-2 text-center w-full my-4">{{ t('nav.bookmarks') }}</Link>
        </div>
    </div>
</template>
