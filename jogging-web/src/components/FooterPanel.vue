<template>
    <div class="footer-panel">
        <div v-if="isLogged()">{{ t('loggedAs') }} {{ getName() }}</div>
    </div>
</template>

<script>
import Auth from '@/services/Auth'

export default {
    name: 'footer-panel',
    data () {
        return {}
    },
    methods: {
        getName () {
            return Auth.getUser().name
        },
        isLogged () {
            return !!Auth.getUser()
        }
    }
}
</script>

<style scoped>
</style>
