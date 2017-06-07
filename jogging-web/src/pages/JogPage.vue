<template>
    <div id="jog-page" class="container">
        <pre>{{ jog }}</pre>
    </div>
</template>

<script>
import Http from '@/services/Http'

export default {
    data () {
        return {
            jog: {
                id: this.$route.params.id
            }
        }
    },
    created () {
        if (!this.isNew()) {
            this.loadJog()
        }
    },
    methods: {
        getRequest () {
            if (this.isNew()) {
                return Http.post(this, '/jogs', this.jog)
            }

            return Http.put(this, `/jogs/${this.jog.id}`, this.jog)
        },
        isNew () {
            return this.jog.id === 'new'
        },
        loadJog () {
            Http
                .get(this, `jogs/${this.jog.id}`)
                .then(response => {
                    this.jog = response.body
                })
        },
        saveJog () {
            this
                .getRequest()
                .then(response => {
                    console.log(response)
                })
        }
    }
}
</script>

<style scoped>
</style>
