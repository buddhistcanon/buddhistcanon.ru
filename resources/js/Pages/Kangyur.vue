<script setup>
import {Head, Link} from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import LogoTitle from "@/Common/LogoTitle.vue";
import Sidebar from "@/Common/Sidebar.vue";
import {ArrowDownIcon} from '@heroicons/vue/24/outline';
import { useTranslation } from '@/composables/useTranslation.js';

const { t } = useTranslation();
</script>

<template>
    <Head :title="t('pages.kangyur.title')"/>

    <ApplicationLayout>
        <div class="flex flex-col md:flex-row">
            <div class="flex flex-col lg:flex-row w-full">
                <div class="bg-white p-4 w-full">
                    <div class="mt-4 text-center font-serif text-4xl">{{ t('canon.derge_kangyur') }}</div>
                    <!-- Adjust flex direction for small screens -->
                    <div class="flex flex-col justify-start items-center">
                        <div class="flex flex-col md:flex-row w-full mt-6 px-10">
                            <div class="flex flex-col justify-start items-center mb-6 md:mb-0 md:mr-4 w-full md:w-1/3">
                                <div class="font-serif px-4 py-4 w-full text-center">
                                    <span class="text-2xl">{{ t('canon.buddha_discourses') }}</span><br>Toh8 - Toh359
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 my-1"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">{{ t('canon.prajnaparamita') }}</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">{{ t('canon.buddhavatamsaka') }}</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">{{ t('canon.ratnakuta') }}</div>
                                <Link class="bc-button px-4 py-3 w-full my-4" href="/kangyur/general">{{ t('canon.large_collection') }}
                                </Link>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">{{ t('canon.later_translations') }}</div>

                            </div>

                            <div class="flex flex-col justify-start items-center mb-6 md:mb-0 md:mr-4 w-full md:w-1/3">
                                <div class="font-serif px-4 py-4 w-full text-center">
                                    <span class="text-2xl">{{ t('canon.tantra') }}</span><br>Toh360 - Toh845
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 my-1"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">{{ t('canon.tantra_collection') }}</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">{{ t('canon.old_tantras') }}</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">{{ t('canon.kalachakra_commentary') }}
                                </div>
                            </div>

                            <div class="flex flex-col justify-start items-center w-full md:w-1/3">
                                <div class="font-serif px-4 py-4 w-full text-center">
                                    <span class="text-2xl">{{ t('canon.short_sutras') }}</span><br>Toh846 - Toh1108
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 my-1"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">{{ t('canon.main_collection') }}</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">{{ t('canon.dedications') }}</div>

                                <div class="font-serif px-4 py-4 w-full text-center">
                                    <span class="text-2xl">{{ t('canon.vinaya') }}</span><br>Toh1 - Toh7
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 my-1"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">{{ t('canon.monastic_rules') }}</div>

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
