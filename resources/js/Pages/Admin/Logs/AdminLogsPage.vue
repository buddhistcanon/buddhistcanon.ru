<script setup>
import Layout from "@/Layouts/AdminLayout.vue";
import {Head} from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import Pagination from "@/Components/Pagination.vue";
import {format} from 'date-fns';

const props = defineProps({
    logs: Array,
});
</script>

<template>
    <Layout title="Edit sutta">
        <Head>
            <title>Действия пользователей</title>
        </Head>

        <div class="mb-2">
            <Breadcrumbs :pages="[{label:'Сутты', url: '/admin/suttas'}]"/>
        </div>

        <div class="mx-auto w-full px-4 sm:px-6 lg:px-8">

            <div class="text-2xl font-semibold leading-6 text-gray-700 mb-4">
                <h1>Логи: редактирование контента</h1>
            </div>

            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                        Дата
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Пользователь
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Действие
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Сутта
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Отредактированный контент
                                    </th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="log in logs.data" :key="log.id">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-800 sm:pl-6">
                                        {{ format(log.created_at, "d.MM.yyyy HH:mm") }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-800">
                                        {{ log.user.nickname }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-800">
                                        {{ log.action }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-800">
                                        {{ log.sutta.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        <span class="text-gray-500 mr-4">#{{ log.content.id }}</span>
                                        <span class="text-gray-800">{{ log.content.short_description }}</span>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">

                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <Pagination class="pt-6 px-4 pb-4 bg-white" :links="logs.links"/>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </Layout>
</template>
