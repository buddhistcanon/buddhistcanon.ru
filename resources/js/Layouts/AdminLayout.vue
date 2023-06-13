<script setup>
import { ref } from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { defineProps } from '@vue/runtime-core'
import { Head, Link } from "@inertiajs/inertia-vue3";
import {
    Bars3Icon,
    CalendarIcon,
    ChartBarIcon,
    FolderIcon,
    HomeIcon,
    InboxIcon,
    UsersIcon,
    XMarkIcon,
    BookOpenIcon,
    LifebuoyIcon,
    ArrowRightOnRectangleIcon
} from '@heroicons/vue/24/outline'
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

defineProps({
    title: String,
})

const navigation = [
    { name: 'На сайт', href: '/', icon: HomeIcon, current: false, method: "get" },
    { name: 'MN', href: '/admin/suttas/mn', icon: FolderIcon, current: false, method: "get" },
    { name: 'AN', href: '/admin/suttas/an', icon: FolderIcon, current: false, method: "get" },
    { name: 'SN', href: '/admin/suttas/sn', icon: FolderIcon, current: false, method: "get" },
    { name: 'DN', href: '/admin/suttas/dn', icon: FolderIcon, current: false, method: "get" },
    { name: 'Термины', href: '/admin/terms', icon: BookOpenIcon, current: false, method: "get" },
    { name: 'Помощь', href: '/admin/help', icon: LifebuoyIcon, current: false, method: "get" },
    { name: 'Выход', href: '/logout', icon: ArrowRightOnRectangleIcon, current: false, method: "post" },
]

const sidebarOpen = ref(false)

</script>


<template>

    <div>
        <Head>
            <title>{{title}}</title>
        </Head>
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog as="div" class="relative z-40 lg:hidden" @close="sidebarOpen = false">
                <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" />
                </TransitionChild>

                <div class="fixed inset-0 z-40 flex">
                    <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
                        <DialogPanel class="relative flex w-full max-w-xs flex-1 flex-col bg-white">
                            <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100" leave-to="opacity-0">
                                <div class="absolute top-0 right-0 -mr-12 pt-2">
                                    <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
                                        <span class="sr-only">Close sidebar</span>
                                        <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="h-0 flex-1 overflow-y-auto pt-5 pb-4">
                                <div class="flex flex-shrink-0 items-center px-4">
                                    <ApplicationLogo></ApplicationLogo>
                                </div>
                                <nav class="mt-5 space-y-1 px-2">
                                    <Link v-for="item in navigation" :key="item.name" :href="item.href" :method="item.method" :class="[item.current ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center rounded-md px-2 py-2 text-base font-medium']">
                                        <component :is="item.icon" :class="[item.current ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-4 h-6 w-6 flex-shrink-0']" aria-hidden="true" />
                                        {{ item.name }}
                                    </Link>

                                </nav>
                            </div>
                            <div class="flex flex-shrink-0 border-t border-gray-200 p-4">
                                <a href="#" class="group block flex-shrink-0">
                                    <div class="flex items-center">
                                        <div>
                                            <img class="inline-block h-10 w-10 rounded-full" src="/avatar-placeholder.jpg" alt="" />
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-base font-medium text-gray-700 group-hover:text-gray-900">
                                                {{ $page.props.auth.user.email }}</p>
                                            <p class="text-sm font-medium text-gray-500 group-hover:text-gray-700">View profile</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                    <div class="w-14 flex-shrink-0">
                        <!-- Force sidebar to shrink to fit close icon -->
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex min-h-0 flex-1 flex-col border-r border-gray-200 bg-white">
                <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
                    <div class="flex flex-shrink-0 items-center px-4">
                        Logo
                    </div>
                    <nav class="mt-5 flex-1 space-y-1 bg-white px-2">
                        <Link v-for="item in navigation" :method="item.method" :key="item.name" :href="item.href" :class="[item.current ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center rounded-md px-2 py-2 text-sm font-medium']">
                            <component :is="item.icon" :class="[item.current ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 h-6 w-6 flex-shrink-0']" aria-hidden="true" />
                            {{ item.name }}
                        </Link>
                    </nav>
                </div>
                <div class="flex flex-shrink-0 border-t border-gray-200 p-4">
                    <a href="#" class="group block w-full flex-shrink-0">
                        <div class="flex items-center">
                            <div>
                                <img class="inline-block h-9 w-9 rounded-full" src="/avatar-placeholder.jpg" alt="" />
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">{{$page.props.auth.user.email}}</p>
                                <p class="text-xs font-medium text-gray-500 group-hover:text-gray-700">View profile</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-1 flex-col lg:pl-64">
            <div class="sticky top-0 z-10 bg-gray-100 pl-1 pt-1 sm:pl-3 sm:pt-3 lg:hidden">
                <button type="button" class="-ml-0.5 -mt-0.5 inline-flex h-12 w-12 items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                </button>
            </div>
            <main class="flex-1">
                <div class="py-6">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

