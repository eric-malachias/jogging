<template>
    <div class="container">
        <h1>{{ t('manageUsers') }}</h1>

        <div class="main-actions clearfix">
            <button class="btn btn-primary pull-right" @click="create()">{{ t('createUser') }}</button>
        </div>

        <div class="user-list">
            <div class="wrapper">
                <error-alert v-model="error" page="users"></error-alert>
                <alert v-if="success" type="success" dismissable="true" :content="t('success.user.removed')" @dismiss="success = false" timer="5"></alert>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ t('name') }}</th>
                        <th>{{ t('email') }}</th>
                        <th>{{ t('role') }}</th>
                        <th>{{ t('createdAt') }}</th>
                        <th>{{ t('updatedAt') }}</th>
                        <th>{{ t('actions') }}</th>
                    </tr>
                </thead>
                <tbody v-if="pagination.total > 0">
                    <tr v-for="(user, index) in users" :key="user.id">
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.role && t(user.role.name) }}</td>
                        <td>{{ user.created_at | date('auto') }}</td>
                        <td>{{ user.updated_at | date('auto') }}</td>
                        <td>
                            <button class="btn btn-primary btn-xs" :title="t('hint.editUser')" @click="edit(user)"><span class="glyphicon glyphicon-pencil"></span></button>
                            <button class="btn btn-danger btn-xs" :title="t('hint.removeUser')" @click="remove(user, index)"><span class="glyphicon glyphicon-remove"></span></button>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else class="no-users text-center">
                    <tr v-if="!pagination.total">
                        <td colspan="5">{{ t('noUsers') }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination-container text-center" v-if="pagination.last_page > 1">
                <pagination :pagination="pagination" :callback="load" :options="paginationOptions"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
import Alert from '@/components/Alert'
import Http from '@/services/Http'
import Pagination from 'vue-bootstrap-pagination'
import ErrorAlert from '@/components/ErrorAlert'

export default {
    components: {
        Alert,
        Pagination,
        ErrorAlert
    },
    data () {
        return {
            error: '',
            users: [],
            pagination: {},
            paginationOptions: {
                offset: 1,
                alwaysShowPrevNext: true
            },
            success: false
        }
    },
    created () {
        this.load()
    },
    methods: {
        clearAlerts () {
            this.error = ''
            this.success = false
        },
        create () {
            this.$router.push('/users/new')
        },
        edit (user) {
            this.$router.push(`/users/${user.id}`)
        },
        load (page) {
            Http
                .get(this, `users`, {
                    params: {
                        page: page || this.pagination.current_page || 1
                    }
                })
                .then(response => {
                    this.users = response.body.data
                    this.pagination = response.body
                })
        },
        remove (user, index) {
            this.clearAlerts()

            Http
                .delete(this, `users/${user.id}`)
                .then(response => {
                    this.success = true
                    this.users.splice(index, 1)
                    this.load(
                        this.users.length > 0
                            ? this.pagination.current_page
                            : (this.pagination.current_page - 1) || 1
                    )
                })
        }
    }
}
</script>

<style scoped>
    .main-actions {
        padding: 15px 0;
    }
</style>
