import $axios from '../../../plugins/axios'

const state = () => ({
    physicalAvailabilities: [], //UNTUK MENAMPUNG DATA COLLECTION YANG DIDAPATKAN DARI DATABASE
    mtbf: [],
    mttr: [],
    rmc: [],
    effectiveHour: [],
    workOrder: [],
    involvement: [],
    
    tableParams:{
        page: 1, //UNTUK MENCATAT PAGE PAGINATE YANG SEDANG DIAKSES
        per_page: 10,
        order_column: 'created_at',
        order_direction: false, //ASCEDING
        pageOptions: [
            { text: 10, value: 10},
            { text: 20, value: 20},
            { text: 50, value: 50},
            { text: 100, value: 100},
        ],
    }
})

const mutations = {
    //MEMASUKKAN DATA KE STATE COLLECTION
    SET_PHYSICAL_AVAILABILITIES(state, payload) {
        state.physicalAvailabilities = payload
    },
    //MEMASUKKAN DATA KE STATE COLLECTION
    SET_MTBF(state, payload) {
        state.mtbf = payload
    },
    //MEMASUKKAN DATA KE STATE COLLECTION
    SET_MTTR(state, payload) {
        state.mttr = payload
    },
    //MEMASUKKAN DATA KE STATE COLLECTION
    SET_RMC(state, payload) {
        state.rmc = payload
    },
    //MEMASUKKAN DATA KE STATE COLLECTION
    SET_EFFECTIVE_HOUR(state, payload) {
        state.effectiveHour = payload
    },
    //MEMASUKKAN DATA KE STATE COLLECTION
    SET_WORK_ORDER(state, payload) {
        state.workOrder = payload
    },
    //MEMASUKKAN DATA KE STATE COLLECTION
    SET_INVOLVEMENT(state, payload) {
        state.involvement = payload
    },
    //MENGUBAH DATA STATE PAGE
    SET_PAGE(state, payload) {
        state.tableParams.page = payload
    },
    //MENGUBAH DATA STATE PAGE
    SET_TABLE_PARAMS(state, payload) {
        state.tableParams = payload
    },
}

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    physicalAvailability({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/engineering/reports/physical-availability`,{ params: payload })
            .then((response) => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit('SET_PHYSICAL_AVAILABILITIES', response.data)
                resolve(response.data)
            })
        })
    },
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    mtbf({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/engineering/reports/mtbf`,{ params: payload })
            .then((response) => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit('SET_MTBF', response.data)
                resolve(response.data)
            })
        })
    },
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    mttr({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/engineering/reports/mttr`,{ params: payload })
            .then((response) => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit('SET_MTTR', response.data)
                resolve(response.data)
            })
        })
    },
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    rmc({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/engineering/reports/rmc`,{ params: payload })
            .then((response) => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit('SET_RMC', response.data)
                resolve(response.data)
            })
        })
    },

    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    effectiveHour({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/engineering/reports/effective-hour`,{ params: payload })
            .then((response) => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit('SET_EFFECTIVE_HOUR', response.data)
                resolve(response.data)
            })
        })
    },
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    workOrder({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/engineering/reports/work-order`,{ params: payload })
            .then((response) => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit('SET_WORK_ORDER', response.data)
                resolve(response.data)
            })
        })
    },
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    involvement({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/engineering/reports/involvement`,{ params: payload })
            .then((response) => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit('SET_INVOLVEMENT', response.data)
                resolve(response.data)
            })
        })
    },
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}