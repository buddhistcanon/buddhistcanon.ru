<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems} from '@headlessui/vue'
import {
    Bars3Icon,
    BellIcon,
    XMarkIcon,
    ArrowUpIcon,
    ArrowDownIcon,
    ChevronDownIcon,
    MagnifyingGlassIcon
} from '@heroicons/vue/24/outline'
import {Link, usePage} from '@inertiajs/vue3';
import Sidebar from "@/Common/Sidebar.vue";
import Footer from "@/Common/Footer.vue";
import CookieBanner from "@/Common/CookieBanner.vue";
import {router} from '@inertiajs/vue3'
import {ref} from 'vue'

const page = usePage();

const user = {
    name: '',
    email: page?.props?.auth?.user?.email ? page.props.auth.user.email : '',
    imageUrl:
        '/avatar-placeholder.jpg',
}
const navigation = [
    {name: 'Дикха-никая', href: '/dn', current: false},
    {name: 'Мадджхима-никая', href: '/mn', current: true},
    {name: 'Ангуттара-никая', href: '/an', current: false},
    {name: 'Саньютта-никая', href: '/sn', current: false},
]
const userNavigation = [
    ...((page?.props?.auth?.user?.is_superadmin || page?.props?.auth?.roles?.length)
        ? [{name: 'Admin area', href: '/admin/suttas/mn', method: "get"}]
        : []
    ),
    {name: 'Профиль', href: '/profile', method: "get"},
    {name: 'Выход', href: '/logout', method: "post"},
]

</script>


<template>
    <div class="min-h-full">
        <Disclosure as="nav" class="bg-white" v-slot="{ open }">
            <div class="mx-auto custom-max-w-8xl px-4 sm:px-6 lg:px-8">
                <!-- Desktop header -->
                <div class="hidden md:block">
                    <div class="h-16 flex items-center">

                        <Link class="bc-button px-7 py-2" :href="'/'">
                            Главная страница
                        </Link>

                        <Link :href="'/search'" class="relative ml-8 flex-1 border-1 border-bc rounded-bc">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <MagnifyingGlassIcon class="h-5 w-5 text-gray-bc" aria-hidden="true"/>
                            </div>
                            <div
                                class="block text-gray-600 w-full rounded-bc bc-button-background border-0 py-2 pl-10 text-gray-900 ring-0 ring-inset ring-gray-bc placeholder:text-gray-400 focus:ring-2 focus:ring-offset-0 focus:ring-gray-200 text-sm"
                            >Поиск
                            </div>
                        </Link>

                        <div v-if="!$page.props.auth.user">
                            <Link class="bc-button px-7 py-2 ml-8" :href="'/login'">
                                Вход / Регистрация
                            </Link>
                        </div>
                        <div v-else>
                            <Menu as="div" class="relative ml-3">
                                <div>
                                    <MenuButton
                                        class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-white">
                                        <span class="sr-only">Меню пользователя</span>
                                        <img class="h-10 w-10 rounded-full" :src="user.imageUrl" alt=""/>
                                    </MenuButton>
                                </div>
                                <transition enter-active-class="transition ease-out duration-100"
                                            enter-from-class="transform opacity-0 scale-95"
                                            enter-to-class="transform opacity-100 scale-100"
                                            leave-active-class="transition ease-in duration-75"
                                            leave-from-class="transform opacity-100 scale-100"
                                            leave-to-class="transform opacity-0 scale-95">
                                    <MenuItems
                                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                                            <Link :href="item.href"
                                                  :class="[active ? 'bg-blue-50' : '', 'block px-4 py-2 bg-white text-sm text-gray-700']"
                                                  :method="item.method">{{ item.name }}
                                            </Link>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>


                    </div>


                </div> <!-- /Desktop header -->

                <!-- Mobile header -->
                <div class="md:hidden h-16 flex items-center justify-between">
                    <div class="flex-shrink-0">
                        <Link href="/">
                            <img class="h-12 w-12" src="/logo-white.png" alt="Logo"/>
                        </Link>
                    </div>

                    <div class="flex flex-row">
                        <!--                        <div class="bg-gray-100 px-3 py-1 rounded-xl text-sm font-medium mr-4 flex flex-row items-baseline">A <ArrowUpIcon class="h-3 w-3" /> </div>-->
                        <!--                        <div class="bg-gray-100 px-3 py-1 rounded-xl text-sm font-medium mr-4 flex flex-row items-baseline">A <ArrowDownIcon class="h-3 w-3" /> </div>-->
                        <div class="relative ml-2 flex-1 border-1 border-bc rounded-bc">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <MagnifyingGlassIcon class="h-5 w-5 text-gray-bc" aria-hidden="true"/>
                            </div>
                            <input type="text" name="search" id="search"
                                   class="block w-full rounded-bc bc-button-background border-0 py-2 pl-10 text-gray-900 ring-0 ring-inset ring-gray-bc placeholder:text-gray-400 focus:ring-2 focus:ring-offset-0 focus:ring-gray-200 text-sm"
                                   v-model="searchString"
                                   @keyup.enter="submitSearch"
                                   placeholder="Поиск"/>
                        </div>
                    </div>

                    <div class="-mr-2 flex">
                        <!-- Mobile menu button -->
                        <DisclosureButton
                            class="inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-500 hover:bg-gray-50 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-white">
                            <span class="sr-only">Открыть главное меню</span>
                            <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true"/>
                            <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true"/>
                        </DisclosureButton>
                    </div>

                </div> <!-- /Mobile header -->


            </div>

            <DisclosurePanel class="md:hidden">
                <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                    <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href"
                                      :class="[item.current ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800', 'block px-3 py-2 rounded-md text-base font-medium']"
                                      :aria-current="item.current ? 'page' : undefined">{{ item.name }}
                    </DisclosureButton>
                </div>
                <div class="border-t border-gray-400 pt-4 pb-3">
                    <div v-if="!$page.props.auth.user">
                        <DisclosureButton :as="Link" :method="get" :href="'/login'"
                                          class="block rounded-md px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-700">
                            Вход
                        </DisclosureButton>
                    </div>
                    <div v-else>
                        <div class="flex items-center px-5">
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full" :src="user.imageUrl" alt=""/>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium leading-none text-gray-600 mb-2">{{ user.name }}</div>
                                <div class="text-sm font-medium leading-none text-gray-500">{{ user.email }}</div>
                            </div>
                            <!--                        <button type="button" class="ml-auto flex-shrink-0 rounded-full bg-white p-1 text-gray-600 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-white">-->
                            <!--                            <span class="sr-only">Уведомления</span>-->
                            <!--                            <BellIcon class="h-6 w-6" aria-hidden="true" />-->
                            <!--                        </button>-->
                        </div>
                        <div class="mt-3 space-y-1 px-2">
                            <DisclosureButton v-for="item in userNavigation" :key="item.name" :as="Link"
                                              :method="item.method" :href="item.href"
                                              class="block rounded-md px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-700">
                                {{ item.name }}
                            </DisclosureButton>
                        </div>
                    </div>

                </div>
            </DisclosurePanel>
        </Disclosure>

        <main>
            <div class="mx-auto custom-max-w-8xl py-2 sm:px-6 lg:px-8">

                <slot/>

                <div class="flex">
                    <div class="flex flex-col lg:flex-row w-full">
                        <Footer/>
                    </div>

                    <div class="lg:ml-4 lg:w-96">

                    </div>

                </div>

            </div>


        </main>
    </div>

    <CookieBanner />


</template>

