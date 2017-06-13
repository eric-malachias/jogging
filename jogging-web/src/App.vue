<template>
    <div id="app">
        <navigator></navigator>
        <loader></loader>
        <router-view></router-view>
        <footer-panel></footer-panel>
    </div>
</template>

<script>
import FooterPanel from '@/components/FooterPanel'
import Navigator from '@/components/Navigator'
import Loader from '@/components/Loader'

export default {
    components: {
        FooterPanel,
        Navigator,
        Loader
    },
    name: 'app'
}
</script>

<style>
    #app {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        color: #2c3e50;
    }
    .table {
        margin-bottom: 0;
    }
    .form-control {
        box-shadow: none !important;
    }
</style>
