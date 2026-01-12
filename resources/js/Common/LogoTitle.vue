<script setup>
import { useTranslation } from '@/composables/useTranslation.js';

const { t } = useTranslation();
</script>

<template>
    <div class="flex flex-col items-center w-36">
        <img src="/logo-white.svg" class="size-3/4 fill-current text-gray-500">
        <div class="mt-3 text-gray-800 text-center text-2xl font-serif leading-7">
            <div>{{ t('logo.foundation') }}</div>
            <div>{{ t('logo.canon') }}</div>
            <div>{{ t('logo.buddhism') }}</div>
        </div>
    </div>
</template>
