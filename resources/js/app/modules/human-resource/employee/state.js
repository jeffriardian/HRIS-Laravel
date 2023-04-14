import $axios from "../../../plugins/axios";

function defaultForm() {
    return {
        department_id: "",
        position_id: "",
        religion_id: "",
        blood_type_id: "",
        marital_status_id: "",
        employee_status_id: "",
        company_id: "",
        office_hour_id: "",
        payroll_type_id: "",
        salary_type_id: "",
        ptkp_code: "",
        nik: "",
        ktp: "",
        kk: "",
        npwp: "",
        sim: "",
        name: "",
        email: "",
        phone: "",
        address: "",
        address_ktp: "",
        photo: "",
        image_preview: null,
        gender: "",
        birth_place: "",
        birth_at: "",
        join_at: "",
        leave_at: "",
        is_active: false,
        pin: "",
        created_by: "",
        updated_by: "",
        created_at: "",
        updated_at: "",
        deleted_at: "",
        contacts: [],
        salaries: []
    };
}

const state = () => ({
    collection: [], //UNTUK MENAMPUNG DATA COLLECTION YANG DIDAPATKAN DARI DATABASE
    collectionList: [],

    //UNTUK MENAMPUNG VALUE DARI FORM INPUTAN NANTINYA
    form: defaultForm(),
    tableParams: {
        page: 1, //UNTUK MENCATAT PAGE PAGINATE YANG SEDANG DIAKSES
        per_page: 10,
        order_column: "created_at",
        order_direction: false, //ASCEDING
        pageOptions: [
            { text: 10, value: 10 },
            { text: 20, value: 20 },
            { text: 50, value: 50 },
            { text: 100, value: 100 }
        ]
    },
    id: ""
});

const mutations = {
    //MEMASUKKAN DATA KE STATE COLLECTION
    ASSIGN_DATA(state, payload) {
        state.collection = payload;
    },
    //MEMASUKKAN DATA KE STATE COLLECTION
    ASSIGN_LIST(state, payload) {
        state.collectionList = payload;
    },
    //MENGUBAH DATA STATE PAGE
    SET_PAGE(state, payload) {
        state.tableParams.page = payload;
    },
    //MENGUBAH DATA STATE PAGE
    SET_TABLE_PARAMS(state, payload) {
        state.tableParams = payload;
    },
    //MENGUBAH DATA STATE FORM
    ASSIGN_FORM(state, payload) {
        state.form = {
            department_id: payload.department_id,
            position_id: payload.position_id,
            religion_id: payload.religion_id,
            blood_type_id: payload.blood_type_id,
            marital_status_id: payload.marital_status_id,
            employee_status_id: payload.employee_status_id,
            company_id: payload.company_id,
            office_hour_id: payload.office_hour_id,
            payroll_type_id: payload.payroll_type_id,
            salary_type_id: payload.salary_type_id,
            ptkp_code: payload.ptkp_code,
            nik: payload.nik,
            ktp: payload.ktp,
            kk: payload.kk,
            npwp: payload.npwp,
            sim: payload.sim,
            name: payload.name,
            email: payload.email,
            phone: payload.phone,
            address: payload.address,
            address_ktp: payload.address_ktp,
            photo: "",
            image_preview: payload.photo,
            gender: payload.gender,
            birth_place: payload.birth_place,
            birth_at: payload.birth_at,
            join_at: payload.join_at,
            leave_at: payload.leave_at,
            is_active: payload.is_active,
            contacts: payload.contacts,
            salaries: payload.salaries,
            pin: payload.pin
        };
    },
    //ME-RESET STATE FORM MENJADI KOSONG
    CLEAR_FORM(state) {
        state.form = defaultForm();
    },
    SET_ID(state, payload) {
        state.id = payload;
    }
};

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    load({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/employees`, { params: payload })
                .then(response => {
                    //SIMPAN DATA KE STATE MELALUI MUTATIONS
                    commit("ASSIGN_DATA", response.data);
                    resolve(response.data);
                });
        });
    },
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    loadList({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/employee-list`, { params: payload })
                .then(response => {
                    //SIMPAN DATA KE STATE MELALUI MUTATIONS
                    commit("ASSIGN_LIST", response.data);
                    resolve(response.data);
                });
        });
    },
    //FUNGSI UNTUK MENAMBAHKAN DATA BARU
    store({ dispatch, commit, state }, payload) {
        return new Promise((resolve, reject) => {
            //MENGIRIMKAN PERMINTAAN KE SERVER DAN MELAMPIRKAN DATA YANG AKAN DISIMPAN
            //DARI STATE FORM
            $axios
                .post(`/human-resource/employees`, payload, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .then(response => {
                    //APABILA BERHASIL KITA MELAKUKAN REQUEST LAGI
                    //UNTUK MENGAMBIL DATA TERBARU
                    dispatch("load", state.tableParams).then(() => {
                        resolve(response.data);
                    });
                })
                .catch(error => {
                    //APABILA TERJADI ERROR VALIDASI
                    //DIMANA LARAVEL MENGGUNAKAN CODE 422
                    if (error.response.status == 422) {
                        //MAKA LIST ERROR AKAN DIASSIGN KE STATE ERRORS
                        commit("SET_ERRORS", error.response.data.errors, {
                            root: true
                        });
                    }
                });
        });
    },
    //UNTUK MENGAMBIL SINGLE DATA DARI SERVER BERDASARKAN ID
    show({ commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            $axios
                .get(`/human-resource/employees/${payload}`)
                .then(response => {
                    //APABIL BERHASIL, DI ASSIGN KE FORM
                    commit("ASSIGN_FORM", response.data.data);
                    resolve(response.data);
                });
        });
    },
    //UNTUK MENGUPDATE DATA BERDASARKAN ID YANG SEDANG DIEDIT
    update({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            //DAN MENGIRIMKAN DATA TERBARU YANG TELAH DIEDIT
            //MELALUI STATE FORM
            $axios
                .post(`/human-resource/employees/${state.id}`, payload, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .then(response => {
                    //FORM DIBERSIHKAN
                    commit("CLEAR_FORM");
                    resolve(response.data);
                })
                .catch(error => {
                    //APABILA TERJADI ERROR VALIDASI
                    //DIMANA LARAVEL MENGGUNAKAN CODE 422
                    if (error.response.status == 422) {
                        //MAKA LIST ERROR AKAN DIASSIGN KE STATE ERRORS
                        commit("SET_ERRORS", error.response.data.errors, {
                            root: true
                        });
                    }
                });
        });
    },
    //MENGHAPUS DATA
    destroy({ dispatch, state }, payload) {
        return new Promise((resolve, reject) => {
            //MENGIRIM PERMINTAAN KE SERVER UNTUK MENGHAPUS DATA
            $axios
                .delete(`/human-resource/employees/${payload}`)
                .then(response => {
                    //APABILA BERHASIL, FETCH DATA TERBARU DARI SERVER
                    dispatch("load", state.tableParams).then(() => resolve());
                });
        });
    },
    //MIGRATION DATA
    migration({ dispatch, state }, payload) {
        return new Promise((resolve, reject) => {
            //MENGIRIM PERMINTAAN KE SERVER UNTUK MENGHAPUS DATA
            $axios.post(`/human-resource/migrationEmployee`).then(response => {
                //APABILA BERHASIL, FETCH DATA TERBARU DARI SERVER
                dispatch("load", state.tableParams).then(() => resolve());
            });
        });
    }
};

export default {
    namespaced: true,
    state,
    actions,
    mutations
};
