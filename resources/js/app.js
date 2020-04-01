import Vue from 'vue';
import router from './router';
import store from './store';
import Layout from '../components/Layout';

new Vue({
    router,
    store,
    render: h => h(Layout)
}).$mount('#app');
