import Vue from 'vue';
import VueRouter from 'vue-router';

import Register from '../components/views/Register';

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    base: '/',
    routes: [
        { path: '/register', component: Register }
    ]
});
