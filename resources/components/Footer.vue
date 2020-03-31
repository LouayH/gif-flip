<template>
    <footer>
        GIF Flip &copy; {{ year }}
    </footer>
</template>

<script>
export default {
    computed: {
        year: () => {
            const currentDate = new Date()
            return currentDate.getFullYear()
        }
    }
}
</script>

<style lang="scss" scoped>
footer {
    padding: 2rem 0;
    text-align: center;
    font-size: 1.25rem;
}
</style>
