<template>
    <div class="footer-panel">
        <div v-if="isLogged()">
            {{ t('loggedAs') }} {{ getName() }}
            (<a class="link logout" @click="logout()">{{ t('logout') }}</a>)
        </div>
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
            return Auth.user.name
        },
        isLogged () {
            return !!Auth.user
        },
        logout () {
            Auth.logout(this)
                .then(() => {
                    this.$router.push('/login')
                })
        }
    }
}
</script>

<style scoped>
    .footer-panel {
        text-align: center;
    }
    .logout {
        cursor: pointer;
    }
</style>
