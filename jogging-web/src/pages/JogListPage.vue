<template>
    <div id="jog-list-page" class="container">
        <div class="main-actions">
            <button class="btn btn-primary" @click="createJog()">{{ t('createJog') }}</button>
        </div>
        <table v-if="loaded" class="table">
            <thead>
                <tr>
                    <th>{{ t('startedAt') }}</th>
                    <th>{{ t('distance') }}</th>
                    <th>{{ t('duration') }}</th>
                    <th>{{ t('speed') }}</th>
                    <th>{{ t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(jog, index) in jogs" :key="jog.id">
                    <td>{{ jog.started_at | date }}</td>
                    <td>{{ jog.distance | distance }}</td>
                    <td>{{ calculateDuration(jog) | duration }}</td>
                    <td>{{ calculateSpeed(jog) | speed }}</td>
                    <td>
                        <button class="btn btn-primary btn-xs" :title="t('hint.editJog')" @click="editJog(jog)"><span class="glyphicon glyphicon-pencil"></span></button>
                        <button class="btn btn-danger btn-xs" :title="t('hint.removeJog')" @click="removeJog(jog, index)"><span class="glyphicon glyphicon-remove"></span></button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="pagination-container">
            <pagination v-if="loaded" :pagination="pagination" :callback="loadJogs" :options="paginationOptions"></pagination>
        </div>
    </div>
</template>

<script>
import Auth from '@/services/Auth'
import Http from '@/services/Http'
import moment from 'moment'
import Pagination from 'vue-bootstrap-pagination'

export default {
    components: {
        Pagination
    },
    data () {
        return {
            loaded: false,
            jogs: [],
            pagination: {},
            paginationOptions: {
                offset: 1,
                alwaysShowPrevNext: true
            }
        }
    },
    created () {
        this.loadJogs()
    },
    methods: {
        calculateDuration (jog) {
            if (jog.duration) {
                return jog.duration
            }

            const startedAt = moment(jog.started_at)
            const endedAt = moment(jog.ended_at)

            const duration = moment.duration(endedAt.diff(startedAt)).asMinutes()

            jog.duration = duration

            return jog.duration
        },
        calculateSpeed (jog) {
            if (jog.speed) {
                return jog.speed
            }

            const distance = jog.distance / 1000
            const duration = this.calculateDuration(jog) / 60

            const speed = distance / duration

            jog.speed = speed

            return jog.speed
        },
        createJog () {
            this.$router.push('/jogs/new')
        },
        editJog (jog) {
            this.$router.push(`/jogs/${jog.id}`)
        },
        loadJogs (page) {
            Http
                .get(this, `users/${Auth.user.id}/jogs`, {
                    params: {
                        page: page || this.pagination.current_page || 1
                    }
                })
                .then(response => {
                    this.jogs = response.body.data
                    this.pagination = response.body
                    this.loaded = true
                })
        },
        removeJog (jog, index) {
            Http
                .delete(this, `jogs/${jog.id}`)
                .then(response => {
                    this.jogs.splice(index, 1)
                    this.loadJogs(
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
    .main-actions {
        padding: 15px 0;
    }
    .pagination-container {
        text-align: center;
    }
    table {
        table-layout: fixed;
    }
</style>
