<template>
    <div class="weekly-report">
        <h2>{{ t('weeklyJogReport') }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>{{ t('weekOf') }}</th>
                    <th>{{ t('totalDistance') }}</th>
                    <th>{{ t('totalDuration') }}</th>
                    <th>{{ t('averageSpeed') }}</th>
                </tr>
            </thead>
            <tbody v-if="pagination.total > 0">
                <tr v-for="(item, index) in report" :key="item.year_week">
                    <td>{{ getWeek(item.year_week) }}</td>
                    <td>{{ item.total_distance | kilometers }}</td>
                    <td>{{ item.total_duration | hours }}</td>
                    <td>{{ calculateSpeed(item) | speed }}</td>
                </tr>
            </tbody>
            <tbody v-else class="no-jogs text-center">
                <tr v-if="!pagination.total">
                    <td colspan="5">{{ t('noJogs') }}</td>
                </tr>
            </tbody>
        </table>
        <div class="pagination-container text-center" v-if="pagination.last_page > 1">
            <pagination v-if="pagination.total > 1" :pagination="pagination" :callback="load" :options="paginationOptions"></pagination>
        </div>
    </div>
</template>

<script>
import Auth from '@/services/Auth'
import Http from '@/services/Http'
import Jog from '@/services/Jog'
import Pagination from 'vue-bootstrap-pagination'
import moment from 'moment'

export default {
    components: {
        Pagination
    },
    props: ['refresh-token'],
    data () {
        return {
            error: '',
            pagination: {},
            paginationOptions: {
                offset: 1,
                alwaysShowPrevNext: true
            },
            report: []
        }
    },
    created () {
        this.load()
    },
    watch: {
        refreshToken () {
            this.load()
        }
    },
    methods: {
        calculateSpeed (item) {
            if (item.average_speed) {
                return item.average_speed
            }

            const speed = Jog.calculateSpeed(item.total_distance, item.total_duration)

            item.average_speed = speed

            return item.average_speed
        },
        getWeek (yearWeek) {
            const year = Math.floor(yearWeek / 100)
            const week = yearWeek - year * 100

            return moment().year(year).week(week).format('DD/MM/YYYY')
        },
        load () {
            Http
                .get(this, `users/${Auth.user.id}/jogs/weekly-report`, {
                    params: {
                        page: this.pagination.current_page || 1
                    }
                })
                .then(response => {
                    this.report = response.body.data
                    this.pagination = response.body
                })
        }
    }
}
</script>
