<script setup>

import {Link} from "@inertiajs/inertia-vue3";
import LogoTitle from "@/Common/LogoTitle.vue";
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="py-8">
            <LogoTitle />
        </div>

        <Link href="/news" class="bc-button py-2 text-center w-full my-4">Новое на сайте</Link>

        <Link href="/about" class="bc-button py-2 text-center w-full my-4">О Фонде</Link>

        <Link href="/bookmarks" class="bc-button py-2 text-center w-full my-4">Мои закладки</Link>


    </div>
</template>
