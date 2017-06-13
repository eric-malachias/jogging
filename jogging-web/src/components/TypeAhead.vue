<template>
    <div class="type-ahead">
        <div class="form-group">
            <input class="form-control" type="text" :placeholder="placeholder" v-model="keyword" @input="search()" @change="updateKeyword()" @keypress.enter="hitReturn()">
            <div class="result">
                <div v-if="value">
                    <span class="glyphicon glyphicon-ok text-success"></span>
                    {{ value[property] }}
                </div>
                <div v-else>
                    <span class="glyphicon glyphicon-remove text-danger"></span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import _ from 'lodash'
import Http from '@/services/Http'

export default {
    name: 'type-ahead',
    props: ['placeholder', 'property', 'url', 'value'],
    data () {
        return {
            error: '',
            keyword: '',
            last: ''
        }
    },
    watch: {
        error (error) {
            if (error && this.value) {
                this.last = null
                this.$emit('input', null)
            }
        },
        value (value) {
            if (!value) {
                return
            }

            if (!this.last || (this.last && this.last.id !== value.id)) {
                this.updateKeyword()
            }
        }
    },
    methods: {
        hitReturn () {
            this.$emit('return')
        },
        search: _.debounce(function () {
            Http
                .get(this, this.url, {
                    params: {
                        keyword: this.keyword
                    }
                })
                .then(response => {
                    this.last = response.body
                    this.$emit('input', response.body)
                })
        }, 200),
        updateKeyword () {
            if (this.value) {
                this.keyword = this.value[this.property]
            }
        }
    }
}
</script>

<style scoped lang="scss">
    $height: 34px;

    .result {
        height: $height;
        line-height: $height;
        position: absolute;
        right: 12px;
        top: 0;

        .glyphicon-remove {
            top: 3px;
            position: relative;
        }
        .glyphicon-ok {
            margin-right: 3px;
        }
    }
    .type-ahead {
        position: relative;
    }
</style>
