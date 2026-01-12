<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import Sidebar from "@/Common/Sidebar.vue";
import Participants from "@/Components/Participants.vue";
import { useTranslation } from '@/composables/useTranslation.js';

const { t } = useTranslation();
</script>

<template>

    <Head :title="t('pages.about.title')" />

    <ApplicationLayout>

        <div class="flex-1 flex flex-col md:flex-row">
            <div class="flex-1 flex flex-col">
                <div class="bg-white py-4 px-12 w-full">

                    <h1 class="py-4">{{ t('about.title') }}</h1>
                    <p class="mb-3">
                        {{ t('about.description') }} <br />
                        {{ t('about.gift') }}
                    </p>

                    <h2 class="py-4">{{ t('about.mission_title') }}</h2>
                    <ul class="bc-list-dash px-4 mb-3">
                        <li>{{ t('about.mission_items.readable_format') }}</li>
                        <li>{{ t('about.mission_items.free') }}</li>
                        <li>{{ t('about.mission_items.translations') }}</li>
                        <li>{{ t('about.mission_items.no_copyright') }}</li>
                        <li>{{ t('about.mission_items.open_license') }}</li>
                        <li>{{ t('about.mission_items.no_affiliation') }}</li>
                        <li>{{ t('about.mission_items.canons_only') }}</li>
                    </ul>

                    <h2 class="py-4">{{ t('about.translations_title') }}</h2>
                    <p class="mb-3">
                        {{ t('about.translations_text') }}
                    </p>

                    <h2 class="py-4">{{ t('about.web_version_title') }}</h2>
                    <ul class="bc-list-dash px-4 mb-3">
                        <li>{{ t('about.web_version_items.easy_edits') }}</li>
                        <li>{{ t('about.web_version_items.disputes') }}</li>
                        <li>{{ t('about.web_version_items.language_comments') }}</li>
                        <li>{{ t('about.web_version_items.collaboration') }}</li>
                        <li>{{ t('about.web_version_items.no_printing') }}</li>
                        <li>{{ t('about.web_version_items.add_materials') }}</li>
                    </ul>

                    <p class="py-4">
                        {{ t('about.contact') }} &nbsp;
                        <a href="https://t.me/BuddhistCanonFoundation" target="_blank"
                            class="bc-button py-1.5 px-3 inline-block">
                            <img src="/icon-telegram.svg" :alt="t('about.telegram')"
                                class="w-6 h-6 min-w-6 inline-block mr-3" /><span>{{ t('about.telegram') }}</span>
                        </a>
                    </p>

                </div>
                <div class="w-full p-4 pb-0">
                    <h3 class="text-center">{{ t('about.participants') }}</h3>
                </div>
                <Participants />
            </div>

            <!-- <div class="md:ml-4 md:w-96"> -->
            <Sidebar />
            <!-- </div> -->

        </div>

    </ApplicationLayout>

</template>
