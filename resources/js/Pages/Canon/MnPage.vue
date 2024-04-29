<script setup>
import ApplicationLayout from "@/Layouts/ApplicationLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import Sidebar from "@/Common/Sidebar.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

defineProps({
    suttas: {type: Array, required: true},
})

const suttaUrl = (sutta) => {
    if (sutta.suborder) {
        return `/${sutta.category}${sutta.order}.${sutta.suborder}`;
    } else {
        return `/${sutta.category}${sutta.order}`;
    }
}

</script>

<template>
    <Head title="Мадджхима-никая"/>

    <ApplicationLayout>

        <div class="flex flex-col md:flex-row">
            <div class="flex flex-col lg:flex-row w-full">
                <div class="bg-white p-4 w-full">

                    <Breadcrumbs :items="[
                        {title: 'Палийский канон', url: '/palicanon'},
                    ]" class="mb-1"/>

                    <div class="font-serif text-2xl mb-6">Мадджхима-никая</div>

                    <div class="grid grid-cols-2 gap-4">
                        <Link v-for="sutta in suttas" :key="sutta.id" class="bc-button px-4 py-4 mb-2"
                              :href="suttaUrl(sutta)">
                            <div class="text-xl mb-2">{{sutta.name}}</div>
                            <div>{{sutta.title_transcribe_ru}}</div>
                        </Link>
                    </div>


                </div>
            </div>

            <div class="md:ml-4 md:w-96">
                <Sidebar/>
            </div>

        </div>

    </ApplicationLayout>
</template>
