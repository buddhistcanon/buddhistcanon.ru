<script setup>
import ApplicationLayout from "@/Layouts/ApplicationLayout.vue";
import {Head, Link} from "@inertiajs/inertia-vue3";
import Sidebar from "@/Common/Sidebar.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

const props = defineProps({
    suttas: { type: Array, required: true },
})
console.log(props.suttas);
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
    <Head title="Ангуттара-никая" />

    <ApplicationLayout>

        <div class="flex flex-col md:flex-row">
            <div class="flex flex-col lg:flex-row w-full">
                <div class="bg-white p-4 w-full">

                    <Breadcrumbs :items="[
                        {title: 'Палийский канон', url: '/palicanon'},
                        {title: 'Ангуттара-никая', url: '/an'},
                    ]" class="mb-1"/>

                    <div class="font-serif text-2xl mb-6">AN1 - Екака нипата</div>

                    <div class="grid grid-cols-2 gap-4">
                        <Link v-for="sutta in filteredByOrder(1)" :key="sutta.id" class="bc-button px-4 py-4 mb-2" :href="suttaUrl(sutta)">
                            <div class="text-xl mb-2">{{sutta.name}}</div>
                            <div>{{sutta.title_transcribe_ru}}</div>
                        </Link>
                    </div>



                </div>
            </div>

            <div class="md:ml-4 md:w-96">
                <Sidebar />
            </div>

        </div>

    </ApplicationLayout>
</template>
