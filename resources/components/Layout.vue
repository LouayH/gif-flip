<template>
    <div id="wrapper">
        <Header />
        <main>
            <transition enter-active-class="animated flipInY" leave-active-class="animated flipOutY" mode="out-in">
                <router-view></router-view>
            </transition>
        </main>
        <Footer />
    </div>
</template>

<script>
import Header from './Header'
import Footer from './Footer'

export default {
    components: { Header, Footer }
}
</script>

<style lang="scss" scoped>
#wrapper {
    display: flex;
    flex-direction: column;
    margin: 0 auto;
    width: 1200px;
    min-height: 100vh;

    @media screen and (max-width: 1200px) {
        width: 992px;
    }

    @media screen and (max-width: 992px) {
        width: 768px;
    }

    @media screen and (max-width: 768px) {
        width: 480px;
    }

    @media screen and (max-width: 480px) {
        width: 300px;
    }

    main {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-grow: 1;
    }
}
</style>
