<template>
    <section id="results">
        <Masonry v-if="results.length" :items="results" :parent="'results'" />

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

        <OverlayGif :parent="'results'" />
    </section>
</template>

<script>
import Masonry from '../Masonry';
import OverlayGif from '../OverlayGif';

import { getAccessToken } from '../../js/utils';

export default {
    components: { Masonry, OverlayGif },
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
        results: []
    }),
    computed: {
        last_offset() {
            // 24 is images count per page
            return this.total_count - 24;
        }
    },
    watch: {
        $route(to, from) {
            if(from.name !== 'results/gif' && to.name === 'results') {
                this.results = [];
                this.fetchResults();
            }
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
                    this.$store.commit('SET_SLIDESHOW_ITEMS', this.results);
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
