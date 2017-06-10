<template>
    <div id="jog-page" class="container">
        <h1>{{ t('jog') }}</h1>

        <alert v-if="success" type="success" dismissable="true" :content="t('success.jog.saved')" @dismiss="success = false"></alert>
        <alert v-if="error" type="danger" dismissable="true" :content="t(`error.jogs.${error.status}`)" @dismiss="error = ''"></alert>
        <date-time-picker v-model="jog.started_at" :date-placeholder="t('startedAtDate')" :time-placeholder="t('startedAtTime')" @return="saveJog()"></date-time-picker>
        <alert v-if="isInvalid('started_at')" type="danger" :dismissable="false" :content="t(`error.${error.body.started_at[0]}`)"></alert>
        <div class="form-group">
            <input class="form-control" type="text" :placeholder="t('distance')" v-model="jog.distance" @keypress.enter="saveJog()">
        </div>
        <alert v-if="isInvalid('distance')" type="danger" :dismissable="false" :content="t(`error.${error.body.distance[0]}`)"></alert>
        <div class="form-group">
            <input class="form-control" type="text" :placeholder="t('duration')" v-model="duration" @keypress.enter="saveJog()" maxlength="3">
        </div>
        <alert v-if="isInvalid('ended_at')" type="danger" :dismissable="false" :content="t(`error.${error.body.ended_at[0]}`)"></alert>

        <input class="btn btn-primary btn-block-sm pull-right" type="submit" @click="saveJog()" :value="t('saveJog')">
    </div>
</template>

<script>
import Alert from '@/components/Alert'
import Http from '@/services/Http'
import Jog from '@/services/Jog'
import DateTimePicker from '@/components/DateTimePicker'

export default {
    components: {
        Alert,
        DateTimePicker
    },
    data () {
        return {
            duration: '',
            error: '',
            jog: {
                id: this.$route.params.id
            },
            startedAt: {
                date: '',
                time: ''
            },
            success: ''
        }
    },
    watch: {
        'jog.ended_at' (endedAt) {
            this.duration = Jog.calculateDuration(this.jog.started_at, endedAt)
        },
        'startedAt.date' (date) {
            this.updateStartAt()
        },
        'startedAt.time' (time) {
            this.updateStartAt()
        }
    },
    created () {
        if (!this.isNew()) {
            this.loadJog()
        }
    },
    methods: {
        clearAlerts () {
            this.error = ''
            this.success = false
        },
        getData () {
            this.jog.ended_at = Jog.calculateEndedAt(this.jog.started_at, this.duration)

            return this.jog
        },
        getRequest () {
            if (this.isNew()) {
                return Http.post(this, 'jogs', this.getData())
            }

            return Http.put(this, `jogs/${this.jog.id}`, this.getData())
        },
        goBack () {
            this.$router.push('/jogs')
        },
        isNew () {
            return this.jog.id === 'new'
        },
        isInvalid (field) {
            return this.error && this.error.body && this.error.body[field]
        },
        loadJog () {
            Http
                .get(this, `jogs/${this.jog.id}`)
                .then(response => {
                    this.jog = response.body
                })
        },
        saveJog () {
            this.clearAlerts()

            this
                .getRequest()
                .then(response => {
                    if (this.isNew()) {
                        this.goBack()
                        return
                    }

                    this.jog = response.body
                    this.success = true
                })
        }
    }
}
</script>

<style scoped>
</style>
