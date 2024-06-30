<script setup>
import Layout from "@/Layouts/AdminLayout.vue";
import {Head} from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import Pagination from "@/Components/Pagination.vue";
import {format} from 'date-fns';
import {ref} from "vue";
import DiffMatchPatch from 'diff-match-patch';

const props = defineProps({
    logs: Array,
});

const expandedDiff = ref(new Set());

const diffs = {};

function diffToHtml(diffs) {
    var html = [];
    diffs.forEach(function(part) {
        var type = part[0];
        var text = part[1];
        var className;
        switch (type) {
            case 1:
                className = 'diff-insert';
                break;
            case -1:
                className = 'diff-delete';
                break;
            default:
                className = 'diff-equal';
        }
        html.push('<span class="' + className + '">' + text + '</span>');
    });
    return html.join('');
}

props.logs.data.forEach((log) => {
    const dmp = new DiffMatchPatch();
    console.log(log.before, log.after);
    const diff = dmp.diff_main(
        JSON.stringify(JSON.parse(log.before || '{}'), null, 2)
            .split("\\n").join("\\n\n"),
        JSON.stringify(JSON.parse(log.after || '{}'), null, 2)
            .split("\\n").join("\\n\n"),
    );
    dmp.diff_cleanupSemantic(diff);
    diffs[log.id] = diffToHtml(diff);
});

function expand(event, id) {
    event.preventDefault();
    if (expandedDiff.value.has(id)) {
        expandedDiff.value.delete(id);
    } else {
        expandedDiff.value.add(id);
    }
}

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
                                <tbody v-for="log in logs.data" :key="log.id" class="divide-y divide-gray-200 bg-white">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-800 sm:pl-6">
                                            {{ format(log.created_at, "d.MM.yyyy HH:mm") }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-800">
                                            <span class="text-gray-500 text-sm mr-2">#{{ log.user.id }}</span>
                                            {{ log.user.first_name }} {{ log.user.last_name }} ({{ log.user.nickname }})
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
                                            <a href="#" class="text-blue-500" @click="expand($event, log.id)">
                                                {{ expandedDiff.has(log.id) ? 'collapse' : 'show diff' }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr v-if="expandedDiff.has(log.id)">
                                        <td colspan="6" class="px-3 py-2 text-sm text-gray-800">
                                            <pre v-html="diffs[log.id]" class="whitespace-pre-wrap break-words"></pre>
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
<style>
.diff-insert {
    background-color: #d4fcbc;
}
.diff-delete {
    background-color: #fbb6c2;
}
.diff-equal {
    background-color: #ffffff;
}
</style>
