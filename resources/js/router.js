import Vue from 'vue';
import VueRouter from 'vue-router';

import Register from '../components/views/Register';
import Login from '../components/views/Login';
import Search from '../components/views/Search';
import Results from '../components/views/Results';
import History from '../components/views/History';
import Favorites from '../components/views/Favorites';
import Gif from '../components/views/Gif';

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    base: '/',
    routes: [
        { path: '/', name: 'search', component: Search,
            children: [
                {
                    path: 'gif/:gid',
                    name: 'search/gif',
                    component: Gif
                }
            ]
        },
        { path: '/register', component: Register },
        { path: '/login', name: 'login', component: Login, props: true },
        { path: '/results/:query/:offset', name: 'results', component: Results, props: true,
            children: [
                {
                    path: 'gif/:gid',
                    name: 'results/gif',
                    component: Gif,
                    props: true
                }
            ]
        },
        { path: '/history', component: History },
        { path: '/favorite', name: 'favorite', component: Favorites,
            children: [
                {
                    path: 'gif/:gid',
                    name: 'favorite/gif',
                    component: Gif,
                    props: true
                }
            ]
        },
        { path: '*', redirect: '/' }
    ]
});
