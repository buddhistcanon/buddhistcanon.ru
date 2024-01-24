<script setup>
import {Head, Link} from '@inertiajs/inertia-vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import LogoTitle from "@/Common/LogoTitle.vue";
import Sidebar from "@/Common/Sidebar.vue";
import {ArrowDownIcon} from '@heroicons/vue/24/outline'
</script>

<template>
    <Head title="Палийский канон"/>

    <ApplicationLayout>

        <div class="flex flex-col md:flex-row">
            <div class="flex flex-col lg:flex-row w-full">
                <div class="bg-white p-4 w-full">

                    <div class="mt-4 text-center font-serif text-4xl">Палийский канон</div>

                    <div class="flex flex-col justify-start items-center">
                        <div class="px-10 mt-6 flex flex-row w-full ">
                            <div class="flex flex-col justify-start items-center mr-4 w-1/3">
                                <div class="font-serif px-4 py-4 w-full text-center"><span
                                    class="text-2xl">Сутта питака</span><br>"Корзина сутт"
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 my-1"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Дигха-никая (DN)</div>
                                <Link class="bc-button px-4 py-3 w-full my-4" href="/mn">Мадджхима-никая (MN)</Link>
                                <Link class="bc-button px-4 py-3 w-full my-4" href="/sn">Самьютта-никая (SN)</Link>
                                <Link class="bc-button px-4 py-3 w-full my-4" href="/an">Ангуттара-никая (AN)</Link>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">Кхудакка-никая (KN)</div>

                            </div>

                            <div class="flex flex-col justify-start items-center mr-4 w-1/3">
                                <!--                                <div class="bc-button-background border-bc rounded-bc text-sm px-4 py-4 w-full">Виная питака<br>"Корзина дисциплины"</div>-->
                                <div class="font-serif px-4 py-4 w-full text-center"><span
                                    class="text-2xl">Виная питака</span><br>"Корзина дисциплины"
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 my-1"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Сутта-вибханга</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">Кхандхака</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">Паривара</div>
                            </div>

                            <div class="flex flex-col justify-start items-center w-1/3">
                                <div class="font-serif px-4 py-4 w-full text-center"><span class="text-2xl">Абхидхамма питака</span><br>"Корзина
                                    Высшей Дхаммы"
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 my-1"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Дхаммасангани</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">Вибханга</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">Дхатукатха</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">Пуггалапаннатти</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">Каттхаватху</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">Ямака</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">Паттхана</div>
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
