import moment from 'moment'

export default {
    calculateDuration (startedAt, endedAt) {
        startedAt = moment(startedAt)
        endedAt = moment(endedAt)

        const duration = moment.duration(endedAt.diff(startedAt)).asMinutes()

        return duration
    },
    calculateEndedAt (startedAt, duration) {
        startedAt = moment(startedAt)

        const endedAt = startedAt.add(duration, 'minutes')

        if (!endedAt.isValid()) {
            return null
        }

        return endedAt.format('YYYY-MM-DD HH:mm:ss')
    },
    calculateSpeed (distance, speed) {
        return (distance / 1000) / (speed / 60)
    }
}
