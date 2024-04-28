<script setup>
import {Link} from "@inertiajs/inertia-vue3";
</script>

<template>

    <div class="flex flex-col mt-4">
        <!--    <div class="text-center text-xs text-gray-400 ">-->
        <!--        Все материалы на сайте, общедоступны и на них не распространяется авторское право. Их разрешено свободно воспроизводить в любой форме без разрешения авторов.-->
        <!--        Копировать, размещать на сайтах, в социальных сетях, цитировать, печатать. Это дар нашего фонда для всего человечества.-->
        <!--    </div>-->

        <div class="mt-1 text-center text-xs text-gray-400">
            <Link href="/policy" class="underline">Политика обработки персональных данных</Link>
            и
            <Link href="/user_agreement" class="underline">пользовательское соглашение</Link>
        </div>
    </div>

</template>
