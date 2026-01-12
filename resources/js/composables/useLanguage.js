import { ref } from 'vue';

const STORAGE_KEY = 'app_language';
const DEFAULT_LANGUAGE = 'ru';

export const availableLanguages = [
    { code: 'ru', name: 'Русский' },
    { code: 'en', name: 'Английский' },
];

function getStoredLanguage() {
    if (typeof window === 'undefined') {
        return DEFAULT_LANGUAGE;
    }

    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored && availableLanguages.some(lang => lang.code === stored)) {
        return stored;
    }

    const browserLang = navigator.language.split('-')[0];
    if (availableLanguages.some(lang => lang.code === browserLang)) {
        return browserLang;
    }

    return DEFAULT_LANGUAGE;
}

function saveLanguage(lang) {
    if (typeof window !== 'undefined') {
        localStorage.setItem(STORAGE_KEY, lang);
    }
}

export function useLanguage() {
    const currentLanguage = ref(getStoredLanguage());

    const getLanguage = (code) => {
        return availableLanguages.find(lang => lang.code === code) || availableLanguages[0];
    };

    const setLanguage = async (langCode) => {
        if (!availableLanguages.some(lang => lang.code === langCode)) {
            return;
        }

        currentLanguage.value = langCode;
        saveLanguage(langCode);

        try {
            await window.axios.post('/api/language', { language: langCode });
            await new Promise(resolve => setTimeout(resolve, 100));
        } catch (error) {
            // Continue even if server request fails - language is already saved in localStorage
        }

        setTimeout(() => {
            window.location.reload();
        }, 150);
    };

    const getCurrentLanguage = () => {
        return getLanguage(currentLanguage.value);
    };

    const syncWithServer = (serverLocale, force = false) => {
        if (!serverLocale || !availableLanguages.some(lang => lang.code === serverLocale)) {
            return;
        }

        if (!force) {
            const stored = getStoredLanguage();
            if (stored && stored !== serverLocale) {
                return;
            }
        }

        if (serverLocale !== currentLanguage.value) {
            currentLanguage.value = serverLocale;
            saveLanguage(serverLocale);
        }
    };

    const init = () => {
        const stored = getStoredLanguage();
        if (stored !== currentLanguage.value) {
            currentLanguage.value = stored;
        }
    };

    return {
        currentLanguage,
        availableLanguages,
        setLanguage,
        getCurrentLanguage,
        getLanguage,
        syncWithServer,
        init,
    };
}

