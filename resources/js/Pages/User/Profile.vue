<script setup>
//import ApplicationLayout from '@/Layouts/ApplicationBreeze.vue';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import {Head} from '@inertiajs/inertia-vue3';

const props = defineProps({
    user: Object
});
</script>

<template>
    <Head title="Profile"/>

    <ApplicationLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Профайл
            </h2>
        </template>

        <div class="py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Здравствуйте, {{ user.name }}.
                </div>
            </div>

        </div>
    </ApplicationLayout>
</template>
