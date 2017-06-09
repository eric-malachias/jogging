<template>
    <div class="alert alert-dismissable" :class="getClass()">
        <span class="glyphicon" :class="getIcon()"></span>
        <span v-if="getDismissable()" class="close" data-dismiss="alert" :aria-label="t('hint.dismissAlert')" :title="t('hint.dismissAlert')" @click="dismiss()">×</span>
        {{ content }}
    </div>
</template>

<script>
const ICONS = {
    danger: 'remove-sign',
    info: 'info-sign',
    success: 'ok-sign',
    warning: 'exclamation-sign'
}

export default {
    name: 'alert',
    props: ['content', 'dismissable', 'timer', 'type'],
    created () {
        if (this.timer) {
            setTimeout(() => this.dismiss(), this.timer * 1000)
        }
    },
    methods: {
        dismiss () {
            this.$emit('dismiss')
        },
        getClass () {
            return ['alert-' + this.getType()]
        },
        getDismissable () {
            return this.dismissable === undefined || this.dismissable
        },
        getIcon () {
            return 'glyphicon-' + ICONS[this.getType()]
        },
        getType () {
            return this.type || 'info'
        }
    }
}
</script>

<style scoped>
    .glyphicon {
        margin-right: 5px;
    }
</style>
