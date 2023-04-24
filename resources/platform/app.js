import { createApp } from 'vue';
import EditSuttaContents from "./suttas/EditSuttaContents"

// Обычная загрузка страницы
document.addEventListener("DOMContentLoaded", () => {
    console.log("DOMContentLoaded event");
    createAndMountVue();
});

// Загрузка страницы при помощи hotwire
document.addEventListener("turbo:load", () => {
    console.log("turbo:load event");
    createAndMountVue();
});

function createAndMountVue()
{
    const app = createApp({});
    app.component('edit-sutta-contents', EditSuttaContents);
    app.mount('#vue_app');
}
