<template>
    <section id="single-gif" v-if="gif">
        <div id="navigation">
            <div id="prev" class="arrow" v-if="gifIndex > 0" @click="changeGif(-1)">
                <span class="icon left-arrow"></span> Previous
            </div>
            <div id="next" class="arrow" v-if="gifIndex < 23" @click="changeGif(1)">
                Next <span class="icon right-arrow"></span>
            </div>
        </div>
        <video controls :src="gif.images.original_mp4.mp4"></video>
        <div id="actions">
            <div id="favorite" class="action">
                <span class="icon outline-heart"></span> Favorite this GIF
            </div>
            <div id="share-link" class="action">
                <span class="icon link"></span> Share GIF Link
            </div>
        </div>
    </section>
</template>

<script>
export default {
    props: {
        openedGif: {
            type: Object,
            required: true
        }
    },
    data: () => ({
        gif: null
    }),
    computed: {
        gifIndex() {
            return this.$store.getters.results.findIndex(result => result.id === this.gif.id);
        }
    },
    methods: {
        changeGif(offset) {
            this.gif = this.$store.getters.results[this.gifIndex + offset];
        }
    },
    mounted() {
        if(this.openedGif) {
            this.gif = this.openedGif;
        }
    }
}
</script>

<style lang="scss" scoped>
#single-gif {
    video {
        outline: 0;
        border: solid 2px #ecf0f1;
        border-radius: 255px 15px 225px 15px/15px 225px 15px 255px;
        width: 100%;
        max-width: 480px;
    }

    #actions {
        display: flex;
        justify-content: space-between;
        margin-top: 1rem;
        color: #ecf0f1;

        .action {
            display: flex;
            align-items: center;

            .icon {
                display: inline-block;
                margin-right: .5rem;
                width: 1rem;
                height: 1rem;
                mask-size: cover;

                &.link {
                    mask: url('../../images/link.svg');
                    background: #ecf0f1;
                }

                &.outline-heart {
                    mask: url('../../images/outline-heart.svg');
                    background: #ecf0f1;
                }

                &.heart {
                    mask: url('../../images/heart.svg');
                    background: #e74c3c;
                }
            }
        }
    }

    #navigation {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        color: #ecf0f1;

        .arrow {
            display: flex;
            align-items: center;
            cursor: pointer;

            &#prev {
                margin-right: auto;
            }

            &#next {
                margin-left: auto;
            }

            .icon {
                display: inline-block;
                width: .8rem;
                height: 1rem;
                mask-size: cover;

                &.left-arrow {
                    margin-right: .5rem;
                    mask: url('../../images/left-arrow.svg');
                    background: #ecf0f1;
                }

                &.right-arrow {
                    margin-left: .5rem;
                    mask: url('../../images/right-arrow.svg');
                    background: #ecf0f1;
                }
            }
        }
    }
}
</style>
