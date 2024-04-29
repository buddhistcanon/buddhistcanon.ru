import './bootstrap';
import '../css/app.css';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
// import VStickyElement from 'vue-sticky-element';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Buddistcanon';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
        return pages[`./Pages/${name}.vue`]
    },
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            // .use(VStickyElement)
            .mount(el);
    },
    progress: {
        color: '#3c84ff',
    },
});
