<script setup>
import ApplicationLayout from "@/Layouts/ApplicationLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import Sidebar from "@/Common/Sidebar.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

const props = defineProps({
    suttas: {type: Array, required: true},
})

const suttaUrl = (sutta) => {
    if (sutta.suborder) {
        return `/${sutta.category}${sutta.order}.${sutta.suborder}`;
    } else {
        return `/${sutta.category}${sutta.order}`;
    }
}

const filteredByOrder = (order) => {
    return props.suttas.filter(sutta => sutta.order === order);
}

</script>

<template>
    <Head title="Ангуттара-никая"/>

    <ApplicationLayout>

        <div class="flex flex-col lg:flex-row">
            <div class="flex flex-col lg:flex-row w-full">
                <div class="bg-white p-4 w-full">

                    <Breadcrumbs :items="[
                        {title: 'Палийский канон', url: '/palicanon'},
                    ]" class="mb-1"/>

                    <div class="font-serif text-2xl mb-6">Ангуттара-никая</div>


                    <div class="flex flex-col lg:flex-row w-full">
                        <div class="bg-white p-4 w-full">
                            <div class="mt-4 flex flex-col items-center mb-36">
                                <Link class="bc-button-background border-bc mb-8 py-6 w-1/2 text-center rounded-bc"
                                      href="/an/1">
                                    AN1 - Книга единиц
                                </Link>
                                <Link class="mt-4 bc-button-background border-bc mb-8 py-6 w-1/2 text-center rounded-bc"
                                      href="/an/2">
                                    AN2 - Книга двоек
                                </Link>
                                <Link class="mt-4 bc-button-background border-bc mb-8 py-6 w-1/2 text-center rounded-bc"
                                      href="/an/3">
                                    AN3 - Книга троек
                                </Link>
                                <Link class="mt-4 bc-button-background border-bc mb-8 py-6 w-1/2 text-center rounded-bc"
                                      href="/an/4">
                                    AN4 - Книга четвёрок
                                </Link>
                                <Link class="mt-4 bc-button-background border-bc mb-8 py-6 w-1/2 text-center rounded-bc"
                                      href="/an/5">
                                    AN5 - Книга пятёрок
                                </Link>
                                <Link class="mt-4 bc-button-background border-bc mb-8 py-6 w-1/2 text-center rounded-bc"
                                      href="/an/6">
                                    AN6 - Книга шестёрок
                                </Link>
                                <Link class="mt-4 bc-button-background border-bc mb-8 py-6 w-1/2 text-center rounded-bc"
                                      href="/an/7">
                                    AN7 - Книга семёрок
                                </Link>
                                <Link class="mt-4 bc-button-background border-bc mb-8 py-6 w-1/2 text-center rounded-bc"
                                      href="/an/8">
                                    AN8 - Книга восьмёрок
                                </Link>
                                <Link class="mt-4 bc-button-background border-bc mb-8 py-6 w-1/2 text-center rounded-bc"
                                      href="/an/9">
                                    AN9 - Книга девяток
                                </Link>
                                <Link class="mt-4 bc-button-background border-bc mb-8 py-6 w-1/2 text-center rounded-bc"
                                      href="/an/10">
                                    AN10 - Книга десяток
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
