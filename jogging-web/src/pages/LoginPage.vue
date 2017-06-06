<template>
    <div id="login-page" class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ t('login') }}</h1>

                <div v-if="error">{{ t(`error.login.${error.status}`) }}</div>
                <div class="form-group">
                    <input class="form-control" type="text" :placeholder="t('email')" v-model="email" @keypress.enter="login()">
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" :placeholder="t('password')" v-model="password" @keypress.enter="login()">
                </div>
                <input class="btn btn-primary btn-block-sm pull-right" type="submit" @click="login()" :value="t('login')">
            </div>
        </div>
    </div>
</template>

<script>
import Auth from '@/services/Auth'

export default {
    data () {
        return {
            error: '',
            email: '',
            password: ''
        }
    },
    methods: {
        login () {
            Auth.login(this, {
                email: this.email,
                password: this.password
            })
        }
    }
}
</script>

<style scoped>
</style>
