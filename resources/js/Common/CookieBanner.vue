<script setup>
import { ref, onMounted } from "vue";

const consentCookieName = 'cookie_consent';
const consent = ref(true);

function setCookie() {
    document.cookie = `${consentCookieName}=true; expires=Fri, 31 Dec 9999 23:59:59 GMT`;
    consent.value = true;
}

onMounted(() => {
    consent.value = document.cookie.includes(`${consentCookieName}=true`);
})

</script>

<template>
    <transition name="slide">
        <div v-if="!consent" class="fixed bottom-0 w-full py-4 px-4 md:px-10 text-center text-white bg-gray-800 flex flex-col md:flex-row">
            <div>
                Мы используем файлы cookie для повышения удобства работы с сайтом.
                Продолжая пользоваться сайтом, вы соглашаетесь с их использованием.
                Подробную информацию о файлах cookie можно найти
                <a href="https://www.kaspersky.ru/resource-center/definitions/cookies" target="_blank" class="underline">здесь</a>.
            </div>
            <div>
                <button @click="setCookie" class="bg-white text-gray-800 px-4 py-2 rounded-lg ml-4">
                    Принять
                </button>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.slide-enter-active, .slide-leave-active {
    transition: transform 0.5s ease;
}
.slide-enter-from, .slide-leave-to {
    transform: translateY(100%);
}
.slide-enter-to, .slide-leave-from {
    transform: translateY(0%);
}
</style>
