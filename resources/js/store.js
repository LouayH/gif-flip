import Vue from 'vue';
import Vuex from 'vuex';
import { getAccessToken } from './utils'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: null,
        results: []
    },
    mutations: {
        SET_USER: (state, user) => {
            state.user = user;
        },
        SET_RESULTS: (state, results) => {
            state.results = results;
        }
    },
    actions: {
        fetchUser({ commit }) {
            const token = getAccessToken();

            if(token) {
                // get user data from api
                fetch('/auth/me', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `bearer ${token}`
                    }
                })
                .then(response => {
                    if(response.ok) {
                        return response.json();
                    }
                })
                .then(data => {
                    if(data && data.id) {
                        commit('SET_USER', data);
                    }
                });
            }
        }
    },
    getters: {
        user: state => state.user,
        results: state => state.results
    }
});
