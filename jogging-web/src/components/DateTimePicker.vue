<template>
    <div class="date-time-picker">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ datePlaceholder || t('date') }}</label>
                    <datepicker v-model="date" format="dd/MM/yyyy" input-class="form-control"></datepicker>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <form-input v-model="time" :label="timePlaceholder || t('time')" maxlength="5" :only="/[0-9:]/" @blur="updateDateAndTime()" @return="$emit('return')"></form-input>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker'
import moment from 'moment'
import FormInput from '@/components/FormInput'

export default {
    components: {
        Datepicker,
        FormInput
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
