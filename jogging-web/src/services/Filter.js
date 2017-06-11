import moment from 'moment'
import Vue from 'vue'

function translate () {
    return Vue.prototype.$translate.text(...arguments)
}

const Filter = {
    boot () {
        for (const name in this.filters) {
            Vue.filter(name, this.filters[name])
        }
    },
    filters: {
        minutes (value) {
            if (value === 0) {
                return '0'
            }
            if (value === 1) {
                return translate('oneMinute')
            }

            return value + ' ' + translate('minutes')
        },
        date (value) {
            return moment(value).format('DD/MM/YYYY HH:mm')
        },
        meters (value) {
            return `${value} m`
        },
        speed (value) {
            return Filter.filters.round(Math.round(value * 10) / 10) + ' km/h'
        },
        kilometers (value) {
            return Filter.filters.round(value / 1000) + ' km'
        },
        hours (value) {
            return Filter.filters.round(value / 60, 2) + ' h'
        },
        round (value, precision = 1) {
            const [a, b] = value.toString().split('.')

            return [a, ((b || '') + '0'.repeat(precision)).substr(0, precision)].join('.')
        }
    }
}

export default Filter
