<template>
    <div class="jog-list">
        <div class="wrapper">
            <alert v-if="success" type="success" dismissable="true" :content="t('success.jog.removed')" @dismiss="success = false" timer="5"></alert>
            <alert v-if="error" type="danger" dismissable="true" :content="t(`error.jogs.${error.status}`)" @dismiss="error = ''"></alert>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>{{ t('startedAt') }}</th>
                    <th>{{ t('distance') }}</th>
                    <th>{{ t('duration') }}</th>
                    <th>{{ t('speed') }}</th>
                    <th>{{ t('actions') }}</th>
                </tr>
            </thead>
            <tbody v-if="pagination.total > 0">
                <tr v-for="(jog, index) in jogs" :key="jog.id">
                    <td>{{ jog.started_at | date }}</td>
                    <td>{{ jog.distance | meters }}</td>
                    <td>{{ calculateDuration(jog) | minutes }}</td>
                    <td>{{ calculateSpeed(jog) | speed }}</td>
                    <td>
                        <button class="btn btn-primary btn-xs" :title="t('hint.editJog')" @click="edit(jog)"><span class="glyphicon glyphicon-pencil"></span></button>
                        <button class="btn btn-danger btn-xs" :title="t('hint.removeJog')" @click="remove(jog, index)"><span class="glyphicon glyphicon-remove"></span></button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else class="no-jogs text-center">
                <tr v-if="!pagination.total">
                    <td colspan="5">{{ t('noJogs') }}</td>
                </tr>
            </tbody>
        </table>
        <div class="pagination-container text-center" v-if="pagination.last_page > 1">
            <pagination :pagination="pagination" :callback="load" :options="paginationOptions"></pagination>
        </div>
    </div>
</template>

<script>
import Alert from '@/components/Alert'
import Auth from '@/services/Auth'
import Http from '@/services/Http'
import Jog from '@/services/Jog'
import Pagination from 'vue-bootstrap-pagination'

export default {
    components: {
        Alert,
        Pagination
    },
    props: ['filters', 'refresh-token'],
    data () {
        return {
            error: '',
            jogs: [],
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
    watch: {
        refreshToken () {
            this.load(1)
        }
    },
    methods: {
        calculateDuration (jog) {
            if (jog.duration) {
                return jog.duration
            }

            jog.duration = Jog.calculateDuration(jog.started_at, jog.ended_at)

            return jog.duration
        },
        calculateSpeed (jog) {
            if (jog.speed) {
                return jog.speed
            }

            const distance = jog.distance
            const duration = this.calculateDuration(jog)

            jog.speed = Jog.calculateSpeed(distance, duration)

            return jog.speed
        },
        clearAlerts () {
            this.error = ''
            this.success = false
        },
        edit (jog) {
            this.$router.push(`/jogs/${jog.id}`)
        },
        load (page) {
            Http
                .get(this, `users/${Auth.user.id}/jogs`, {
                    params: {
                        page: page || this.pagination.current_page || 1,
                        before: this.filters.to,
                        after: this.filters.from
                    }
                })
                .then(response => {
                    this.jogs = response.body.data
                    this.pagination = response.body
                })
        },
        remove (jog, index) {
            this.clearAlerts()

            Http
                .delete(this, `jogs/${jog.id}`)
                .then(response => {
                    this.$emit('jog-remove')
                    this.success = true
                    this.jogs.splice(index, 1)
                    this.load(
                        this.jogs.length > 0
                            ? this.pagination.current_page
                            : (this.pagination.current_page - 1) || 1
                    )
                })
        }
    }
}
</script>

<style scoped>
    table {
        table-layout: fixed;
    }
</style>
