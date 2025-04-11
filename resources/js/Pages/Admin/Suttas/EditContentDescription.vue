<script setup>
import { defineProps, defineEmits, watch } from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['update:modelValue']);

// Убедимся, что translator всегда присутствует в объекте modelValue
// watch(() => props.modelValue.lang, (newLang) => {
//     if (newLang && (newLang !== 'pali' && newLang !== 'bo') && !props.modelValue.translator) {
//         emit('update:modelValue', {
//             ...props.modelValue,
//             translator: {
//                 fullname_ru: '',
//                 slug: ''
//             }
//         });
//     }
// }, { immediate: true });

const updateLang = (value) => {
    const modelValue = {
        ...props.modelValue,
        lang: value
    };
    emit('update:modelValue', modelValue);
};

const updateTranslator = (field, value) => {
    const modelValue = {
        ...props.modelValue,  // копируем все существующие поля
        translator: {
            ...props.modelValue.translator,
            [field]: value
        }
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
        </div>

        <!-- Данные переводчика (только если это не оригинал) -->
        <div  class="flex flex-col space-y-4">
            <div>
                <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Полное имя переводчика (на русском)</label>
                <input
                    type="text"
                    :value="modelValue.translator?.fullname_ru || ''"
                    @input="updateTranslator('fullname_ru', $event.target.value)"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6"
                />
            </div>

            <div>
                <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Идентификатор переводчика (латиницей)</label>
                <input
                    type="text"
                    :value="modelValue.translator?.slug || ''"
                    @input="updateTranslator('slug', $event.target.value)"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6"
                />
                <p class="mt-1 text-sm text-gray-500">Короткий идентификатор на латинице, например: sujato, sv</p>
            </div>
        </div>
    </div>
</template>
