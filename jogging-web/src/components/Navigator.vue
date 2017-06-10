<template>
    <div class="navigator">
        <span v-for="(page, index) in getPages()" :key="page.name" class="navigator-item">
            <router-link :to="page.url">{{ t(page.name) }}</router-link>
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

<style scoped lang="scss">
    $bg-color: #333;
    $bg-hover-color: darken($bg-color, 10%);
    $bg-active-color: darken($bg-hover-color, 10%);

    .navigator {
        background-color: $bg-color;
        box-shadow: 2px 0 2px rgba(black, 0.3);
        text-align: center;
        width: 100%;

        .navigator-item {
            display: inline-block;

            a {
                background-color: $bg-color;
                color: white;
                display: inline-block;
                padding: 15px;
                text-decoration: none;
                transition: all 0.3s;
                width: 120px;

                &:hover {
                    background-color: $bg-hover-color;
                }

                &.router-link-exact-active {
                    background-color: $bg-active-color;
                }
            }
        }
    }
</style>
