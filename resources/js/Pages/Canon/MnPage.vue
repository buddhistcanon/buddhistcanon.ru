<script setup>
import ApplicationLayout from "@/Layouts/ApplicationLayout.vue";

defineProps({
    suttas: { type: Array, required: true },
})

const suttaUrl = (sutta) => {
    if(sutta.suborder) {
        return `/${sutta.category}${sutta.order}.${sutta.suborder}`;
    } else {
        return `/${sutta.category}${sutta.order}`;
    }
}

</script>

<template>
    <ApplicationLayout>
        <h1 class="text-xl mb-4">Маддхжима никая</h1>
        <div class="">
            <div v-for="sutta in suttas" :key="sutta.id" class="mb-2">
                <a :href="suttaUrl(sutta)" class="link">
                    {{sutta.name}} - {{sutta.title_transcribe_ru}}
                </a>
            </div>
        </div>
    </ApplicationLayout>
</template>
