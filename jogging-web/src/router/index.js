import Vue from 'vue'
import Router from 'vue-router'
import LoginPage from '@/pages/LoginPage'
import JogListPage from '@/pages/JogListPage'

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/login',
            component: LoginPage
        }, {
            path: '/jogs',
            component: JogListPage
        }
    ]
})
