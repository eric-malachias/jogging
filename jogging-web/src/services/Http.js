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
        return VueHttp[method](...args)
            .catch(err => {
                context.error = err
            })
    }
}
