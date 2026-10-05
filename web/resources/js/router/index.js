import { createRouter, createWebHistory } from 'vue-router';

import Dashboard from '../pages/Dashboard.vue';
import Tenants from '../pages/Tenants.vue';
import Rooms from '../pages/Rooms.vue';
import Payments from '../pages/Payments.vue';
import Reports from '../pages/Reports.vue';
import Settings from '../pages/Settings.vue';

const routes = [
    { path: '/', name: 'dashboard', component: Dashboard },
    { path: '/tenants', name: 'tenants', component: Tenants },
    { path: '/rooms', name: 'rooms', component: Rooms },
    { path: '/payments', name: 'payments', component: Payments },
    { path: '/reports', name: 'reports', component: Reports },
    { path: '/settings', name: 'settings', component: Settings },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
