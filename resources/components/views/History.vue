<template>
    <section id="history" v-if="username">
        <h2>{{ username }}'s search history</h2>
        <table v-if="history.length">
            <thead>
                <tr>
                    <th>
                        Query String
                    </th>
                    <th>
                        Date and Time
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in history" :key="row.id">
                    <td>
                        <router-link :to="{ name: 'results', params: { query: row.query_string, offset: 0 } }">{{ row.query_string }}</router-link>
                    </td>
                    <td>
                        {{ row.created_at }}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                All times displayed in GMT Stand.
            </tfoot>
        </table>
        <p v-else>
            {{ loadingText }}
            <router-link id="search-now" v-if="noResults" to="/">Go search now.</router-link>
        </p>

    </section>
</template>

<script>
import { getAccessToken } from '../../js/utils';

export default {
    data: () => ({
        noResults: null,
        loadingText: 'Please wait...',
        history: []
    }),
    computed: {
        username() {
            if(this.$store.getters.user) {
                return this.$store.getters.user.username;
            }
        }
    },
    mounted() {
        const token = getAccessToken();

        fetch('/api/history', {
            method: 'POST',
            headers: {
                'Authorization': `bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data && data.length) {
                this.history = data;
            } else {
                this.noResults = true;
                this.loadingText = 'No history found! ';
            }
        });
    }
}
</script>

<style lang="scss" scoped>
#history {
    box-sizing: border-box;
    box-shadow: 20px 20px 20px -20px hsla(0,0%,0%,.2);
    border-radius: 255px 15px 225px 15px/15px 225px 15px 255px;
    border: solid 2px #41403E;
    width: 100%;
    max-width: 480px;
    padding: 1rem 2rem;

    table {
        box-sizing: border-box;
        box-shadow: 20px 20px 20px -20px hsla(0,0%,0%,.2);
        border-radius: 255px 15px 225px 15px/15px 225px 15px 255px;
        border: dashed 2px #41403E;
        width: 100%;
        padding: 1rem 2rem;

        thead {
            display: table;
            width: calc(100% - 1rem); // 1rem is scroll bar width
            table-layout: fixed;
            margin-bottom: 1rem;
        }

        tbody {
            overflow-y: scroll;
            display: block;
            margin-bottom: 1rem;
            height: 200px;
            padding-right: .5rem;

            tr {
                display: table;
                width: 100%;
                table-layout: fixed;

                td {
                    text-align: center;

                    a {
                        text-decoration: underline;
                    }
                }
            }
        }
    }

    #search-now {
        text-decoration: underline;
    }
}
</style>
