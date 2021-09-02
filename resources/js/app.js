require('./bootstrap');
require('../../public/molla-template/js/bootstrap-input-spinner')
require("./util/init-service-worker");
require("./util/input-transform");
require("./util/input-filter");
require("./util/general");

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

const el = document.getElementById('app');

createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: name => import(`./Pages/${name}`).then(importedFile => importedFile.default)
        }),
})
    .mixin({ methods: { route } })
    .use(InertiaPlugin)
    .mount(el);

InertiaProgress.init({ color: '#4B5563' });
