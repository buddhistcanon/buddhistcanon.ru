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

    <Head :title="t('pages.palicanon.title')"/>

    <ApplicationLayout>
        <div class="flex-1 flex flex-col md:flex-row">
            <div class=" flex-1 flex flex-col lg:flex-row">
                <div class="bg-white p-4 w-full">
                    <div class="mt-4 text-center font-serif text-3xl">{{ t('canon.palicanon') }}</div>
                    <!-- Adjust flex direction for small screens -->
                    <div class="flex flex-col justify-start items-center">
                        <div class="flex flex-col md:flex-row w-full mt-10 px-10">
                            <div class="flex flex-col justify-start items-center mb-6 md:mb-0 md:mr-4 w-full md:w-1/3 pl-10 pr-4">
                                <div class="bc-button px-4 py-4 w-full">
                                    <span>{{ t('canon.sutta_pitaka') }}</span><br>{{ t('canon.sutta_pitaka_desc') }}
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 mb-1 mt-3"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">{{ t('canon.dn') }}</div>
                                <Link class="bc-button px-4 py-3 w-full my-4" href="/mn">{{ t('canon.mn') }}</Link>
                                <Link class="bc-button px-4 py-3 w-full my-4" href="/sn">{{ t('canon.sn') }}</Link>
                                <Link class="bc-button px-4 py-3 w-full my-4" href="/an">{{ t('canon.an') }}</Link>
                                <Link class="bc-button px-4 py-3 w-full my-4" href="/kn">{{ t('canon.kn') }}</Link>
                            </div>

                            <div class="flex flex-col justify-start items-center mb-6 md:mb-0 md:mr-4 w-full md:w-1/3 px-4">
                                <div class="bc-button px-4 py-4 w-full">
                                    <span>{{ t('canon.vinaya_pitaka') }}</span><br>{{ t('canon.vinaya_pitaka_desc') }}
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 mb-1 mt-3"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">{{ t('canon.sutta_vibhanga') }}</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">{{ t('canon.khandhaka') }}</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">{{ t('canon.parivara') }}</div>
                            </div>

                            <div class="flex flex-col justify-start items-center w-full md:w-1/3 px-4 pr-10">
                                <div class="bc-button px-4 py-4 w-full">
                                    <span>{{ t('canon.abhidhamma_pitaka') }}</span><br>{{ t('canon.abhidhamma_pitaka_desc') }}
                                </div>
                                <div>
                                    <ArrowDownIcon class="w-6 h-6 text-gray-300 mb-1 mt-3"/>
                                </div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-2">{{ t('canon.dhammasangani') }}</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">{{ t('canon.vibhanga') }}</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">{{ t('canon.dhatukatha') }}</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">{{ t('canon.puggalapannatti') }}</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">{{ t('canon.kathavatthu') }}</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">{{ t('canon.yamaka') }}</div>
                                <div class="bc-button-disabled px-4 py-3 w-full my-4">{{ t('canon.patthana') }}</div>
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
