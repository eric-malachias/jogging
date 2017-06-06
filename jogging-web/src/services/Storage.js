class Storage {
    constructor (storage) {
        this.storage = storage
    }
    get (key, fallback = null) {
        return JSON.parse(this.storage.getItem(key)) || fallback
    }
    remove (key) {
        return this.storage.removeItem(key)
    }
    set (key, value) {
        return this.storage.setItem(key, JSON.stringify(value))
    }
}

export default {
    get defaultStorage () {
        return this.Session
    },
    get Local () {
        return new Storage(localStorage)
    },
    get Session () {
        return new Storage(sessionStorage)
    },
    get () {
        return this.defaultStorage.get(...arguments)
    },
    remove () {
        return this.defaultStorage.remove(...arguments)
    },
    set () {
        return this.defaultStorage.set(...arguments)
    }
}
