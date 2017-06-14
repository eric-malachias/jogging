<template>
    <div id="welcome-page" class="container-sm">
        <h4>{{ t('home.welcome') }}</h4>
        <h4>{{ t('home.loader') }}</h4>
        <div class="loader-container">
            <double-bounce-spin></double-bounce-spin>
        </div>
        <h4>{{ t('home.firstSteps') }}</h4>
    </div>
</template>

<script>
import DoubleBounceSpin from 'vue-loading-spinner/src/components/DoubleBounce'

export default {
    components: {
        DoubleBounceSpin
    }
}
</script>

<style scoped lang="scss">
    $container-width: 480px;

    #welcome-page {
        padding: 15px;
        text-align: center;
    }
    .container-sm {
        margin: auto;
        width: 100%;
    }
    @media (min-width: $container-width) {
        .container-sm {
            width: $container-width;
        }
    }
    .loader-container {
        display: inline-block;
    }
    h4 {
        line-height: 35px;
    }
</style>
