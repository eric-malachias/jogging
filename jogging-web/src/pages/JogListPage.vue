<template>
    <div id="jog-list-page" class="container">
        <h1>{{ t('myJogs') }}</h1>
        <div class="main-actions">
            <div class="row">
                <div class="col-xs-6">
                    <button class="btn btn-default" @click="showFilters = !showFilters">
                        {{ t('filterJogs') }}
                        <span v-if="!showFilters && getFilterCount()" class="badge">{{ getFilterCount() }}</span>
                    </button>
                </div>
                <div class="col-xs-6">
                    <button class="btn btn-primary pull-right" @click="createJog()">{{ t('createJog') }}</button>
                </div>
            </div>
            <div :class="showFilters ? '' : 'hidden'" class="filters">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ t('filters') }}</div>
                    <div class="panel-body">
                        <div class="col-sm-6">
                            <strong>
                                {{ t('from') }}
                                (<a class="link clear-button" @click="filters.from = ''">{{ t('clear') }}</a>)
                            </strong>
                            <date-time-picker v-model="filters.from"></date-time-picker>
                        </div>
                        <div class="col-sm-6">
                            <strong>
                                {{ t('to') }}
                                (<a class="link clear-button" @click="filters.to = ''">{{ t('clear') }}</a>)
                            </strong>
                            <date-time-picker v-model="filters.to"></date-time-picker>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                    <td>{{ jog.distance | distance }}</td>
                    <td>{{ calculateDuration(jog) | duration }}</td>
                    <td>{{ calculateSpeed(jog) | speed }}</td>
                    <td>
                        <button class="btn btn-primary btn-xs" :title="t('hint.editJog')" @click="editJog(jog)"><span class="glyphicon glyphicon-pencil"></span></button>
                        <button class="btn btn-danger btn-xs" :title="t('hint.removeJog')" @click="removeJog(jog, index)"><span class="glyphicon glyphicon-remove"></span></button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else class="no-jogs">
                <tr v-if="!pagination.total">
                    <td colspan="5">{{ t('noJogs') }}</td>
                </tr>
            </tbody>
        </table>
        <div class="pagination-container">
            <pagination v-if="pagination.total > 1" :pagination="pagination" :callback="loadJogs" :options="paginationOptions"></pagination>
        </div>
    </div>
</template>

<script>
import Auth from '@/services/Auth'
import Http from '@/services/Http'
import Pagination from 'vue-bootstrap-pagination'
import Jog from '@/services/Jog'
import DateTimePicker from '@/components/DateTimePicker'
import _ from 'lodash'
import Alert from '@/components/Alert'

export default {
    components: {
        Pagination,
        DateTimePicker,
        Alert
    },
    data () {
        return {
            error: '',
            filters: {
                from: '',
                to: ''
            },
            jogs: [],
            pagination: {},
            paginationOptions: {
                offset: 1,
                alwaysShowPrevNext: true
            },
            showFilters: false,
            success: false,
            _loadJogs: null
        }
    },
    created () {
        this.loadJogs()
    },
    watch: {
        'filters.from' () {
            this.applyFilters()
        },
        'filters.to' () {
            this.applyFilters()
        }
    },
    methods: {
        applyFilters () {
            if (this._loadJogs) {
                this._loadJogs(1)
                return
            }

            this._loadJogs = _.debounce(this.loadJogs, 200).bind(this)

            return this.applyFilters()
        },
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

            const distance = jog.distance / 1000
            const duration = this.calculateDuration(jog) / 60

            const speed = distance / duration

            jog.speed = speed

            return jog.speed
        },
        clearAlerts () {
            this.error = ''
            this.success = false
        },
        createJog () {
            this.$router.push('/jogs/new')
        },
        editJog (jog) {
            this.$router.push(`/jogs/${jog.id}`)
        },
        getFilterCount () {
            let count = 0

            for (const key in this.filters) {
                count += (this.filters[key] && 1) || 0
            }

            return count
        },
        loadJogs (page) {
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
        removeJog (jog, index) {
            this.clearAlerts()

            Http
                .delete(this, `jogs/${jog.id}`)
                .then(response => {
                    this.success = true
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
    .clear-button {
        cursor: pointer;
    }
    .filters {
        margin-bottom: -15px;
        margin-top: 15px;
    }
    .filter.hidden {
        display: none;
    }
    .main-actions {
        padding: 15px 0;
    }
    .no-jogs {
        text-align: center;
    }
    .pagination-container {
        text-align: center;
    }
    table {
        table-layout: fixed;
    }
</style>
