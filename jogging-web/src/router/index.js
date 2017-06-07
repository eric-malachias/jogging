import Vue from 'vue'
import Router from 'vue-router'
import LoginPage from '@/pages/LoginPage'
import JogPage from '@/pages/JogPage'
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
        }, {
            path: '/jogs/:id',
            component: JogPage
        }
    ]
})
