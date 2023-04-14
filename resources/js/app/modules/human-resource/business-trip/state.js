import $axios from "../../../plugins/axios";

function defaultForm() {
    return {
        employee_id: "",
        departure_date: "",
        back_date: "",
        destination: "",
        purpose: "",
        business_trip_result: "",
        total_cost: "",
        reimburse_type: "",
        created_by: "",
        updated_by: "",
        created_at: "",
        updated_at: "",
        deleted_at: "",
        transportations: [],
        parameters: []
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
    detail: ""
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
        let transportations = [];
        payload.transportations.forEach(element => {
            transportations.push({
                business_trip_id: element.business_trip_id,
                cost: element.cost,
                id: element.id,
                images: null,
                is_active: element.is_active,
                transportation_id: element.transportation_id
            });
        });
        let parameters = [];
        payload.parameters.forEach(element => {
            parameters.push({
                business_trip_id: element.business_trip_id,
                cost: element.cost,
                id: element.id,
                images: null,
                is_active: element.is_active,
                receipt_parameter_id: element.receipt_parameter_id
            });
        });
        state.form = {
            employee_id: payload.employee_id,
            departure_date: payload.departure_date,
            back_date: payload.back_date,
            destination: payload.destination,
            purpose: payload.purpose,
            business_trip_result: payload.business_trip_result,
            total_cost: payload.total_cost,
            reimburse_type:
                payload.reimburse != null
                    ? payload.reimburse.reimburse_type
                    : null,
            transportations: transportations,
            parameters: parameters
        };
    },
    //ME-RESET STATE FORM MENJADI KOSONG
    CLEAR_FORM(state) {
        state.form = defaultForm();
    },
    ASSIGN_DETAIL(state, payload) {
        state.detail = payload;
    },
    CLEAR_DETAIL(state) {
        state.detail = "";
    }
};

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    load({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/business-trips`, { params: payload })
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
            $axios.get(`/human-resource/business-trip-list`).then(response => {
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
                .post(`/human-resource/business-trips`, payload)
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
                .get(`/human-resource/business-trips/${payload.id}`)
                .then(response => {
                    //APABIL BERHASIL, DI ASSIGN KE FORM
                    if (payload.action == "edit") {
                        commit("ASSIGN_FORM", response.data.data);
                    } else {
                        commit("ASSIGN_DETAIL", response.data.data);
                    }
                    resolve(response.data);
                });
        });
    },
    //UNTUK MENGUPDATE DATA BERDASARKAN ID YANG SEDANG DIEDIT
    update({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            //DAN MENGIRIMKAN DATA TERBARU YANG TELAH DIEDIT
            $axios
                .post(
                    `/human-resource/business-trips/${payload.id}`,
                    payload.data,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data"
                        }
                    }
                )
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
                .delete(`/human-resource/business-trips/${payload}`)
                .then(response => {
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
