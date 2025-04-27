<script setup>
import {defineProps, defineEmits, ref, watch} from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true
    },
    translators: Array,
    error: String
});

let listTranslators = [
    {
        'id': 0,
        'name': "-- создание нового переводчика --"
    },
]
props.translators.forEach(translator => {
    listTranslators.push({
        'id': translator.id,
        'name': translator.name
    })
})
console.log(listTranslators);
const selectedTranslator = ref('0');

const emit = defineEmits(['update:modelValue']);

const updateLang = (value) => {
    const modelValue = {
        ...props.modelValue,
        lang: value
    };
    emit('update:modelValue', modelValue);
};

const updateTranslatorId = (value) => {
    selectedTranslator.value = value;
    const modelValue = {
        ...props.modelValue,
        translator_id: value
    };
    emit('update:modelValue', modelValue);
};

const updateTranslator = (field, value) => {
    const modelValue = {
        ...props.modelValue,
        translator: {
            ...props.modelValue.translator,
            [field]: value
        }
    };
    emit('update:modelValue', modelValue);
};

const updateContent = (field, value) => {
    const modelValue = {
        ...props.modelValue,
        [field]: value
    };
    emit('update:modelValue', modelValue);
};
</script>

<template>
    <div class="flex flex-col space-y-4">
        <!-- Выбор языка -->
        <div class="flex flex-col">
            <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Язык контента</label>
            <div class="flex flex-row items-center">
                <div class="flex gap-4">
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="content_lang"
                            value="pali"
                            :checked="modelValue.lang === 'pali'"
                            @change="updateLang('pali')"
                            class="form-radio"
                        >
                        <span class="ml-2">Пали</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="content_lang"
                            value="bo"
                            :checked="modelValue.lang === 'bo'"
                            @change="updateLang('bo')"
                            class="form-radio"
                        >
                        <span class="ml-2">Тибетский</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="content_lang"
                            value="en"
                            :checked="modelValue.lang === 'en'"
                            @change="updateLang('en')"
                            class="form-radio"
                        >
                        <span class="ml-2">Английский</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="content_lang"
                            value="ru"
                            :checked="modelValue.lang === 'ru'"
                            @change="updateLang('ru')"
                            class="form-radio"
                        >
                        <span class="ml-2">Русский</span>
                    </label>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Краткое описание</label>
                <input
                    type="text"
                    :value="modelValue.short_description || ''"
                    @input="updateContent('short_description', $event.target.value)"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6"
                />
                <p class="mt-1 text-sm text-gray-500">Например, "Перевод с английского перевода"</p>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Ссылка, если он есть в
                    интернете</label>
                <input
                    type="text"
                    :value="modelValue.link_url || ''"
                    @input="updateContent('link_url', $event.target.value)"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6"
                />
            </div>
        </div>

        <div class="flex flex-col border-t pt-2 mt-4">
            <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Переводчик</label>
            <select
                :value="modelValue.translator_id"
                @input="updateTranslatorId($event.target.value)"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6"
            >
                <option v-for="translator in listTranslators" :value="translator.id">{{ translator.name }}</option>
            </select>
        </div>

        <!-- Данные переводчика (только если это не оригинал) -->
        <div class="flex flex-col space-y-4" v-if="selectedTranslator === '0'">
            <div>
                <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Подпись переводчика</label>
                <input
                    type="text"
                    :value="modelValue.translator?.signature || ''"
                    @input="updateTranslator('signature', $event.target.value)"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6"
                />
                <p class="mt-1 text-sm text-gray-500">Например, "Сергей SV"</p>
            </div>


            <div>
                <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Slug переводчика
                    (латиницей)</label>
                <input
                    type="text"
                    :value="modelValue.translator?.slug || ''"
                    @input="updateTranslator('slug', $event.target.value)"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6"
                />
                <p class="mt-1 text-sm text-gray-500">Короткий идентификатор на латинице, например: sujato, sv</p>
            </div>

            <div>
                <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Полное имя переводчика</label>
                <input
                    type="text"
                    :value="modelValue.translator?.fullname_ru || ''"
                    @input="updateTranslator('fullname_ru', $event.target.value)"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6"
                />
            </div>


        </div>

        <div class="mt-4 text-red-600 text-sm" v-if="error">
            {{ error }}
        </div>
    </div>
</template>
