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
            <h1 class="text-2xl mb-4">Палийский канон</h1>
            <p><Link href="/mn" class="link">Мадджхима никая</Link></p>
        </div>

    </ApplicationLayout>
</template>
