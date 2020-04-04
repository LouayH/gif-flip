<template>
    <masonry id="masonry"
        :cols="{default: 5, 1200: 4, 992: 3, 768: 2, 480: 1}"
        :gutter="{default: '1rem'}" >

        <div class="item" v-for="item in items" :key="item.id" @click="openGif(item)">
            <img :src="item.images.fixed_width_still.url"
                :style="{ width: `${item.images.fixed_width_still.width}px`, height: `${item.images.fixed_width_still.height}px` }"
                :alt="item.images.title" />
        </div>
    </masonry>
</template>

<script>
import Vue from 'vue';
import VueMasonry from 'vue-masonry-css';
Vue.use(VueMasonry);

export default {
    props: {
        items: {
            type: Array,
            required: true
        },
        parent: {
            type: String,
            required: true
        }
    },
    methods: {
        openGif(gif) {
            this.$router.push({
                name: this.parent + '/gif',
                params: {
                    gid: gif.id,
                    openedGif: gif,
                    parent: this.parent
                }
            });
        }
    }
}
</script>

<style lang="scss" scoped>
#masonry {
    .item {
        cursor: pointer;

        img {
            display: block;
            border: solid 2px #41403E;
            border-radius: 255px 15px 225px 15px/15px 225px 15px 255px;
            margin-bottom: 1rem;
        }
    }
}
</style>
