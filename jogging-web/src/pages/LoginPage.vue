<template>
    <div id="login-page" class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ t('login') }}</h1>

                <error-alert v-model="error" page="login"></error-alert>
                <div class="form-group">
                    <label>{{ t('email') }}</label>
                    <input class="form-control" type="text" v-model="email" @keypress.enter="login()">
                </div>
                <div class="form-group">
                    <label>{{ t('password') }}</label>
                    <input class="form-control" type="password" v-model="password" @keypress.enter="login()">
                </div>
                <input class="btn btn-primary btn-block-sm pull-right" type="submit" @click="login()" :value="t('login')">
            </div>
        </div>
    </div>
</template>

<script>
import Auth from '@/services/Auth'
import ErrorAlert from '@/components/ErrorAlert'
import User from '@/services/User'

export default {
    components: {
        ErrorAlert
    },
    data () {
        return {
            error: '',
            email: '',
            password: ''
        }
    },
    methods: {
        login () {
            Auth
                .login(this, {
                    email: this.email,
                    password: this.password
                })
                .then(res => {
                    this.$router.push(User.homePage[Auth.user.role])
                })
        }
    }
}
</script>

<style scoped>
</style>
