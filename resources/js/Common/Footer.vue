<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>

    <div class="flex-1 flex flex-col my-5">
        <div class="text-center text-xs text-gray-500">
            Все материалы на сайте, общедоступны и на них не распространяется авторское право. В некоммерческих целях их
            разрешено свободно воспроизводить в любой форме без разрешения авторов.
            Копировать, размещать на сайтах, в социальных сетях, цитировать, печатать. Это дар нашего фонда для всего
            человечества.
        </div>
        <div class="text-center text-xs text-gray-400">
            <Link href="/policy" class="no-underline">Политика обработки персональных данных</Link>
            <span class="text-gray-500"> и </span>
            <Link href="/user_agreement" class="no-underline">пользовательское соглашение</Link>
        </div>
    </div>

</template>
