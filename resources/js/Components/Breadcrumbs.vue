<script setup>
import { HomeIcon } from '@heroicons/vue/20/solid'
import { Link } from "@inertiajs/inertia-vue3";
import LinkSafe from "@/Components/LinkSafe.vue";

defineProps({
    items: Array,
})
// const items = [
//     { title: 'Projects', url: '#', current: false },
//     { title: 'Project Nero', url: '#', current: true },
// ]
</script>

<template>

    <nav class="flex" aria-label="Breadcrumb">
        <ol role="list" class="flex items-center space-x-1">
<!--            <li>-->
<!--                <div>-->
<!--                    <a href="#" class="text-gray-400 hover:text-gray-500">-->
<!--                        <HomeIcon class="h-4 w-4 flex-shrink-0" aria-hidden="true" />-->
<!--                        <span class="sr-only">Home</span>-->
<!--                    </a>-->
<!--                </div>-->
<!--            </li>-->
            <li v-for="(item, index) in items" :key="item.label">
                <div class="flex items-center">
                    <svg v-if="index!==0" class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                    </svg>
                    <a v-if="item.url !== ''" :href="item.url" class="ml-1 text-sm font-medium text-gray-400 hover:text-gray-600" :aria-current="item.current ? 'item' : undefined">{{ item.title }}</a>
                    <span v-else class="ml-1 text-sm font-medium text-gray-400 hover:text-gray-600" :aria-current="item.current ? 'item' : undefined">{{ item.title }}</span>
                </div>
            </li>
        </ol>
    </nav>

</template>

