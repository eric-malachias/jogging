import Http from './Http'
import Storage from './Storage'

const Auth = {
    getHeader () {
        const user = this.getUser()

        if (!user) {
            return null
        }

        return `Bearer ${user.token}`
    },
    getUser () {
        return Storage.Session.get('user', '')
    },
    login (context, credentials) {
        return Http
            .post(context, 'users/login', credentials)
            .then(response => {
                this.saveUser(response.body)
            })
    },
    saveUser (data) {
        Storage.Session.set('user', data)
    }
}

export default Auth
