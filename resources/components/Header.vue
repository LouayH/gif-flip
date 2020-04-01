<template>
    <header>
        <h1>
            <router-link to="/">GIF Flip</router-link>
        </h1>
        <nav v-if="!$store.getters.user">
            <router-link to="/register">Register</router-link>
            <router-link to="/login">Login</router-link>
        </nav>
        <nav v-else>
            <router-link to="/history">History</router-link>
            <router-link to="/favorite">Favorite</router-link>
            <router-link to="/" @click.native="logout">Logout</router-link>
        </nav>
    </header>
</template>

<script>
import { getAccessToken } from '../js/utils';

export default {
    methods: {
        logout() {
            const token = getAccessToken();

            if(token) {
                fetch('/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `bearer ${token}`
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.message) {
                        this.$store.commit('SET_USER', null);
                        this.$router.push('/');
                    }
                });
            }
        }
    }
}
</script>

<style lang="scss" scoped>
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;

    h1 {
        font-size: 1.5rem;
    }

    nav {
        a {
            border: dashed 2px #41403E;
            border-radius: 255px 31px 225px 31px/15px 225px 15px 255px;
            padding: .5rem 1rem;
            font-size: 1.25rem;
            transition: all .1s ease-in;

            &:hover,
            &.router-link-exact-active {
                border-style:solid;
                box-shadow: 0px 5px 25px -10px rgba(0, 0, 0, 0.2);
            }

            &:not(:last-child) {
                margin-right: 1rem;
            }

            @media screen and (max-width: 768px) {
                padding: .5rem;
                font-size: 1rem;

                &:not(:last-child) {
                    margin-right: .5rem;
                }
            }
        }
    }

    @media screen and (max-width: 480px) {
        flex-direction: column;

        nav {
            margin: 1rem 0;
        }
    }
}
</style>
