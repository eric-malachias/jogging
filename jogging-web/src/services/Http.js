import { Http as VueHttp } from 'vue-resource'

export default {
    delete (context, ...args) {
        return this.request(context, 'delete', args)
    },
    get (context, ...args) {
        return this.request(context, 'get', args)
    },
    post (context, ...args) {
        return this.request(context, 'post', args)
    },
    put (context, ...args) {
        return this.request(context, 'put', args)
    },
    request (context, method, args) {
        return new Promise(resolve => {
            VueHttp[method](...args)
                .then(resolve)
                .catch(err => {
                    context.error = err
                })
        })
    }
}
