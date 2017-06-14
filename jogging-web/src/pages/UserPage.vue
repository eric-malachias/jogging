<template>
    <div id="user-page" class="container">
        <h1>{{ user.name || t('noName') }}</h1>

        <div class="wrapper">
            <alert v-if="success" type="success" dismissable="true" :content="t('success.user.saved')" @dismiss="success = false"></alert>
            <alert v-if="error" type="danger" dismissable="true" :content="t(`error.users.${error.status}`)" @dismiss="error = ''"></alert>
        </div>

        <form-input :label="t('name')" v-model="user.name" @return="save()" :error="error" name="name"></form-input>
        <form-input :label="t('email')" v-model="user.email" @return="save()" :error="error" name="email"></form-input>

        <button class="btn btn-default" @click="showPassword = !showPassword">
            <span v-if="showPassword">{{ t('hidePassword') }}</span>
            <span v-else>{{ t('showPassword') }}</span>
        </button>
        <div v-if="showPassword" class="password-container">
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

export default {
    components: {
        Alert,
        FormInput
    },
    data () {
        return {
            error: '',
            showPassword: false,
            user: {
                id: this.$route.params.id || (this.isSelf() && Auth.user.id),
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
        }
    },
    methods: {
        clearAlerts () {
            this.error = ''
            this.success = false
        },
        getData () {
            let data = {
                name: this.user.name,
                email: this.user.email,
                role_id: this.user.role_id,
                password: this.user.password
            }

            if (!this.showPassword || !this.user.password) {
                delete data.password
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
        loadRoles () {
            Http
                .get(this.roles, 'roles')
                .then(response => {
                    this.roles = response.body
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
    .password-container {
        margin-top: 15px;
    }
</style>
