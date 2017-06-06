import Http from './Http'

const Auth = {
    login (context, credentials) {
        return Http
            .post(context, 'users/login', credentials)
            .then(response => {
                console.log(response)
            })
    }
}

export default Auth
