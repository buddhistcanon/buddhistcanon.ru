<script setup>
import NavBar from "@/Common/NavBar.vue";

</script>

<template>
    <div class="min-h-screen flex flex-col">
        <NavBar/>
        <slot/>
    </div>
</template>
