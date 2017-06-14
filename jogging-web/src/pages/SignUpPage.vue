<template>
    <div id="sign-up-page" class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ t('signUp') }}</h1>

                <error-alert v-model="error" page="signUp"></error-alert>

                <form-input :label="t('name')" name="name" @return="signUp()" v-model="name" :error="error"></form-input>
                <form-input :label="t('email')" name="email" @return="signUp()" v-model="email" :error="error"></form-input>
                <form-input :label="t('password')" name="password" @return="signUp()" v-model="password" :error="error" type="password"></form-input>

                <input class="btn btn-primary btn-block-sm pull-right" type="submit" @click="signUp()" :value="t('signUp')">
            </div>
        </div>
    </div>
</template>

<script>
import FormInput from '@/components/FormInput'
import Auth from '@/services/Auth'
import Http from '@/services/Http'
import ErrorAlert from '@/components/ErrorAlert'

export default {
    components: {
        FormInput,
        ErrorAlert
    },
    data () {
        return {
            error: '',
            email: '',
            password: '',
            name: ''
        }
    },
    methods: {
        getData () {
            return {
                email: this.email,
                password: this.password,
                name: this.name
            }
        },
        isInvalid (field) {
            return this.error && this.error.body && this.error.body[field]
        },
        signUp () {
            Http
                .post(this, 'users/sign-up', this.getData())
                .then(response => {
                    Auth.saveUser(response.body)
                    this.$router.push('/jogs')
                })
        }
    }
}
</script>

<style scoped>
</style>
