import Vue from 'vue'
import { Http as VueHttp } from 'vue-resource'

export default new Vue({
    data () {
        return {
            loading: 0,

            HTTP_SUCCESS: 200,
            HTTP_CREATED: 201,
            HTTP_BAD_REQUEST: 400,
            HTTP_FORBIDDEN: 403,
            HTTP_NOT_FOUND: 404,
            HTTP_SERVER_ERROR: 500
        }
    },
    methods: {
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
            this.loading++

            return new Promise(resolve => {
                VueHttp[method](...args)
                    .then(function () {
                        this.loading--
                        resolve(...arguments)
                    }.bind(this))
                    .catch(err => {
                        this.loading--
                        context.error = err
                    })
            })
        }
    }
})
