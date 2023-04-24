<script setup>
import { Link} from '@inertiajs/inertia-vue3';

defineProps({
    label: String,
    href: String,
    class: String,
})
</script>

<template>
    <Link :href="href" >{{label}}</Link>
</template>
