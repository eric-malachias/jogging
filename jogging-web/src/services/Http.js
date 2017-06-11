import Vue from 'vue'
import { Http as VueHttp } from 'vue-resource'

export default new Vue({
    data () {
        return {
            loading: 0
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
