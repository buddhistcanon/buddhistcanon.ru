# Система переводов

## Архитектура

### 1. Структура файлов

```
resources/
  js/
    lang/
      ru.json          # Русские переводы
      en.json          # Английские переводы
    composables/
      useLanguage.js   # Управление языком (уже есть)
      useTranslation.js # Работа с переводами (новый)
```

### 2. Как это работает

#### Серверная часть (Laravel) - опционально
- Файлы переводов: `lang/ru/`, `lang/en/` (PHP массивы)
- Middleware `SetLocale` устанавливает локаль из сессии/куки
- Локаль передается в Inertia через `HandleInertiaRequests`

#### Клиентская часть (Vue/Inertia)
- JSON файлы переводов: `resources/js/lang/ru.json`, `resources/js/lang/en.json`
- Composable `useTranslation` для доступа к переводам
- Автоматическая загрузка нужного языка
- Кэширование переводов для производительности
- Fallback на дефолтный язык, если перевод отсутствует

### 3. Использование в компонентах

#### Базовое использование:

```vue
<script setup>
import { useTranslation } from '@/composables/useTranslation.js';

const { t } = useTranslation();
</script>

<template>
  <div>
    <h1>{{ t('common.welcome') }}</h1>
    <button>{{ t('common.save') }}</button>
  </div>
</template>
```

#### С параметрами:

```vue
<template>
  <p>{{ t('auth.throttle', { seconds: 60 }) }}</p>
</template>
```

В JSON:
```json
{
  "auth": {
    "throttle": "Слишком много попыток. Попробуйте еще раз через :seconds секунд."
  }
}
```

#### Пример для NavBar:

```vue
<script setup>
import { useTranslation } from '@/composables/useTranslation.js';

const { t } = useTranslation();
</script>

<template>
  <Link :href="'/bookmarks'">
    {{ t('nav.bookmarks') }}
  </Link>
</template>
```

### 4. Структура JSON файлов

Переводы организованы по категориям:

```json
{
  "common": {
    "welcome": "Добро пожаловать",
    "loading": "Загрузка..."
  },
  "nav": {
    "home": "Главная страница",
    "bookmarks": "Мои закладки"
  },
  "sutta": {
    "add_to_bookmarks": "Добавить в закладки",
    "remove_from_bookmarks": "Удалить из закладки"
  }
}
```

### 5. Интеграция с переключением языка

Система автоматически реагирует на изменение языка:

1. Пользователь выбирает язык в NavBar
2. `useLanguage` обновляет `currentLanguage`
3. `useTranslation` отслеживает изменения через `watch`
4. Переводы автоматически перезагружаются
5. Компоненты обновляются с новыми переводами

### 6. Добавление новых переводов

1. Добавьте ключ в оба файла (`ru.json` и `en.json`)
2. Используйте в компонентах через `t('category.key')`
3. Если перевод отсутствует, будет показан ключ (с предупреждением в консоли)

### 7. Преимущества

- ✅ Централизованное хранение переводов
- ✅ Автоматическая загрузка нужного языка
- ✅ Кэширование для производительности
- ✅ Fallback на дефолтный язык
- ✅ Поддержка параметров в переводах
- ✅ Типобезопасность через структуру JSON
- ✅ Легко расширять новыми языками

