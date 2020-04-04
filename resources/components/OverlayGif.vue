<template>
    <div id="overlay" v-if="isGifOpened" @click.self="closeGif">
        <router-view />
    </div>
</template>

<script>
export default {
    props: {
        parent: {
            type: String,
            required: true
        }
    },
    computed: {
        isGifOpened() {
            return this.$route.name.includes('gif');
        }
    },
    methods: {
        closeGif() {
            this.$router.push({
                name: this.parent
            });
        }
    }
}
</script>

<style lang="scss" scoped>
#overlay {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(0, 0, 0, .75);
}
</style>
