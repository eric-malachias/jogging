<template>
    <div class="navigator">
        <span v-for="(page, index) in getPages()" :key="page.name">
            <router-link :to="page.url">{{ t(page.name) }}</router-link>
            <span v-if="index !== getPages().length - 1" class="separator"> | </span>
        </span>
    </div>
</template>

<script>
export default {
    name: 'navigator',
    data () {
        return {
            pages: [{
                name: 'signUp',
                url: '/sign-up'
            }, {
                name: 'login',
                url: '/login'
            }, {
                name: 'myJogs',
                url: '/jogs'
            }, {
                name: 'manageUsers',
                url: '/manage/users'
            }, {
                name: 'manageJogs',
                url: '/manage/jogs'
            }]
        }
    },
    methods: {
        getPages () {
            const names = ['signUp', 'login']

            return this.pages.filter(page => names.includes(page.name))
        }
    }
}
</script>

<style scoped>
    .router-link-active {
        color: rgba(0, 0, 0, 0.8);
    }
    .separator {
        color: rgba(0, 0, 0, 0.3);
    }
</style>
