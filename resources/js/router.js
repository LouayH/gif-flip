import Vue from 'vue';
import VueRouter from 'vue-router';

import Register from '../components/views/Register';
import Login from '../components/views/Login';

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    base: '/',
    routes: [
        { path: '/register', component: Register },
        { path: '/login', name: 'login', component: Login, props: true }
    ]
});
