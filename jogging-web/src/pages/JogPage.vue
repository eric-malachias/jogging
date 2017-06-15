<template>
    <div id="jog-page" class="container">
        <h1>{{ t('jog') }}</h1>

        <div class="wrapper">
            <error-alert v-model="error" page="jogs"></error-alert>
            <alert v-if="success" type="success" dismissable="true" :content="t('success.jog.saved')" @dismiss="success = false"></alert>
        </div>
        <date-time-picker v-model="jog.started_at" :date-placeholder="t('startedAtDate')" :time-placeholder="t('startedAtTime')" @return="saveJog()"></date-time-picker>
        <alert v-if="isInvalid('started_at')" type="danger" :dismissable="false" :content="t(`error.${error.body.started_at[0]}`)"></alert>

        <form-input :label="t('distanceInMeters')" name="distance" v-model="jog.distance" @return="saveJog()" :error="error"></form-input>
        <form-input :label="t('durationInMinutes')" name="ended_at" v-model="duration" @return="saveJog()" maxlength="3" :error="error"></form-input>

        <div v-if="isAdmin()" class="owner">
            <type-ahead url="search/users" property="name" :placeholder="t('owner')" v-model="jog.owner" @return="saveJog()"></type-ahead>
            <alert v-if="isInvalid('owner_id')" type="danger" :dismissable="false" :content="t(`error.${error.body.owner_id[0]}`)"></alert>
        </div>

        <button v-if="isAdmin()" class="btn btn-default btn-block-sm" type="submit" @click="goBack()">{{ t('backToManageJogs') }}</button>
        <button v-else class="btn btn-default btn-block-sm" type="submit" @click="goBack()">{{ t('backToMyJogs') }}</button>
        <button class="btn btn-primary btn-block-sm pull-right" type="submit" @click="saveJog()">{{ t('saveJog') }}</button>
    </div>
</template>

<script>
import Alert from '@/components/Alert'
import Http from '@/services/Http'
import Jog from '@/services/Jog'
import DateTimePicker from '@/components/DateTimePicker'
import TypeAhead from '@/components/TypeAhead'
import Auth from '@/services/Auth'
import FormInput from '@/components/FormInput'
import ErrorAlert from '@/components/ErrorAlert'

export default {
    components: {
        Alert,
        DateTimePicker,
        TypeAhead,
        FormInput,
        ErrorAlert
    },
    data () {
        return {
            duration: '',
            error: '',
            jog: {
                id: this.$route.params.id,
                started_at: '',
                ended_at: '',
                distance: '',
                owner: '',
                owner_id: Auth.user.id
            },
            success: ''
        }
    },
    watch: {
        error (error) {
            if (error.status === Http.HTTP_FORBIDDEN) {
                this.goBack()
            }
        },
        'jog.ended_at' (endedAt) {
            let duration = Jog.calculateDuration(this.jog.started_at, endedAt)

            if (isNaN(duration)) {
                duration = ''
            }

            this.duration = duration
        },
        'jog.owner' (owner) {
            this.jog.owner_id = owner && owner.id || null
        },
        'jog.owner.id' (id) {
            this.jog.owner_id = id
        },
        '$route.path' (path) {
            if (!this.isNew()) {
                this.loadJog()
                return
            }

            this.clearJog()
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
        clearJog () {
            this.jog.id = this.$route.params.id || ''
            this.jog.distance = ''
            this.jog.started_at = ''
            this.jog.ended_at = ''
            this.jog.owner = ''
            this.jog.owner_id = Auth.user.id
            this.duration = ''
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
        getUsers () {
            return []
        },
        isAdmin () {
            return Auth.isAdmin()
        },
        isNew () {
            return this.$route.params.id === 'new'
        },
        isInvalid (field) {
            return this.error && this.error.body && this.error.body[field]
        },
        loadJog () {
            this.clearJog()

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

                    Object.assign(this.jog, response.body)
                    this.success = true
                })
        }
    }
}
</script>
