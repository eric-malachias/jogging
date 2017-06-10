<template>
    <div class="date-time-picker">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <datepicker v-model="date" format="yyyy-MM-dd" input-class="form-control" :placeholder="t('startedAtDate')"></datepicker>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <input class="form-control" type="text" :placeholder="t('startedAtTime')" v-model="time" maxlength="8" @blur="updateDateAndTime(true)" @keypress.enter="$emit('return')">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker'
import moment from 'moment'

export default {
    components: {
        Datepicker
    },
    props: ['date-placeholder', 'time-placeholder', 'value'],
    data () {
        return {
            date: '',
            time: ''
        }
    },
    watch: {
        date () {
            this.updateValue()
        },
        time () {
            this.updateValue()
        },
        value () {
            this.updateDateAndTime()
        }
    },
    methods: {
        getDate () {
            let date = moment(this.date)

            if (!date.isValid()) {
                date = moment()
            }

            return date.format('YYYY-MM-DD')
        },
        getTime (time = null) {
            time = moment(time || this.time, ['H:m', 'h:m a'])

            if (!time.isValid()) {
                time = moment()
            }

            return time.format('HH:mm')
        },
        updateDateAndTime (force = false) {
            let value = moment(this.value, ['YYYY-MM-DD H:m'])

            if (!value.isValid()) {
                value = moment()
            }

            this.date = value.toDate()

            const timeString = value.format('HH:mm')

            if (force || this.getTime() !== this.getTime(timeString)) {
                this.time = timeString
            }
        },
        updateValue () {
            const value = this.getDate() + ' ' + this.getTime()

            this.$emit('input', value)
        }
    }
}
</script>
