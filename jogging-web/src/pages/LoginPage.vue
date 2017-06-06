<template>
    <div id="login-page" class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ t('login') }}</h1>

                <alert v-if="error" type="danger" dismissable="true" :content="t(`error.login.${error.status}`)" @dismiss="error = null"></alert>
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
import Alert from '@/components/Alert'

export default {
    components: {
        Alert
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
                    this.$router.push('/jogs')
                })
        }
    }
}
</script>

<style scoped>
</style>
