<template>
    <div class="form-input">
        <label>{{ label }}</label>
        <div class="form-group">
            <input v-if="type === 'password'" class="form-control" type="password" v-model="localValue" @keypress.enter="hitReturn()" :maxlength="maxlength" @input="update(localValue, $event)" @blur="blur()">
            <input v-else class="form-control" type="text" v-model="localValue" @keypress.enter="hitReturn()" :maxlength="maxlength" @input="update(localValue, $event)" @blur="blur()">
        </div>
        <alert v-if="isInvalid()" type="danger" :dismissable="false" :content="getError()"></alert>
    </div>
</template>

<script>
import Alert from '@/components/Alert'

const DEFAULT_ERROR = 'error.invalid'

export default {
    props: ['name', 'maxlength', 'value', 'error', 'label', 'type'],
    components: {
        Alert
    },
    data () {
        return {
            localValue: ''
        }
    },
    watch: {
        value (value) {
            this.localValue = value
        }
    },
    created () {
    },
    methods: {
        blur () {
            this.$emit('blur')
        },
        getError () {
            const errorCode = `error.${this.error.body[this.name][0]}`
            const translatedError = this.t(errorCode)

            if (errorCode === translatedError) {
                return this.t(DEFAULT_ERROR)
            }

            return translatedError
        },
        hitReturn () {
            this.$emit('return')
        },
        isInvalid () {
            return this.error && this.error.body && this.error.body[this.name]
        },
        update (value, event) {
            this.$emit('input', this.localValue)
        }
    }
}
</script>
