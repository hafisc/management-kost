import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';

import AppLayout from './layouts/AppLayout.vue';

const app = createApp(AppLayout);

app.use(createPinia());
app.use(router);

app.mount('#app');
