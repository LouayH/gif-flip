<template>
    <section id="favorites">
        <masonry id="masonry" v-if="favorites.length"
            :cols="{default: 5, 1200: 4, 992: 3, 768: 2, 480: 1}"
            :gutter="{default: '1rem'}" >
            <div class="item"
                v-for="favorite in favorites" :key="favorite.id">
                <img :src="favorite.thumb_url"
                    :style="{ width: `${favorite.thumb_width}px`, height: `${favorite.thumb_height}px` }"
                    :alt="favorite.title" />
            </div>
        </masonry>

        <p v-else>
            No favorites found!
            <router-link id="search-again" to="/">Go Fav Some GIFs.</router-link>
        </p>
    </section>
</template>

<script>
import Vue from 'vue';
import VueMasonry from 'vue-masonry-css';
Vue.use(VueMasonry);

export default {
    computed: {
        favorites() {
            return this.$store.getters.user.favorites;
        }
    }
}
</script>

<style lang="scss" scoped>
#favorites {
    display: flex;
    flex-direction: column;
    align-items: center;

    #masonry {
        .item {
            img {
                display: block;
                border: solid 2px #41403E;
                border-radius: 255px 15px 225px 15px/15px 225px 15px 255px;
                margin-bottom: 1rem;
            }
        }
    }

    #search-again {
        text-decoration: underline;
    }
}
</style>
