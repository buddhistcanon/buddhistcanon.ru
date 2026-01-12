<script setup>
//import ApplicationLayout from '@/Layouts/ApplicationBreeze.vue';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import {Head} from '@inertiajs/vue3';
import { useTranslation } from '@/composables/useTranslation.js';

const props = defineProps({
    user: Object
});

const { t } = useTranslation();
</script>

<template>
    <Head :title="t('profile.title')"/>
    <ApplicationLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('profile.title') }}
            </h2>
        </template>

        <div class="py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{ t('profile.greeting') }}, {{ user.nickname }}.
                </div>
            </div>

        </div>
    </ApplicationLayout>
</template>
