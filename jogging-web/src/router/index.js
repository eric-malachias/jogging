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

Vue.use(Router)

const ERROR_FORBIDDEN = 403

function makeCondition (condition, next) {
    if (condition) {
        next()
        return
    }

    next(new Error(ERROR_FORBIDDEN))
}
function is (roles) {
    return Auth.user && roles.includes(Auth.user.role)
}

const conditions = {
    isAdmin (from, to, next) {
        return makeCondition(is(['admin']), next)
    },
    isLogged (from, to, next) {
        return makeCondition(Auth.user, next)
    },
    isManager (from, to, next) {
        return makeCondition(is(['manager', 'admin']), next)
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
            beforeEnter: conditions.isLogged
        }, {
            path: '/jogs/:id',
            component: JogPage,
            beforeEnter: conditions.isLogged
        }, {
            path: '/sign-up',
            component: SignUpPage,
            beforeEnter: conditions.isNotLogged
        }, {
            path: '/manage/users',
            component: UserListPage,
            beforeEnter: conditions.isManager
        }, {
            path: '/manage/users/:id',
            component: UserPage,
            beforeEnter: conditions.isManager
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
