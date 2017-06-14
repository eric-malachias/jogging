<template>
    <div id="jog-list-page" class="container">
        <h1 v-if="isAdmin()">{{ t('manageJogs') }}</h1>
        <h1 v-else>{{ t('myJogs') }}</h1>
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
            <jog-filters v-model="filters" :hidden="!showFilters"></jog-filters>
        </div>
        <jog-list :filters="filters" :refresh-token="refresh.list" @jog-remove="loadWeeklyReport()" :is-admin="isAdmin()"></jog-list>
        <jog-weekly-report v-if="!isAdmin()" :refresh-token="refresh.report"></jog-weekly-report>
    </div>
</template>

<script>
import JogWeeklyReport from './JogListPage/JogWeeklyReport'
import JogList from './JogListPage/JogList'
import JogFilters from './JogListPage/JogFilters'
import Auth from '@/services/Auth'

export default {
    components: {
        JogWeeklyReport,
        JogList,
        JogFilters
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
                report: 0
            }
        }
    },
    watch: {
        'filters.from' () {
            this.loadJogs()
        },
        'filters.to' () {
            this.loadJogs()
        }
    },
    methods: {
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
        isAdmin () {
            return Auth.isAdmin()
        },
        loadJogs () {
            this.refresh.list++
        },
        loadWeeklyReport () {
            this.refresh.report++
        }
    }
}
</script>
