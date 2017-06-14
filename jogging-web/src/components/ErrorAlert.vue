<template>
    <div class="error-alert">
        <alert v-if="value" type="danger" dismissable="true" :content="getContent()" @dismiss="dismiss()"></alert>
    </div>
</template>

<script>
import Alert from './Alert'

export default {
    props: ['value', 'page'],
    components: {
        Alert
    },
    methods: {
        dismiss () {
            this.$emit('input', '')
        },
        getContent () {
            const errorCode = `error.${this.page}.${this.value.status}`
            const translatedError = this.t(errorCode)

            if (errorCode === translatedError) {
                return this.t(`error.${this.value.status}`)
            }

            return translatedError
        }
    }
}
</script>
