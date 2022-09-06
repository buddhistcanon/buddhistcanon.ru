<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Bars3Icon, BellIcon, XMarkIcon, ArrowUpIcon, ArrowDownIcon, ChevronDownIcon } from '@heroicons/vue/24/outline'
import { Link } from '@inertiajs/inertia-vue3';

const user = {
    name: 'Имя Фамилия',
    email: 'some-email@gmail.com',
    imageUrl:
        '/avatar-placeholder.jpg',
}
const navigation = [
    { name: 'Дикха-никая', href: '/dn', current: false },
    { name: 'Мадджхима-никая', href: '/mn', current: true },
    { name: 'Ангуттара-никая', href: '/an', current: false },
    { name: 'Саньютта-никая', href: '/sn', current: false },
]
const userNavigation = [
    { name: 'Профиль', href: '/profile', method: "get" },
    { name: 'Настройки', href: '/settings', method: "get" },
    { name: 'Выход', href: '/logout', method: "post" },
]
</script>


<template>
    <div class="min-h-full">
        <Disclosure as="nav" class="bg-white" v-slot="{ open }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <Link href="/">
                                <img class="h-12 w-12" src="/logo-white.png" alt="Logo" />
                            </Link>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-8">
                                <Menu as="div" class="relative inline-block text-left">
                                    <MenuButton class="inline-flex w-full justify-center text-gray-700 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 border border-gray-200 px-3 py-2 rounded-xl text-sm font-medium">
                                        Палийский канон
                                        <ChevronDownIcon
                                            class="ml-2 -mr-1 h-5 w-5 text-gray-600 hover:text-gray-800"
                                            aria-hidden="true"
                                        />
                                    </MenuButton>
                                    <transition
                                        enter-active-class="transition duration-100 ease-out"
                                        enter-from-class="transform scale-95 opacity-0"
                                        enter-to-class="transform scale-100 opacity-100"
                                        leave-active-class="transition duration-75 ease-in"
                                        leave-from-class="transform scale-100 opacity-100"
                                        leave-to-class="transform scale-95 opacity-0"
                                    >
                                    <MenuItems class="absolute right-0 mt-2 w-56 py-1 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <MenuItem v-slot="{ active }" v-for="item in navigation" :key="item.name">
                                            <Link
                                                :class="[ active ? 'bg-blue-50 text-gray-800' : '', 'group flex bg-white text-gray-700 w-full items-center px-2 py-2 text-sm' ]"
                                                :href="item.href"
                                            >
                                                {{ item.name }}
                                            </Link>
                                        </MenuItem>
                                    </MenuItems>
                                    </transition>
                                </Menu>

<!--                                <Link v-for="item in navigation" :key="item.name" :href="item.href" :class="[item.current ? 'bg-gray-200 text-gray-900 border-gray-400 ' : 'text-gray-700 bg-gray-100 hover:bg-gray-200 hover:text-gray-900', 'border border-gray-200 px-3 py-2 rounded-2xl text-sm font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</Link>-->
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="flex flex-row">
                                <div class="border border-gray-200 bg-gray-100 px-3 py-2 rounded-xl text-sm font-medium ml-8 flex flex-row items-center">A <ArrowUpIcon class="h-3 w-3" /> </div>
                                <div class="border border-gray-200 bg-gray-100 px-3 py-2 rounded-xl text-sm font-medium ml-4 flex flex-row items-center">A <ArrowDownIcon class="h-3 w-3" /> </div>
                            </div>
                        </div>
                    </div>
                    <div class="md:hidden flex flex-row">
                        <div class="bg-gray-100 px-3 py-1 rounded-xl text-sm font-medium mr-4 flex flex-row items-baseline">A <ArrowUpIcon class="h-3 w-3" /> </div>
                        <div class="bg-gray-100 px-3 py-1 rounded-xl text-sm font-medium mr-4 flex flex-row items-baseline">A <ArrowDownIcon class="h-3 w-3" /> </div>
                    </div>

                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">

                            <div v-if="!$page.props.auth.user">
                                <Link href="/login" class="mr-4 text-gray-700 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 border border-gray-200 px-3 py-2 rounded-xl text-sm font-medium">
                                    Вход
                                </Link>
                                <Link href="#" class="text-gray-700 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 border border-gray-200 px-3 py-2 rounded-xl text-sm font-medium">
                                    Регистрация
                                </Link>
                            </div>
                            <div v-else>
<!--                            <button type="button" class="rounded-full bg-white p-1 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-white">-->
<!--                                <span class="sr-only">Уведомления</span>-->
<!--                                <BellIcon class="h-6 w-6" aria-hidden="true" />-->
<!--                            </button>-->
                            <!-- Profile dropdown -->
                            <Menu as="div" class="relative ml-3">
                                <div>
                                    <MenuButton class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-white">
                                        <span class="sr-only">Меню пользователя</span>
                                        <img class="h-10 w-10 rounded-full" :src="user.imageUrl" alt="" />
                                    </MenuButton>
                                </div>
                                <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                    <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                                            <Link :href="item.href" :class="[active ? 'bg-blue-50' : '', 'block px-4 py-2 bg-white text-sm text-gray-700']" :method="item.method">{{ item.name }}</Link>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                            </div>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <DisclosureButton class="inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-500 hover:bg-gray-50 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-white">
                            <span class="sr-only">Открыть главное меню</span>
                            <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                            <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
                        </DisclosureButton>
                    </div>
                </div>
            </div>

            <DisclosurePanel class="md:hidden">
                <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                    <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href" :class="[item.current ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800', 'block px-3 py-2 rounded-md text-base font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</DisclosureButton>
                </div>
                <div class="border-t border-gray-400 pt-4 pb-3">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" :src="user.imageUrl" alt="" />
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
                        <DisclosureButton v-for="item in userNavigation" :key="item.name" :as="Link" :method="item.method" :href="item.href" class="block rounded-md px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-700 hover:text-gray-800">{{ item.name }}</DisclosureButton>
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>

<!--        <header class="bg-white shadow">-->
<!--            <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">-->
<!--                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>-->
<!--            </div>-->
<!--        </header>-->
        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-0 lg:px-0">

                <slot />

            </div>
        </main>
    </div>



</template>

