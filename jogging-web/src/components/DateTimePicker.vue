<template>
    <div class="date-time-picker">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <datepicker v-model="date" format="yyyy-MM-dd" input-class="form-control" :placeholder="datePlaceholder || t('date')"></datepicker>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <input class="form-control" type="text" :placeholder="timePlaceholder || t('time')" v-model="time" maxlength="8" @blur="updateDateAndTime()" @keypress.enter="$emit('return')">
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
            time: '',
            updating: false
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
        clearDateAndTime () {
            this.date = ''
            this.time = ''
        },
        getDate () {
            let date = moment(this.date)

            if (!date.isValid()) {
                date = moment()
            }

            return date.format('YYYY-MM-DD')
        },
        getTime () {
            let time = moment(this.time, ['H:m', 'h:m a'])

            if (!time.isValid()) {
                time = moment()
            }

            return time.format('HH:mm')
        },
        isEmpty () {
            return !this.date && !this.time
        },
        updateDateAndTime () {
            if (this.updating) {
                return
            }
            if (!this.value) {
                this.clearDateAndTime()
                return
            }

            let value = moment(this.value, ['YYYY-MM-DD H:m'])

            if (!value.isValid()) {
                this.date = moment().toDate()
                this.time = '00:00'
                return
            }

            this.date = value.toDate()
            this.time = value.format('HH:mm')
        },
        updateValue () {
            if (this.isEmpty()) {
                this.$emit('input', '')
                return
            }

            const value = this.getDate() + ' ' + this.getTime()

            this.updating = true
            this.$emit('input', value)

            setTimeout(() => {
                this.updating = false
            }, 1)
        }
    }
}
</script>
