<template>
    <div id="error-page" class="container">
        <h4 v-if="status === 403">{{ t('error.page.403') }}</h4>
        <h4 v-else>{{ t('error.page.404') }}</h4>
    </div>
</template>

<script>
export default {
    data () {
        return {
            status: ''
        }
    },
    created () {
        const match = this.$route.path.match(/[0-9]{3}$/)

        this.status = match && parseInt(match[0]) || ''
    }
}
</script>

<style scoped>
    #error-page {
        padding: 15px 0;
    }
    h4 {
        text-align: center;
    }
</style>
