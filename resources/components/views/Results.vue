<template>
    <section id="results">
        <masonry id="masonry" v-if="results.length"
            :cols="{default: 5, 1200: 4, 992: 3, 768: 2, 480: 1}"
            :gutter="{default: '1rem'}" >
            <div class="item" @click="openGif(result)"
                v-for="result in results" :key="result.id">
                <img :src="result.images.fixed_width_still.url"
                    :style="{ width: `${result.images.fixed_width_still.width}px`, height: `${result.images.fixed_width_still.height}px` }"
                    :alt="result.images.title" />
            </div>
        </masonry>

        <p v-else>
            {{ loadingText }}
            <router-link id="search-again" v-if="noResults" to="/">Try another keywords.</router-link>
        </p>

        <nav id="navigation" v-if="results.length">
            <button v-if="$route.params.offset > 0" @click="goToPage(-1)">
                Previous
            </button>
            <button v-if="$route.params.offset <= this.last_offset" @click="goToPage(1)">
                {{ $route.params.offset > 0 ? 'Next' : 'Load More' }}
            </button>
        </nav>

        <div id="overlay" v-if="openedGif" @click.self="closeGif">
            <Gif :openedGif="openedGif" />
        </div>
    </section>
</template>

<script>
import Vue from 'vue';
import VueMasonry from 'vue-masonry-css';
Vue.use(VueMasonry);

import { getAccessToken } from '../../js/utils';

import Gif from './Gif';

export default {
    components: { Gif },
    props: {
        directSearch: {
            type: Boolean,
            default: false,
            required: false
        }
    },
    data: () => ({
        loadingText: 'Please wait...',
        noResults: null,
        total_count: 0,
        results: [],
        openedGif: null
    }),
    computed: {
        last_offset() {
            // 24 is images count per page
            return this.total_count - 24;
        }
    },
    watch: {
        $route(to, from) {
            this.results = [];
            this.fetchResults();
        }
    },
    methods: {
        fetchResults() {
            const token = getAccessToken();

            const payload = {
                query_string: this.$route.params.query,
                offset: this.$route.params.offset,
                direct_search: this.directSearch
            };

            fetch('/api/search', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `bearer ${token}`
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(results => {
                this.total_count = results.pagination.total_count;
                if(this.total_count) {
                    this.results = results.data;
                    this.$store.commit('SET_RESULTS', this.results);
                } else  {
                    this.noResults = true;
                    this.loadingText = 'No results found! ';
                }
            });
        },
        goToPage(direction) {
            this.$router.push({
                name: 'results',
                params: {
                    query: this.$route.params.query,
                    offset: parseInt(this.$route.params.offset) + (direction * 24)
                    // 24 is images count per page
                }
            });
        },
        openGif(gif) {
            this.openedGif = gif;
            window.scrollTo(0, 0);
            document.querySelector('body').classList.add('no-overflow');
        },
        closeGif() {
            this.openedGif = null;
            document.querySelector('body').classList.remove('no-overflow');
        }
    },
    mounted() {
        if(!this.$route.params) {
            this.$router.push('/');
        }

        this.fetchResults();
    }
}
</script>

<style lang="scss" scoped>
#results {
    display: flex;
    flex-direction: column;
    align-items: center;

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

    #search-again {
        text-decoration: underline;
    }

    #navigation {
        display: flex;
        margin-top: 1rem;

        button {
            outline: 0;
            border: dashed 2px #41403E;
            border-radius: 255px 31px 225px 31px/15px 225px 15px 255px;
            width: 10rem;
            padding: .5rem 1rem;
            background: white;
            font-size: 1.25rem;
            transition: all .1s ease-in;
            cursor: pointer;

            &:not(:last-child) {
                margin-right: 1rem;
            }

            &:hover {
                border-style: solid;
            }
        }
    }
}
</style>
