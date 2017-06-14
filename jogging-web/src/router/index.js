import Vue from 'vue'
import Router from 'vue-router'
import Auth from '@/services/Auth'
import LoginPage from '@/pages/LoginPage'
import JogPage from '@/pages/JogPage'
import JogListPage from '@/pages/JogListPage'
import SignUpPage from '@/pages/SignUpPage'
import ErrorPage from '@/pages/ErrorPage'
import UserListPage from '@/pages/UserListPage'
import UserPage from '@/pages/UserPage'
import HomePage from '@/pages/HomePage'
import Http from '@/services/Http'

Vue.use(Router)

function makeCondition (condition, next) {
    if (condition) {
        next()
        return
    }

    next(new Error(Http.HTTP_FORBIDDEN))
}

const conditions = {
    isLogged (from, to, next) {
        return makeCondition(Auth.user, next)
    },
    isInRoles () {
        const roles = [...arguments]

        return function (from, to, next) {
            return makeCondition(Auth.user && roles.includes(Auth.user.role), next)
        }
    },
    isNotLogged (from, to, next) {
        return makeCondition(!Auth.user, next)
    }
}

const router = new Router({
    routes: [
        {
            path: '/login',
            component: LoginPage,
            beforeEnter: conditions.isNotLogged
        }, {
            path: '/jogs',
            component: JogListPage,
            beforeEnter: conditions.isInRoles('regular', 'admin')
        }, {
            path: '/jogs/:id',
            component: JogPage,
            beforeEnter: conditions.isInRoles('regular', 'admin')
        }, {
            path: '/sign-up',
            component: SignUpPage,
            beforeEnter: conditions.isNotLogged
        }, {
            path: '/users',
            component: UserListPage,
            beforeEnter: conditions.isInRoles('manager', 'admin')
        }, {
            path: '/users/:id',
            component: UserPage,
            beforeEnter: conditions.isInRoles('manager', 'admin')
        }, {
            path: '/me',
            component: UserPage,
            beforeEnter: conditions.isLogged
        }, {
            path: '/',
            component: HomePage
        }, {
            path: '*',
            component: ErrorPage
        }
    ]
})

router.onError(function (error) {
    router.push(`/${error.message}`)
})

export default router
