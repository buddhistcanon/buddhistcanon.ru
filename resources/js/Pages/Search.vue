<script setup>
import {Head, Link} from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import Sidebar from "@/Common/Sidebar.vue";
import {ref} from 'vue';

const props = defineProps({
    search: String,
    result: Object
});

const search = ref(props.search);


</script>

<template>
    <Head title="Новости"/>

    <ApplicationLayout>

        <div class="flex flex-col md:flex-row">
            <div class="flex flex-col lg:flex-row w-full">
                <div class="bg-white p-4 w-full">

                    <div class="mt-4 mb-4 font-serif text-4xl">Поиск</div>

                    <div class="mb-8">
                        <input type="text" class="w-full p-2 border border-gray-300 rounded" v-model="search"
                               placeholder="Поиск...">
                    </div>


                </div>
            </div>

            <div class="md:ml-4 md:w-96">
                <Sidebar/>
            </div>

        </div>


    </ApplicationLayout>
</template>
