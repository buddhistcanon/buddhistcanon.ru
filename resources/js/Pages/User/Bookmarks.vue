<script setup>
import ApplicationLayout from "@/Layouts/ApplicationLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import Sidebar from "@/Common/Sidebar.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import { BookmarkIcon as BookmarkAddedIcon } from '@heroicons/vue/24/solid';
import { ref } from 'vue';

const props = defineProps({
    bookmarks: { type: Array, required: true },
})

const suttaUrl = (sutta) => {
    if (sutta.suborder) {
        return `/${sutta.category}${sutta.order}.${sutta.suborder}`;
    } else {
        return `/${sutta.category}${sutta.order}`;
    }
}

const removeBookmark = async (sutta) => {
    try {
        await window.axios.delete(`/bookmarks/${sutta.id}`);
        // Обновляем страницу после удаления
        router.reload({ only: ['bookmarks'] });
    } catch (error) {
        console.error('Ошибка при удалении закладки:', error);
    }
}

</script>

<template>

    <Head title="Мои закладки" />

    <ApplicationLayout>

        <div class="flex-1 flex flex-col lg:flex-row">
            <div class="flex-1 flex flex-col lg:flex-row">
                <div class="bg-white p-4 w-full">

                    <Breadcrumbs :items="[
                        { title: 'Главная', url: '/' },
                    ]" class="mb-1" />

                    <div class="font-serif text-2xl mb-6">Мои закладки</div>

                    <div v-if="bookmarks.length === 0" class="text-center py-12 text-gray-500">
                        У вас пока нет закладок
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-for="sutta in bookmarks" :key="sutta.id" class="bc-button px-4 py-4 mb-2 relative">
                            <div class="flex flex-row justify-between items-start">
                                <Link :href="suttaUrl(sutta)" class="flex-1">
                                    <div class="flex flex-row justify-between">
                                        <div class="text-xl mb-2">{{ sutta.name }}</div>
                                        <div>
                                            <span class="mr-1" v-for="content in sutta.contents" :key="content.id">

                                            </span>
                                        </div>
                                    </div>
                                    <div>{{ sutta.title_transcribe_ru }}</div>
                                </Link>
                                <button
                                    @click="removeBookmark(sutta)"
                                    class="ml-4 p-2 hover:bg-gray-100 rounded transition-colors"
                                    title="Удалить из закладок"
                                >
                                    <BookmarkAddedIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <Sidebar />

        </div>

    </ApplicationLayout>

</template>

