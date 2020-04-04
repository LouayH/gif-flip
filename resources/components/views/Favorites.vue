<template>
    <section id="favorites">
        <Masonry v-if="favorites.length" :items="favorites" :parent="'favorite'" />

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

export default {
    components: { Masonry, OverlayGif },
    computed: {
        favorites() {
            return this.$store.getters.user.favorites.map(favorite => ({
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
