<script setup>
import {Link} from '@inertiajs/vue3';

defineProps({
    label: String,
    href: String,
    class: String,
})
</script>

<template>
    <Link :href="href">{{label}}</Link>
</template>
