<script setup>
import ApplicationLayout from "@/Layouts/ApplicationLayout.vue";
import {Head, Link} from "@inertiajs/inertia-vue3";
import Sidebar from "@/Common/Sidebar.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

const props = defineProps({
    suttas: { type: Array, required: true },
})

const suttaUrl = (sutta) => {
    if(sutta.suborder) {
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
    <Head title="Саньютта-никая" />

    <ApplicationLayout>

        <div class="flex flex-col md:flex-row">
            <div class="flex flex-col lg:flex-row w-full">
                <div class="bg-white p-4 w-full">

                    <Breadcrumbs :items="[
                        {title: 'Палийский канон', url: '/palicanon'},
                    ]" class="mb-1"/>

                    <div class="font-serif text-2xl mb-6">Саньютта-никая</div>



                </div>
            </div>

            <div class="md:ml-4 md:w-96">
                <Sidebar />
            </div>

        </div>

    </ApplicationLayout>
</template>
