<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import Layout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

defineProps({
    suttas: Array,
    category: String,
});

</script>

<template>
    <Layout title="Admin area">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
<!--            <Breadcrumbs :items="[-->
<!--                {label:'Home', url: '/admin'},-->
<!--                {label: category.toUpperCase(), url: '/admin/suttas/' + category},-->
<!--                ]" />-->

            <div class="px-4 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <div class="text-2xl font-semibold leading-6 text-gray-700">Сутты {{category.toUpperCase()}}</div>
<!--                        <p class="mt-2 text-sm text-gray-700">Список сутт </p>-->
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
<!--                        <button type="button" class="block rounded-md bg-blue-500 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">Создать</button>-->
                    </div>
                </div>
                <div class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Сутта</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Контенты</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="sutta in suttas" :key="sutta.id">
                                        <td class="whitespace-nowrap py-5 pr-3 text-sm sm:pl-0">
                                            <div class="pl-4">
                                                <div class="font-medium text-gray-900">{{ sutta.name }}</div>
                                                <div class="mt-1 text-gray-500">{{ sutta.title_pali }}</div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                            <div v-for="content in sutta.contents" :key="content.id">
                                                <span v-if="content.is_original">Оригинал на пали.</span><span v-else>Перевод. {{content.lang}}: {{content.translator.signature}}</span>
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap py-5 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                            <div class="mr-4"><a :href="'/admin/edit_sutta/'+sutta.name" class="text-blue-500 hover:text-blue-600"
                                            >Edit<span class="sr-only">, {{ sutta.name }}</span></a
                                            ></div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>

    </Layout>
</template>
