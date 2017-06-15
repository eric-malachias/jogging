<template>
    <div id="user-page" class="container">
        <h1>{{ user.name || t('noName') }}</h1>

        <div class="wrapper">
            <error-alert v-model="error" page="users"></error-alert>
            <alert v-if="success" type="success" dismissable="true" :content="t('success.user.saved')" @dismiss="success = false"></alert>
        </div>

        <form-input :label="t('name')" v-model="user.name" @return="save()" :error="error" name="name"></form-input>
        <form-input :label="t('email')" v-model="user.email" @return="save()" :error="error" name="email"></form-input>

        <button v-if="!isNew()" class="btn btn-default show-password-button" @click="showPassword = !showPassword">
            <span v-if="showPassword">{{ t('hidePassword') }}</span>
            <span v-else>{{ t('showPassword') }}</span>
        </button>
        <div v-if="showPassword || isNew()" class="password-container">
            <form-input :label="t('password')" v-model="user.password" @return="save()" :error="error" name="password" type="password"></form-input>
        </div>

        <div v-if="roles.length > 0 && isAdmin() && !isSelf()" class="role">
            <div class="form-group">
                <label>{{ t('role') }}</label>
                <select class="form-control" v-model="user.role_id">
                    <option v-for="role in roles" :key="role.id" :value="role.id" v-if="role.name !== 'admin'">
                        {{ t(role.name) }}
                    </option>
                </select>
            </div>
            <alert v-if="isInvalid('role_id')" type="danger" :dismissable="false" :content="t(`error.${error.body.role_id[0]}`)"></alert>
        </div>

        <div class="clearfix">
            <button v-if="!isSelf()" class="btn btn-default btn-block-sm" type="submit" @click="goBack()">{{ t('backToUsers') }}</button>
            <button class="btn btn-primary btn-block-sm pull-right" type="submit" @click="save()">{{ t('saveUser') }}</button>
        </div>
    </div>
</template>

<script>
import Alert from '@/components/Alert'
import Auth from '@/services/Auth'
import Http from '@/services/Http'
import FormInput from '@/components/FormInput'
import ErrorAlert from '@/components/ErrorAlert'

export default {
    components: {
        Alert,
        FormInput,
        ErrorAlert
    },
    data () {
        return {
            error: '',
            showPassword: false,
            user: {
                id: '',
                name: '',
                email: '',
                password: '',
                role_id: ''
            },
            roles: [],
            success: ''
        }
    },
    created () {
        if (this.isAdmin()) {
            this.loadRoles()
        }

        if (!this.isNew()) {
            this.load()
        }
    },
    watch: {
        error (error) {
            if (error.status === Http.HTTP_FORBIDDEN) {
                this.goBack()
            }
        },
        '$route.path' (path) {
            if (!this.isNew()) {
                this.load()
                return
            }

            this.clearAlerts()
            this.clearUser()
        }
    },
    methods: {
        clearAlerts () {
            this.error = ''
            this.success = false
        },
        clearUser () {
            this.user.id = this.$route.params.id || (this.isSelf() && Auth.user.id)
            this.user.name = ''
            this.user.email = ''
            this.user.password = ''
            this.user.role_id = ''
        },
        getData () {
            let data = {
                name: this.user.name,
                email: this.user.email,
                role_id: this.user.role_id,
                password: this.user.password
            }

            if (!this.isNew() && (!this.showPassword || !this.user.password)) {
                delete data.password
            }
            if (!this.isAdmin()) {
                delete data.role_id
            }

            return data
        },
        getRequest () {
            if (this.isNew()) {
                return Http.post(this, 'users', this.getData())
            }

            return Http.put(this, `users/${this.user.id}`, this.getData())
        },
        goBack () {
            this.$router.push('/users')
        },
        isAdmin () {
            return Auth.isAdmin()
        },
        isNew () {
            return this.$route.params.id === 'new'
        },
        isSelf () {
            return this.$route.path === '/me'
        },
        isInvalid (field) {
            return this.error && this.error.body && this.error.body[field]
        },
        load () {
            this.clearAlerts()
            this.clearUser()

            Http
                .get(this, `users/${this.user.id}`)
                .then(response => {
                    this.user = response.body
                })
        },
        loadRoles () {
            Http
                .get(this.roles, 'roles')
                .then(response => {
                    this.roles = response.body
                })
        },
        save () {
            this.clearAlerts()

            console.log(this.getData())

            this
                .getRequest()
                .then(response => {
                    if (this.isNew()) {
                        this.goBack()
                        return
                    }

                    Object.assign(this.user, response.body)
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
    .show-password-button {
        margin-bottom: 15px;
    }
</style>
