<template>
    <div id="jog-list-page" class="container">
        <div class="control-group">
            <button class="btn btn-primary">{{ t('createJog') }}</button>
        </div>
        <table v-if="loaded" class="table">
            <thead>
                <tr>
                    <th>{{ t('id') }}</th>
                    <th>{{ t('distance') }}</th>
                    <th>{{ t('startedAt') }}</th>
                    <th>{{ t('duration') }}</th>
                    <th>{{ t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="jog in jogs" :key="jog.id">
                    <td>{{ jog.id }}</td>
                    <td>{{ jog.distance | distance }}</td>
                    <td>{{ jog.started_at | date }}</td>
                    <td>{{ calculateDuration(jog) | duration }}</td>
                    <td>
                        <button class="btn btn-primary btn-xs" :title="t('hint.editJog')"><span class="glyphicon glyphicon-pencil"></span></button>
                        <button class="btn btn-danger btn-xs" :title="t('hint.removeJog')"><span class="glyphicon glyphicon-remove"></span></button>
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
            const startedAt = moment(jog.started_at)
            const endedAt = moment(jog.ended_at)

            const duration = moment.duration(endedAt.diff(startedAt))

            return duration.asMinutes()
        },
        loadJogs () {
            Http
                .get(this, `users/${Auth.getUser().id}/jogs`, {
                    params: {
                        page: this.pagination.current_page || 1
                    }
                })
                .then(response => {
                    this.jogs = response.body.data
                    this.pagination = response.body
                    this.loaded = true
                })
        }
    }
}
</script>

<style scoped>
    .pagination-container {
        text-align: center;
    }
</style>
