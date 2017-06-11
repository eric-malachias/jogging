<template>
    <div :class="hidden ? 'hidden' : ''" class="filters">
        <div class="panel panel-default">
            <div class="panel-heading">{{ t('filters') }}</div>
            <div class="panel-body">
                <div class="col-sm-6">
                    <strong>
                        {{ t('from') }}
                        (<a class="link clear-button" @click="value.from = ''">{{ t('clear') }}</a>)
                    </strong>
                    <date-time-picker v-model="value.from"></date-time-picker>
                </div>
                <div class="col-sm-6">
                    <strong>
                        {{ t('to') }}
                        (<a class="link clear-button" @click="value.to = ''">{{ t('clear') }}</a>)
                    </strong>
                    <date-time-picker v-model="value.to"></date-time-picker>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DateTimePicker from '@/components/DateTimePicker'
import _ from 'lodash'

export default {
    components: {
        DateTimePicker
    },
    props: ['value', 'hidden'],
    watch: {
        'value.from' () {
            this.applyFilters()
        },
        'value.to' () {
            this.applyFilters()
        }
    },
    methods: {
        applyFilters: _.debounce(function () {
            this.$emit('input', this.value)
        })
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
</style>
