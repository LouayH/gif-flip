import Vue from 'vue';
import Vuex from 'vuex';
import { getAccessToken } from './utils'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: null,
        slideshow_items: []
    },
    mutations: {
        SET_USER: (state, user) => {
            state.user = user;
        },
        ADD_TO_FAVORITES: (state, gif) => {
            state.user.favorites.push(gif);
        },
        REMOVE_FROM_FAVORITES: (state, gif) => {
            const favoritedIndex = state.user.favorites.findIndex(favorite => favorite.gif_id === gif.gif_id);
            state.user.favorites.splice(favoritedIndex, 1);
        },
        SET_SLIDESHOW_ITEMS: (state, items) => {
            state.slideshow_items = items;
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
        slideshow_items: state => state.slideshow_items
    }
});
