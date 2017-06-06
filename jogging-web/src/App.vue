<template>
    <div id="app">
        <navigator></navigator>
        <router-view></router-view>
    </div>
</template>

<script>
import Navigator from '@/components/Navigator'

export default {
    components: {
        Navigator
    },
    name: 'app'
}
</script>

<style>
#app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
}
</style>
