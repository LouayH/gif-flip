<template>
    <section id="favorites">
        <Masonry v-if="_favorites.length" :items="_favorites" :parent="'favorite'" />

        <p v-else>
            No favorites found!
            <router-link id="search-again" to="/">Go Fav Some GIFs.</router-link>
        </p>

        <OverlayGif :parent="'favorite'" />
    </section>
</template>

<script>
import Masonry from '../Masonry';
import OverlayGif from '../OverlayGif';

import { mapGetters } from 'vuex';

export default {
    components: { Masonry, OverlayGif },
    data: () => ({
        favorites: []
    }),
    computed: {
        ...mapGetters(['user']),
        _favorites: {
            get() {
                return this.favorites.map(favorite => ({
                    id: favorite.gif_id,
                    title: favorite.gif_title,
                    images: {
                        fixed_width_still: {
                            url: favorite.thumb_url,
                            width: favorite.thumb_width,
                            height: favorite.thumb_height
                        },
                        original_mp4: {
                            mp4: favorite.mp4_url
                        }
                    }
                }));
            },
            set(value) {
                this.favorites = value;
                this.$store.commit('SET_SLIDESHOW_ITEMS', this._favorites);
            }
        }
    },
    watch: {
        user(userData) {
            this._favorites = userData.favorites;
        }
    },
    mounted() {
        if(this.user) {
            this._favorites = this.user.favorites;
        }
    }
}
</script>

<style lang="scss" scoped>
#favorites {
    display: flex;
    flex-direction: column;
    align-items: center;

    #search-again {
        text-decoration: underline;
    }
}
</style>
