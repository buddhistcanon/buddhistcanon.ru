import { ref, computed, watch } from 'vue';
import { useLanguage } from './useLanguage.js';
import ruTranslations from '../lang/ru.json';
import enTranslations from '../lang/en.json';

const translationsMap = {
    ru: ruTranslations,
    en: enTranslations,
};

function loadTranslations(locale) {
    const translations = translationsMap[locale] || translationsMap['ru'];
    return translations || {};
}

function getNestedValue(obj, path) {
    return path.split('.').reduce((current, key) => {
        return current && current[key] !== undefined ? current[key] : undefined;
    }, obj);
}

export function useTranslation() {
    const { currentLanguage } = useLanguage();

    const getLocale = () => {
        const lang = currentLanguage.value;
        if (typeof lang === 'string') {
            return lang;
        } else if (lang && typeof lang === 'object' && lang.code) {
            return lang.code;
        }
        return 'ru';
    };

    const initialLocale = getLocale();
    const initialTranslations = loadTranslations(initialLocale);
    const translations = ref(initialTranslations || {});

    const load = (locale = null) => {
        const targetLocale = locale || getLocale();
        const loaded = loadTranslations(targetLocale);
        if (loaded && Object.keys(loaded).length > 0) {
            translations.value = loaded;
        }
    };

    const t = (key, params = {}) => {
        if (!translations.value || Object.keys(translations.value).length === 0) {
            load();
            if (!translations.value || Object.keys(translations.value).length === 0) {
                return key;
            }
        }

        const value = getNestedValue(translations.value, key);

        if (value === undefined) {
            return key;
        }

        if (typeof value === 'string') {
            let result = value;
            Object.keys(params).forEach(paramKey => {
                result = result.replace(`:${paramKey}`, params[paramKey]);
            });
            return result;
        }

        return value;
    };

    const isLoaded = computed(() => {
        return Object.keys(translations.value).length > 0;
    });

    watch(() => getLocale(), (newLocale) => {
        if (newLocale) {
            load(newLocale);
        }
    });

    return {
        t,
        translations,
        isLoading: ref(false),
        isLoaded,
        load,
        currentLocale: currentLanguage,
    };
}
