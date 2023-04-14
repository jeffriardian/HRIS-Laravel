import $axios from '../../../plugins/axios'

function defaultForm() {
    return {
        employee_id: '',
        name: '',
        password: '',
        api_token: '',
        last_logged_at: '',
        last_logged_ip: '',
        is_active: false,
        created_by: '', 
        updated_by: '', 
        created_at: '', 
        updated_at: '', 
        deleted_at: '',
    };
}

const state = () => ({
    collection: [], //UNTUK MENAMPUNG DATA COLLECTION YANG DIDAPATKAN DARI DATABASE
    collectionList: [],

    //UNTUK MENAMPUNG VALUE DARI FORM INPUTAN NANTINYA
    form: defaultForm(),
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
    },

    roles: [], //MENAMPUNG LIST ROLES
    permissions: [], //MENAMPUNG LIST PERMISSION
    role_permission: [], //MENAMPUNG PERMISSION YANG DIMILIKI OLEH ROLE
    authenticated: [] //MENAMPUNG USER YANG SEDANG LOGIN
})

const mutations = {
    //MEMASUKKAN DATA KE STATE COLLECTION
    ASSIGN_DATA(state, payload) {
        state.collection = payload
    },
    //MEMASUKKAN DATA KE STATE COLLECTION
    ASSIGN_LIST(state, payload) {
        state.collectionList = payload
    },
    //MENGUBAH DATA STATE PAGE
    SET_PAGE(state, payload) {
        state.tableParams.page = payload
    },
    //MENGUBAH DATA STATE PAGE
    SET_TABLE_PARAMS(state, payload) {
        state.tableParams = payload
    },
    //MENGUBAH DATA STATE FORM
    ASSIGN_FORM(state, payload) {
        state.form = {
            employee_id: payload.employee_id,
            name: payload.name,
            password: payload.password,
            is_active: payload.is_active,
        }
    },
    //ME-RESET STATE FORM MENJADI KOSONG
    CLEAR_FORM(state) {
        state.form = defaultForm()
    },
    ASSIGN_ROLES(state, payload) {
        state.roles = payload
    },
    ASSIGN_PERMISSION(state, payload) {
        state.permissions = payload
    },
    ASSIGN_ROLE_PERMISSION(state, payload) {
        state.role_permission = payload
    },
    CLEAR_ROLE_PERMISSION(state, payload) {
        state.role_permission = []
    },
}

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    load({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/users`,{ params: payload })
            .then((response) => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit('ASSIGN_DATA', response.data)
                resolve(response.data)
            })
        })
    },
    //FUNGSI UNTUK MENAMBAHKAN DATA BARU
    store({ dispatch, commit, state }) {
        return new Promise((resolve, reject) => {
            //MENGIRIMKAN PERMINTAAN KE SERVER DAN MELAMPIRKAN DATA YANG AKAN DISIMPAN
            //DARI STATE FORM
            $axios.post(`/users`, state.form)
            .then((response) => {
                //APABILA BERHASIL KITA MELAKUKAN REQUEST LAGI
                //UNTUK MENGAMBIL DATA TERBARU
                dispatch('load',state.tableParams).then(() => {
                    resolve(response.data)
                })
            })
            .catch((error) => {
                //APABILA TERJADI ERROR VALIDASI
                //DIMANA LARAVEL MENGGUNAKAN CODE 422
                if (error.response.status == 422) {
                    //MAKA LIST ERROR AKAN DIASSIGN KE STATE ERRORS
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    },
    //UNTUK MENGAMBIL SINGLE DATA DARI SERVER BERDASARKAN ID
    show({ commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            $axios.get(`/users/${payload}`)
            .then((response) => {
                //APABIL BERHASIL, DI ASSIGN KE FORM
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
    //UNTUK MENGUPDATE DATA BERDASARKAN ID YANG SEDANG DIEDIT
    update({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            //DAN MENGIRIMKAN DATA TERBARU YANG TELAH DIEDIT
            //MELALUI STATE FORM
            $axios.put(`/users/${payload}`, state.form)
            .then((response) => {
                //FORM DIBERSIHKAN
                commit('CLEAR_FORM')
                resolve(response.data)
            })
            .catch((error) => {
                //APABILA TERJADI ERROR VALIDASI
                //DIMANA LARAVEL MENGGUNAKAN CODE 422
                if (error.response.status == 422) {
                    //MAKA LIST ERROR AKAN DIASSIGN KE STATE ERRORS
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    } ,
    //MENGHAPUS DATA 
    destroy({ dispatch, state }, payload) {
        return new Promise((resolve, reject) => {
            //MENGIRIM PERMINTAAN KE SERVER UNTUK MENGHAPUS DATA
            $axios.delete(`/users/${payload}`)
            .then((response) => {
                //APABILA BERHASIL, FETCH DATA TERBARU DARI SERVER
                dispatch('load',state.tableParams).then(() => resolve())
            })
        })
    },
    //FUNGSI INI UNTUK MENGAMBIL DATA USER
    getUserLists({ commit }) {
        return new Promise((resolve, reject) => {
            //KIRIM PERMINTAAN KE BACKEND
            $axios.get(`/user-list`)
            .then((response) => {
                //SIMPAN DATANYA KE STATE USERS MENGGUNAKAN MUTATIONS
                commit('ASSIGN_LIST', response.data.data)
                resolve(response.data)
            })
        })
    },
    //FUNGSI INI UNTUK MENGATUR ROLE TIAP USER
    setRoleUser({commit}, payload) {
        return new Promise((resolve, reject) => {
            commit('CLEAR_ERRORS', '', {root: true}) //STATE ERROR DIBERSIHKAN
            //KIRIM PERMINTAAN KE BACKEND
            $axios.post(`/set-role-user`, payload)
            .then((response) => {
                resolve(response.data)
            })
            .catch((error) => {
                //APABILA TERJADI ERROR VALIDASI, SET ERRONYA AGAR DAPAT DITAMPILKAN
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    },
    //UNTUK MENGAMBIL LIST ROLES
    getRoles({ commit }) {
        return new Promise((resolve, reject) => {
            //KIRIM PERMINTAAN KE BACKEND
            $axios.get(`/role-list`)
            .then((response) => {
                //SIMPAN DATANYA KE DALAM STATE ROLES
                commit('ASSIGN_ROLES', response.data.data)
                resolve(response.data)
            })
        })
    },
    //MENGAMBIL LIST PERMISSIONS
    getAllPermission({ commit }) {
        return new Promise((resolve, reject) => {
            //KIRIM PERMINTAAN KE BACKEND
            $axios.get(`/permission-list`)
            .then((response) => {
                //SIMPAN DATA YANG DITERIMA KE DALAM STATE PERMISSIONS
                commit('ASSIGN_PERMISSION', response.data.data)
                resolve(response.data)
            })
        })
    },
    //MENGAMBIL PERMISSION YANG TELAH DIMILIKI OLEH ROLE TERTENTU
    getRolePermission({ commit }, payload) {
        return new Promise((resolve, reject) => {
            commit('CLEAR_ERRORS', '', {root: true}) //BERSIHKAN STATE ERRORS
            //KIRIM PERMINTAAN KE BACKEND BERDASARKAN ROLE_ID
            $axios.post(`/role-permission`, {role_id: payload})
            .then((response) => {
                //SIMPAN DATANYA DENGAN MUTATIONS
                commit('ASSIGN_ROLE_PERMISSION', response.data.data)
                resolve(response.data)
            })
        })
    },
    //BERFUNGSI UNTUK MENGATUR PERMISSION SETIAP ROLEH YANG DIPILIH
    setRolePermission({ commit }, payload) {
        return new Promise((resolve, reject) => {
            commit('CLEAR_ERRORS', '', {root: true})
            //KIRIM PERMINTAAN KE BACKEND
            $axios.post(`/set-role-permission`, payload)
            .then((response) => {
                resolve(response.data)
            })
            .catch((error) => {
                //APABILA TERJADI ERROR VALIDASI
                if (error.response.status == 422) {
                    //SET ERRORNYA AGAR DAPAT DITAMPILKAN
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
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