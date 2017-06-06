// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import Resource from 'vue-resource'
import VueTranslate from 'vue-translate-plugin'

Vue.use(Resource)
Vue.use(VueTranslate)

import locales from './locales/locales'

Vue.locales(locales)

Vue.config.productionTip = false

Vue.http.options.root = '/api'

/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    template: '<App/>',
    components: { App },
    mounted () {
        this.$translate.setLang('en-us')
    }
})
