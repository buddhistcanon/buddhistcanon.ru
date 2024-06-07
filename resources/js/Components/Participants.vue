<script setup>

const rowClass = 'flex flex-row mt-4';
const leftColClass = 'bg-white mr-4 flex-1 flex flex-col md:flex-row';
const rightColClass = 'bg-white ml-4 flex-1 flex flex-col md:flex-row';
const nameCardClass = 'text-xs flex-1 p-4 text-center md:text-left md:pl-12 md:pr-0 md:my-auto';
const nameClass = 'font-bold';
const socialCardClass = 'flex-1 flex flex-row justify-center px-4 mb-6 md:my-6 md:justify-around';
const linkClass = 'bc-button mx-1 py-1.5 px-2 block my-auto';
const iconClass = 'w-6 h-6 min-w-6';

</script>

<template>
    <div class="flex flex-col">
        <div :class="rowClass">
            <div :class="leftColClass">
                <div :class="nameCardClass  ">
                    <div :class="nameClass">
                        Игорь Васильев (Нара Лока)
                    </div>
                    <div>ведущий дизайнер и инициатор создания фонда</div>
                </div>
                <div :class="socialCardClass">
                    <a href="https://www.youtube.com/naraloka" target="_blank" :class="linkClass">
                        <img src="/icon-youtube.svg" alt="youtube" :class="iconClass" />
                    </a>

                    <a href="https://t.me/nara_loka" target="_blank" :class="linkClass">
                        <img src="/icon-telegram.svg" alt="telegram" :class="iconClass" />
                    </a>

                    <a href="https://vk.com/naraloka" target="_blank" :class="linkClass">
                        <img src="/icon-vk.svg" alt="vk" :class="iconClass" />
                    </a>
                </div>
            </div>

            <div :class="rightColClass">
                <div :class="nameCardClass  ">
                    <div :class="nameClass">
                        Михаил Парфёнов
                    </div>
                    <div>ведущий программист</div>
                </div>
                <div :class="socialCardClass">
                </div>
            </div>
        </div>

        <div :class="rowClass">
            <div :class="leftColClass">
                <div :class="nameCardClass  ">
                    <div :class="nameClass">
                        Олег Павлов
                    </div>
                    <div></div>
                </div>
                <div :class="socialCardClass">
                    <a href="https://www.youtube.com/@kalyanadhamma1292" target="_blank" :class="linkClass">
                        <img src="/icon-youtube.svg" alt="youtube" :class="iconClass" />
                    </a>

                    <a href="https://vk.com/id16948620" target="_blank" :class="linkClass">
                        <img src="/icon-vk.svg" alt="vk" :class="iconClass" />
                    </a>

                    <a href="#" target="_blank" :class="linkClass" style="visibility:hidden">
                        <img src="/icon-telegram.svg" alt="telegram" :class="iconClass" />
                    </a>
                </div>
            </div>
            <div :class="rightColClass">
                <div :class="nameCardClass  ">
                    <div :class="nameClass">
                        Борис Гроздев
                    </div>
                    <div>программист</div>
                </div>
                <div :class="socialCardClass">
                </div>
            </div>
        </div>

        <div :class="rowClass">
            <div :class="leftColClass">
                <div :class="nameCardClass  ">
                    <div :class="nameClass">
                        Миямото Мусаси
                    </div>
                    <div></div>
                </div>
                <div :class="socialCardClass">
                    <a href="https://t.me/BuddhismMahayanaChat" target="_blank" :class="linkClass">
                        <img src="/icon-telegram.svg" alt="telegram" :class="iconClass" />
                    </a>

                    <a href="#" target="_blank" :class="linkClass" style="visibility:hidden">
                        <img src="/icon-youtube.svg" alt="youtube" :class="iconClass" />
                    </a>

                    <a href="#" target="_blank" :class="linkClass" style="visibility:hidden">
                        <img src="/icon-vk.svg" alt="vk" :class="iconClass" />
                    </a>
                </div>
            </div>
            <div :class="rightColClass">
                <div :class="nameCardClass  ">
                    <div :class="nameClass">
                        Влад Аскинази
                    </div>
                    <div></div>
                </div>
                <div :class="socialCardClass">
                    <a href="https://www.youtube.com/@vladaskinazi2715" target="_blank" :class="linkClass">
                        <img src="/icon-youtube.svg" alt="youtube" :class="iconClass" />
                    </a>

                    <a href="https://t.me/askinazi" target="_blank" :class="linkClass">
                        <img src="/icon-telegram.svg" alt="telegram" :class="iconClass" />
                    </a>

                    <a href="https://vk.com/id230002101" target="_blank" :class="linkClass">
                        <img src="/icon-vk.svg" alt="vk" :class="iconClass" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
