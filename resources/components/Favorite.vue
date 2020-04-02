<template>
    <div id="favorite" class="action" @click="favorite">
        <template v-if="!isFavorited">
            <span class="icon outline-heart"></span> {{ $store.getters.user ? 'Favorite this GIF' : 'Login to Favorite' }}
        </template>
        <template v-else>
            <span class="icon heart"></span> Favorited
        </template>
    </div>
</template>

<script>
import { getAccessToken } from '../js/utils';

export default {
    props: {
        gif: {
            type: Object,
            required: true
        }
    },
    computed: {
        isFavorited() {
            if(this.$store.getters.user) {
                return this.$store.getters.user.favorites.find(favorite => favorite.gif_id === this.gif.id);
            } else {
                return false;
            }
        }
    },
    methods: {
        favorite() {
            const token = getAccessToken();

            const payload = {
                gif_id: this.gif.id,
                gif_title: this.gif.title,
                thumb_url: this.gif.images.fixed_width_still.url,
                thumb_width: this.gif.images.fixed_width_still.width,
                thumb_height: this.gif.images.fixed_width_still.height,
                mp4_url: this.gif.images.original_mp4.mp4
            };

            fetch(`/api/${this.isFavorited ? 'unfavorite' : 'favorite'}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `bearer ${token}`
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if(data.id) {
                    this.$store.commit(this.isFavorited ? 'REMOVE_FROM_FAVORITES' : 'ADD_TO_FAVORITES', data);
                }
            });
        }
    }
}
</script>

<style lang="scss" scoped>
.icon {
    display: inline-block;
    margin-right: .5rem;
    width: 1rem;
    height: 1rem;
    mask-size: cover;

    &.outline-heart {
        mask: url('../images/outline-heart.svg');
        background: #ecf0f1;
    }

    &.heart {
        mask: url('../images/heart.svg');
        background: #e74c3c;
    }
}
</style>
