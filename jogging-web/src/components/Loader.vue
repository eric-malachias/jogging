<template>
    <div id="loader" v-if="isLoading()">
        <double-bounce-spin></double-bounce-spin>
    </div>
</template>

<script>
import Http from '@/services/Http'
import DoubleBounceSpin from 'vue-loading-spinner/src/components/DoubleBounce'

export default {
    name: 'loader',
    components: {
        DoubleBounceSpin
    },
    methods: {
        isLoading () {
            return Http.loading > 0
        }
    }
}
</script>

<style scoped lang="scss">
    $size: 40px;

    #loader {
        left: 50%;
        margin-left: -$size / 2;
        position: absolute;
        top: 65px;
        width: $size;
    }
</style>
