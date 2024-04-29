<script setup>
import ApplicationLayout from "@/Layouts/ApplicationLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import Sidebar from "@/Common/Sidebar.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

const props = defineProps({
    suttas: {type: Array, required: true},
})

const suttaUrl = (sutta) => {
    if (sutta.suborder) {
        return `/${sutta.category}${sutta.order}.${sutta.suborder}`;
    } else {
        return `/${sutta.category}${sutta.order}`;
    }
}

const filteredByOrder = (order) => {
    return props.suttas.filter(sutta => sutta.order === order);
}

</script>

<template>
    <Head title="Саньютта-никая"/>

    <ApplicationLayout>

        <div class="flex flex-col md:flex-row">
            <div class="flex flex-col lg:flex-row w-full">
                <div class="bg-white p-4 w-full">

                    <Breadcrumbs :items="[
                        {title: 'Палийский канон', url: '/palicanon'},
                    ]" class="mb-1"/>

                    <div class="font-serif text-2xl">Саньютта-никая</div>

                    <div class="font-serif text-xl mt-6 mb-6">1. Строфы</div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/1">SN1: Дэвата саньютта - Дэвы</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/2">SN2: Дэвапутта саньютта - Молодые дэвы
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/3">SN3: Косала саньютта - Косалы</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/4">SN4: Мара саньютта - Мара</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/5">SN5: Бхиккхуни саньютта - Монахини</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/6">SN6: Брахма саньютта - Брахмы</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/7">SN7: Брахмана саньютта - Брахманы</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/8">SN8: Вангиса саньютта - Вангиса</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/9">SN9: Вана саньютта - Лес</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/10">SN10: Яккха саньютта - Яккхи</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/11">SN11: Сакка саньютта - Сакка</Link>
                    </div>

                    <div class="font-serif text-xl mt-10 mb-6">2. Причинность</div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/12">SN12: Нидана саньютта - Причинность</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/13">SN13: Абхисамая саньютта - Постижение
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/14">SN14: Дхату саньютта - Элементы</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/15">SN15: Анаматагга саньютта - Без постижимого
                            начала
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/16">SN16: Кассапа саньютта - Кассапа</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/17">SN17: Лабхасаккара саньютта - Приобретения
                            и похвала
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/18">SN18: Рахула саньютта - Рахула</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/19">SN19: Лаккхана саньютта - Лаккхана</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/20">SN20: Опамма саньютта - Метафоры</Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/21">SN21: Бхиккху саньютта - Монахи</Link>
                    </div>

                    <div class="font-serif text-xl mt-10 mb-6">3. Совокупности</div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/22">SN22: Кхандха саньютта - Совокупности
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/23">SN23: Радха саньютта - Радха
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/24">SN24: Диттхи саньютта - Воззрения
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/25">SN25: Окканти саньютта - Вступление
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/26">SN26: Уппада саньютта - Возникновение
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/27">SN27: Килеса саньютта - Загрязнения
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/28">SN28: Сарипутта саньютта - Сарипутта
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/29">SN29: Нага саньютта - Наги
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/30">SN30: Супанна саньютта - Супанны
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/31">SN31: Гандхабба саньютта - Гандхаббы
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/32">SN32: Валахака саньютта - Дэвы облаков
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/33">SN33: Ваччхаготта саньютта - Ваччхаготта
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/34">SN34: Самадхи саньютта - Сосредоточение
                        </Link>
                    </div>

                    <div class="font-serif text-xl mt-10 mb-6">4. Шесть сфер</div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/35">SN35: Салаятана саньютта - Шесть сфер
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/36">SN36: Ведана саньютта - Чувство
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/37">SN37: Матугама саньютта - Женщины
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/38">SN38: Джамбукхадака саньютта -
                            Джамбукхадака
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/39">SN39: Самандака саньютта - Самандака
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/40">SN40: Моггаллана саньютта - Моггаллана
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/41">SN41: Читта саньютта - Домохозяин Читта
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/42">SN42: Гамани саньютта - Начальник
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/43">SN43: Асанкхата саньютта - Необусловленное
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/44">SN44: Абьяката саньютта - Не-утверждённое
                        </Link>
                    </div>

                    <div class="font-serif text-xl mt-10 mb-6">5. Большой раздел</div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/45">СН 45: Магга саньютта - Путь
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/46">СН 46: Бодджханга саньютта - Факторы
                            просветления
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/47">СН 47: Сатипаттхана саньютта - Основы
                            осознанности
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/48">СН 48: Индрия саньютта - Качества
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/49">СН 49: Саммаппадхана саньютта - Правильные
                            усилия
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/50">СН 50: Бала саньютта - Силы
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/51">СН 51: Иддхипада саньютта - Основы
                            сверхъестественных сил
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/52">СН 52: Ануруддха саньютта - Ануруддха
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/53">СН 53: Джхана саньютта - Джханы
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/54">СН 54: Анапана саньютта - Дыхание
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/55">СН 55: Сотапатти саньютта - Вступление в
                            поток
                        </Link>
                        <Link class="bc-button px-4 py-4 mb-2" href="/sn/56">СН 56: Сачча саньютта - Истины
                        </Link>
                    </div>

                </div>
            </div>

            <div class="md:ml-4 md:w-96">
                <Sidebar/>
            </div>

        </div>

    </ApplicationLayout>
</template>
