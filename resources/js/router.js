import Vue from 'vue';
import VueRouter from 'vue-router';

import Register from '../components/views/Register';
import Login from '../components/views/Login';
import Search from '../components/views/Search';
import Results from '../components/views/Results';

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    base: '/',
    routes: [
        { path: '/', component: Search },
        { path: '/register', component: Register },
        { path: '/login', name: 'login', component: Login, props: true },
        { path: '/results/:query/:offset', name: 'results', component: Results, props: true },
        { path: '*', redirect: '/' }
    ]
});
