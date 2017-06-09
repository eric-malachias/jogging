import Http from './Http'
import Storage from './Storage'
import Vue from 'vue'

function forgetUser () {
    Storage.Session.remove('user')
}
function getUser () {
    return Storage.Session.get('user', '')
}
function saveUser (data) {
    Storage.Session.set('user', data)
}

const Auth = new Vue({
    data () {
        return {
            user: ''
        }
    },
    methods: {
        checkUser () {
            this.user = getUser()
        },
        getHeader () {
            const user = this.user

            if (!user) {
                return null
            }

            return `Bearer ${user.token}`
        },
        logout () {
            return new Promise((resolve) => {
                this.user = ''
                forgetUser()
                resolve()
            })
        },
        login (context, credentials) {
            return Http
                .post(context, 'users/login', credentials)
                .then(response => {
                    this.saveUser(response.body)
                })
        },
        saveUser (data) {
            this.user = data
            saveUser(data)
        }
    }
})

export default Auth
