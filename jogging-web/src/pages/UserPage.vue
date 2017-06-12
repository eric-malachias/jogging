<template>
    <div id="user-page" class="container">
        <h1>{{ user.name || t('noName') }}</h1>

        <div class="wrapper">
            <alert v-if="success" type="success" dismissable="true" :content="t('success.user.saved')" @dismiss="success = false"></alert>
            <alert v-if="error" type="danger" dismissable="true" :content="t(`error.users.${error.status}`)" @dismiss="error = ''"></alert>
        </div>

        <div class="form-group">
            <input class="form-control" type="text" :placeholder="t('name')" v-model="user.name" @keypress.enter="save()">
        </div>
        <alert v-if="isInvalid('name')" type="danger" :dismissable="false" :content="t(`error.${error.body.name[0]}`)"></alert>
        <div class="form-group">
            <input class="form-control" type="text" :placeholder="t('email')" v-model="user.email" @keypress.enter="save()">
        </div>
        <alert v-if="isInvalid('email')" type="danger" :dismissable="false" :content="t(`error.${error.body.email[0]}`)"></alert>
        <div class="form-group">
            <input class="form-control" type="password" :placeholder="t('password')" v-model="user.password" @keypress.enter="save()">
        </div>
        <alert v-if="isInvalid('password')" type="danger" :dismissable="false" :content="t(`error.${error.body.password[0]}`)"></alert>

        <button v-if="!isSelf()" class="btn btn-default btn-block-sm" type="submit" @click="goBack()">{{ t('backToUsers') }}</button>
        <button class="btn btn-primary btn-block-sm pull-right" type="submit" @click="save()">{{ t('saveUser') }}</button>
    </div>
</template>

<script>
import Alert from '@/components/Alert'
import Auth from '@/services/Auth'
import Http from '@/services/Http'

export default {
    components: {
        Alert
    },
    data () {
        return {
            error: '',
            user: {
                id: this.$route.params.id || (this.isSelf() && Auth.user.id),
                name: '',
                email: '',
                password: ''
            },
            success: ''
        }
    },
    created () {
        if (!this.isNew()) {
            this.load()
        }
    },
    watch: {
        error (error) {
            if (error.status === Http.HTTP_FORBIDDEN) {
                this.goBack()
            }
        }
    },
    methods: {
        clearAlerts () {
            this.error = ''
            this.success = false
        },
        getRequest () {
            if (this.isNew()) {
                return Http.post(this, 'users', this.user)
            }

            return Http.put(this, `users/${this.user.id}`, this.user)
        },
        goBack () {
            this.$router.push('/users')
        },
        isNew () {
            return this.user.id === 'new'
        },
        isSelf () {
            return this.$route.path === '/me'
        },
        isInvalid (field) {
            return this.error && this.error.body && this.error.body[field]
        },
        load () {
            Http
                .get(this, `users/${this.user.id}`)
                .then(response => {
                    this.user = response.body
                })
        },
        save () {
            this.clearAlerts()

            this
                .getRequest()
                .then(response => {
                    if (this.isNew()) {
                        this.goBack()
                        return
                    }

                    this.user = response.body
                    this.success = true

                    if (this.isSelf()) {
                        Auth.copyUser(this.user)
                    }
                })
        }
    }
}
</script>

<style scoped>
</style>
