<script setup>
import {Head, Link} from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import LogoTitle from "@/Common/LogoTitle.vue";
import Sidebar from "@/Common/Sidebar.vue";
import {ArrowDownIcon} from '@heroicons/vue/24/outline';
</script>

<template>

    <Head title="Палийский канон"/>

    <ApplicationLayout>
        <div class="flex-1 flex flex-col md:flex-row">
            <div class=" flex-1 flex flex-col lg:flex-row">
                <div class="bg-white p-4 w-full">
                    <div class="mt-4 text-center font-serif text-3xl">Палийский канон</div>
                    <!-- Adjust flex direction for small screens -->
                    <div class="flex flex-col justify-start items-center">
                        <div class="flex flex-col md:flex-row w-full mt-10 px-10">
                            <div class="flex flex-col justify-start items-center mb-6 md:mb-0 md:mr-4 w-full md:w-1/3 pl-10 pr-4">
                                <div class="bc-button px-4 py-4 w-full">
                                    <span>Сутта питака</span><br>“Корзина сутт”
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 mb-1 mt-3"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Дигха-никая (DN)</div>
                                <Link class="bc-button px-4 py-3 w-full my-4" href="/mn">Мадджхима-никая (MN)</Link>
                                <Link class="bc-button px-4 py-3 w-full my-4" href="/sn">Самьютта-никая (SN)</Link>
                                <Link class="bc-button px-4 py-3 w-full my-4" href="/an">Ангуттара-никая (AN)</Link>
                                <Link class="bc-button px-4 py-3 w-full my-4" href="/kn">Кхудакка-никая (KN)</Link>
                            </div>

                            <div class="flex flex-col justify-start items-center mb-6 md:mb-0 md:mr-4 w-full md:w-1/3 px-4">
                                <div class="bc-button px-4 py-4 w-full">
                                    <span>Виная питака</span><br>“Корзина дисциплины”
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 mb-1 mt-3"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">Сутта-вибханга</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">Кхандхака</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">Паривара</div>
                            </div>

                            <div class="flex flex-col justify-start items-center w-full md:w-1/3 px-4 pr-10">
                                <div class="bc-button px-4 py-4 w-full">
                                    <span>Абхидхамма питака</span><br>“Корзина Высшей Дхаммы”
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 mb-1 mt-3"/>
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

            <!-- <div class="md:ml-4 md:w-96"> -->
                <Sidebar/>
            <!-- </div> -->
        </div>

    </ApplicationLayout>

</template>
