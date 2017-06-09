<template>
    <div id="sign-up-page" class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ t('signUp') }}</h1>

                <alert v-if="error" type="danger" dismissable="true" :content="t(`error.signUp.${error.status}`)" @dismiss="error = ''"></alert>
                <div class="form-group">
                    <input class="form-control" type="text" :placeholder="t('name')" v-model="name" @keypress.enter="signUp()">
                </div>
                <alert v-if="isInvalid('name')" type="danger" :dismissable="false" :content="t(`error.${error.body.name[0]}`)"></alert>
                <div class="form-group">
                    <input class="form-control" type="text" :placeholder="t('email')" v-model="email" @keypress.enter="signUp()">
                </div>
                <alert v-if="isInvalid('email')" type="danger" :dismissable="false" :content="t(`error.${error.body.email[0]}`)"></alert>
                <div class="form-group">
                    <input class="form-control" type="password" :placeholder="t('password')" v-model="password" @keypress.enter="signUp()">
                </div>
                <alert v-if="isInvalid('password')" type="danger" :dismissable="false" :content="t(`error.${error.body.password[0]}`)"></alert>
                <input class="btn btn-primary btn-block-sm pull-right" type="submit" @click="signUp()" :value="t('signUp')">
            </div>
        </div>
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
                .post(this, 'users', this.getData())
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
