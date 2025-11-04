<script setup>
import ApplicationLayout from "@/Layouts/ApplicationLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import Sidebar from "@/Common/Sidebar.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

</script>

<template>
    <Head title="Кхудаккха-никая"/>

    <ApplicationLayout>

        <div class="flex flex-col lg:flex-row">
            <div class="flex flex-col lg:flex-row w-full">
                <div class="bg-white p-4 w-full">

                    <Breadcrumbs :items="[
                        {title: 'Палийский канон', url: '/palicanon'},
                    ]" class="mb-1"/>

                    <div class="font-serif text-2xl mb-6">Кхудаккха-никая</div>


                    <div class="flex flex-col lg:flex-row w-full">
                        <div class="bg-white p-4 w-full">
                            <div class="mt-4 flex flex-col items-center mb-36">
                                <Link class="mb-8 py-6 w-1/2 text-center bc-button-background bc-border bc-rounded"
                                      href="/dhp">
                                    Дхаммапада
                                </Link>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="lg:ml-4 lg:w-96">
                <Sidebar/>
            </div>

        </div>

    </ApplicationLayout>
</template>
