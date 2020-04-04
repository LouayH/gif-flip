<template>
    <section id="single-gif" v-if="gif">
        <div id="navigation">
            <div id="prev" class="arrow" v-if="gifIndex > 0" @click="changeGif(-1)">
                <span class="icon left-arrow"></span> Previous
            </div>
            <div id="next" class="arrow" v-if="gifIndex > -1 && gifIndex < $store.getters.results.length - 1" @click="changeGif(1)">
                Next <span class="icon right-arrow"></span>
            </div>
        </div>
        <video controls :src="gif.images.original_mp4.mp4"></video>
        <div id="actions">
            <Favorite :gif="gif" />
            <div id="share-link" class="action" @click="copyURL">
                <input type="text" id="short-url"
                    :value="`${origin}/g/${gif.short_url}`" />
                <span class="icon link"></span> Share GIF Link
            </div>
        </div>
    </section>
</template>

<script>
import Favorite from '../Favorite';

import { getAccessToken } from '../../js/utils';

export default {
    components: { Favorite },
    props: {
        openedGif: {
            type: Object,
            required: false
        }
    },
    data: () => ({
        gif: null
    }),
    computed: {
        gifIndex() {
            return this.$store.getters.results.findIndex(result => result.id === this.gif.id);
        },
        origin() {
            return document.location.origin;
        }
    },
    watch: {
        $route(to, from) {
            this.gif = to.params.openedGif;
        }
    },
    methods: {
        fetchGif() {
            const token = getAccessToken();

            const payload = {
                gif_id: this.$route.params.gid
            };

            fetch('/api/searchById', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(results => {
                this.gif = results.data;
            });
        },
        changeGif(offset) {
            const gif = this.$store.getters.results[this.gifIndex + offset];

            this.$router.push({
                name: 'results/gif',
                params: {
                    gid: gif.id,
                    openedGif: gif
                }
            });
        },
        copyURL() {
            let input = document.querySelector("#short-url");
            input.select();

            try {
                const successful = document.execCommand('copy');
                if(successful) {
                    alert('Shorten URL copied to clipboard');
                }
            } catch (err) {
                alert('Something goes wrong!');
            }
        }
    },
    mounted() {
        if(this.openedGif) {
            this.gif = this.openedGif;
        } else {
            this.fetchGif();
        }

        window.scrollTo(0, 0);
        document.querySelector('body').classList.add('no-overflow');
    },
    destroyed() {
        document.querySelector('body').classList.remove('no-overflow');
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
            cursor: pointer;

            input {
                opacity: 0;
            }

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
