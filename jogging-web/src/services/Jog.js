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

        const endedAt = startedAt.add(duration, 'minutes').format('YYYY-MM-DD HH:mm:ss')

        return endedAt
    }
}
