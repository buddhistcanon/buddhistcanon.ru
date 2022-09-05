<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
})
</script>

<template>
    <Head title="Фонд канона Буддизма" />

    <ApplicationLayout>

        <div class="rounded-lg bg-white p-4">

        </div>

    </ApplicationLayout>
</template>
