<script setup>
import { HomeIcon } from '@heroicons/vue/20/solid'
import { Link } from "@inertiajs/inertia-vue3";
import LinkSafe from "@/Components/LinkSafe.vue";

defineProps({
    items: Array,
    root: String,
})
// const items = [
//     { label: 'Projects', url: '#', current: false },
//     { label: 'Project Nero', url: '#', current: true },
// ]
</script>

<template>
    <nav class="flex" aria-label="Breadcrumb">
        <ol role="list" class="flex space-x-4 rounded-md bg-white px-6 shadow">
            <li class="flex">
                <div class="flex items-center">
                    <Link :href="root" class="text-gray-400 hover:text-gray-500">
                        <HomeIcon class="h-5 w-5 flex-shrink-0" aria-hidden="true" />
                        <span class="sr-only">Home</span>
                    </Link>
                </div>
            </li>
            <li v-for="item in items" :key="item.label" class="flex">
                <div class="flex items-center">
                    <svg class="h-full w-6 flex-shrink-0 text-gray-200" viewBox="0 0 24 44" preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
                        <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
                    </svg>
                    <div v-if="item.url !== ''">
                        <Link :href="item.url" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700" :aria-current="item.current ? 'item' : undefined">{{ item.label }}</Link>
                    </div>
                    <div v-else>
                        <span class="ml-4 text-sm font-medium text-gray-700" >{{ item.label }}</span>
                    </div>

                </div>
            </li>
        </ol>
    </nav>
</template>

