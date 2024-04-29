<script setup>
import {Head, Link} from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import LogoTitle from "@/Common/LogoTitle.vue";
import Sidebar from "@/Common/Sidebar.vue";
</script>

<template>
    <Head title="Главная страница"/>

    <ApplicationLayout>

        <div class="flex flex-col md:flex-row">
            <div class="flex flex-col lg:flex-row w-full">
                <div class="bg-white p-4 w-full">
                    <div class="mt-36 flex flex-col items-center mb-36">
                        <Link class="bc-button-background border-bc mb-8 py-6 w-1/2 text-center rounded-bc"
                              href="/palicanon">
                            Читать Палийский канон
                        </Link>

                        <div
                            class="mt-4 text-gray-400 bc-button-background border-bc mb-8 py-6 w-1/2 text-center rounded-bc">
                            Читать Тибетский канон
                        </div>

                        <div
                            class="mt-4 text-gray-400 bc-button-background border-bc mb-8 py-6 w-1/2 text-center rounded-bc">
                            Читать Китайский канон
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:ml-4 md:w-96">
                <Sidebar/>
            </div>

        </div>


    </ApplicationLayout>
</template>
