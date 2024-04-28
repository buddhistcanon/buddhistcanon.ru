<script setup>
import {Head, Link} from '@inertiajs/inertia-vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import Sidebar from "@/Common/Sidebar.vue";
</script>

<template>
    <Head title="Новости"/>

    <ApplicationLayout>

        <div class="flex flex-col md:flex-row">
            <div class="flex flex-col lg:flex-row w-full">
                <div class="bg-white p-4 w-full">

                    <div class="mt-4 mb-4 font-serif text-4xl">Новое на сайте</div>

                    <div class="text-gray-500 text-sm mb-1">19 ноября 2023г.</div>
                    <div class="mb-6">Импортированы сутты MN из suttacentral.net и theravada.ru</div>

                    <div class="text-gray-500 text-sm mb-1">23 марта 2024г.</div>
                    <div class="mb-6">Импортированы сутты AN и SN из suttacentral.net и theravada.ru</div>

                </div>
            </div>

            <div class="md:ml-4 md:w-96">
                <Sidebar/>
            </div>

        </div>


    </ApplicationLayout>
</template>
