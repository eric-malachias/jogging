import moment from 'moment'
import Vue from 'vue'

function translate () {
    return Vue.prototype.$translate.text(...arguments)
}

export default {
    boot () {
        Vue.filter('duration', function (value) {
            if (value === 0) {
                return '0'
            }
            if (value === 1) {
                return translate('oneMinute')
            }

            return value + ' ' + translate('minutes')
        })
        Vue.filter('date', function (value) {
            return moment(value).format('DD/MM/YYYY HH:mm')
        })
        Vue.filter('distance', function (value) {
            return `${value}m`
        })
    }
}
