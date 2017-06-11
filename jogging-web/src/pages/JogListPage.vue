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
        <jog-list :filters="filters" :refresh-token="refresh.list"></jog-list>
        <jog-weekly-report :refresh-token="refresh.report"></jog-weekly-report>
    </div>
</template>

<script>
import DateTimePicker from '@/components/DateTimePicker'
import _ from 'lodash'
import JogWeeklyReport from './JogListPage/JogWeeklyReport'
import JogList from './JogListPage/JogList'

export default {
    components: {
        DateTimePicker,
        JogWeeklyReport,
        JogList
    },
    data () {
        return {
            filters: {
                from: '',
                to: ''
            },
            showFilters: false,
            refresh: {
                list: 0,
                rerport: 0
            },
            _loadJogs: null
        }
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
        createJog () {
            this.$router.push('/jogs/new')
        },
        getFilterCount () {
            let count = 0

            for (const key in this.filters) {
                count += (this.filters[key] && 1) || 0
            }

            return count
        },
        loadJogs () {
            this.refresh.list++
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
</style>
