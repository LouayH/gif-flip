<template>
    <form @submit.prevent="submit">
        <h1>Looking for a GIF? Here is it!</h1>
        <input type="text" v-model="query_string" placeholder="Enter any keywords..." />
        <button type="submit">
            Search
        </button>
    </form>
</template>

<script>
export default {
    data: () => ({
        query_string: null
    }),
    methods: {
        submit() {
            this.$router.push({
                name: 'results',
                params: {
                    query: this.query_string,
                    offset: 0,
                    directSearch: true
                }
            })
        }
    }
}
</script>

<style lang="scss" scoped>
form {
    display: flex;
    flex-wrap: wrap;
    box-sizing: border-box;
    box-shadow: 20px 20px 20px -20px hsla(0,0%,0%,.2);
    border-radius: 255px 15px 225px 15px/15px 225px 15px 255px;
    border: solid 2px #41403E;
    width: 100%;
    padding: 1rem 2rem;
    max-width: 480px;

    h1 {
        font-size: 1.75rem;
    }

    input {
        outline: 0;
        box-sizing: border-box;
        border-radius: 255px 15px 225px 15px/15px 225px 15px 255px;
        border: dashed 2px #41403E;
        margin: .5rem 0;
        width: 100%;
        padding: .5rem 1rem;
        font-family: 'Comic Neue', cursive;
        font-size: 1.25rem;
    }

    button {
        outline: 0;
        border: 0;
        margin: 1rem 0 1rem auto;
        background: transparent;
        font-family: 'Comic Neue', cursive;
        font-weight: bold;
        font-size: 1.25rem;
        cursor: pointer;

        &:disabled {
            cursor: default;
        }
    }
}
</style>
