<script setup>
import {Head, Link} from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import LogoTitle from "@/Common/LogoTitle.vue";
import Sidebar from "@/Common/Sidebar.vue";
import {ArrowDownIcon} from '@heroicons/vue/24/outline';
</script>

<template>
    <Head title="Кангьюр - тибетский канон"/>

    <ApplicationLayout>
        <div class="flex flex-col md:flex-row">
            <div class="flex flex-col lg:flex-row w-full">
                <div class="bg-white p-4 w-full">
                    <div class="mt-4 text-center font-serif text-4xl">Дерге Кангьюр</div>
                    <!-- Adjust flex direction for small screens -->
                    <div class="flex flex-col justify-start items-center">
                        <div class="flex flex-col md:flex-row w-full mt-6 px-10">
                            <div class="flex flex-col justify-start items-center mb-6 md:mb-0 md:mr-4 w-full md:w-1/3">
                                <div class="font-serif px-4 py-4 w-full text-center">
                                    <span class="text-2xl">Беседы Будды</span><br>Toh8 - Toh359
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 my-1"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Праджняпарамита</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Буддаватамсака</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Ратнакута</div>
                                <Link class="bc-button px-4 py-3 w-full my-4" href="/kangyur/general">Большая коллекция
                                    разных сутр
                                </Link>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Сутры, переведённые позднее</div>

                            </div>

                            <div class="flex flex-col justify-start items-center mb-6 md:mb-0 md:mr-4 w-full md:w-1/3">
                                <div class="font-serif px-4 py-4 w-full text-center">
                                    <span class="text-2xl">Тантра</span><br>Toh360 - Toh845
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 my-1"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Сборник тантр</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Старые тантры</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Комментарий на Калачакра тантру
                                </div>
                            </div>

                            <div class="flex flex-col justify-start items-center w-full md:w-1/3">
                                <div class="font-serif px-4 py-4 w-full text-center">
                                    <span class="text-2xl">Короткие сутры</span><br>Toh846 - Toh1108
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 my-1"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Основное собрание</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Посвящения</div>

                                <div class="font-serif px-4 py-4 w-full text-center">
                                    <span class="text-2xl">Виная</span><br>Toh1 - Toh7
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 my-1"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Монашеские правила</div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:ml-4 md:w-96">
                <Sidebar/>
            </div>
        </div>
    </ApplicationLayout>
</template>
