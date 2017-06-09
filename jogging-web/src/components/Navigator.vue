<template>
    <div class="navigator">
        <span v-for="(page, index) in getPages()" :key="page.name">
            <router-link :to="page.url">{{ t(page.name) }}</router-link>
            <span v-if="index !== getPages().length - 1" class="separator"> | </span>
        </span>
    </div>
</template>

<script>
import Auth from '@/services/Auth'

const PERMISSION_NOT_LOGGED = 'not-logged'
const PERMISSION_REGULAR = 'regular'
const PERMISSION_MANAGER = 'manager'
const PERMISSION_ADMIN = 'admin'

export default {
    name: 'navigator',
    data () {
        return {
            pages: [{
                name: 'signUp',
                url: '/sign-up',
                permissions: [PERMISSION_NOT_LOGGED]
            }, {
                name: 'login',
                url: '/login',
                permissions: [PERMISSION_NOT_LOGGED]
            }, {
                name: 'myJogs',
                url: '/jogs',
                permissions: [PERMISSION_REGULAR, PERMISSION_MANAGER, PERMISSION_ADMIN]
            }, {
                name: 'manageUsers',
                url: '/manage/users',
                permissions: [PERMISSION_MANAGER, PERMISSION_ADMIN]
            }, {
                name: 'manageJogs',
                url: '/manage/jogs',
                permissions: [PERMISSION_ADMIN]
            }]
        }
    },
    methods: {
        getPages () {
            const permission = Auth.user.role || PERMISSION_NOT_LOGGED

            return this.pages.filter(page => page.permissions.includes(permission))
        }
    }
}
</script>

<style scoped>
    .navigator {
        text-align: center;
    }
    .router-link-active {
        color: rgba(0, 0, 0, 0.8);
    }
    .separator {
        color: rgba(0, 0, 0, 0.3);
    }
</style>
