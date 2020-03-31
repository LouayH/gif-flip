import Vue from 'vue';
import router from './router';
import Layout from '../components/Layout';

new Vue({
    router,
    render: h => h(Layout)
}).$mount('#app');
