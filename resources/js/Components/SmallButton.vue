<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type"
            class="inline-flex items-center px-2 py-1 text-gray-800 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition ease-in-out duration-150">
        <slot/>
    </button>
</template>
