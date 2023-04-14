import $axios from "../../../plugins/axios";

function defaultForm() {
    return {
        incident_category_id: "",
        date_of_incident: "",
        incident_chronologic: "",
        typeable_type: "",
        typeable_id: "",
        interrogation_report_type_id: "",
        orderStatus: false,
        lost_cost: 0,
        is_active: false,
        lostStatus: false,
        actor_witness: [],
        actor_suspects: [],
        images: []
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
        let actor_witness = [];
        let actor_suspects = [];
        payload.interrogation_report_actors.forEach(element => {
            let id = element.id;
            let actor = element.employee_id;
            let description = element.description;
            let external = element.external_employee_id;
            if (element.interrogation_report_actor_type_id == 1) {
                if (external > 0) {
                    actor_witness.push({
                        id: id,
                        witnesses: actor,
                        external: external,
                        description: description,
                        employeeStatus: true
                    });
                } else {
                    actor_witness.push({
                        id: id,
                        witnesses: actor,
                        external: external,
                        description: description,
                        employeeStatus: false
                    });
                }
            } else {
                if (external > 0) {
                    actor_suspects.push({
                        id: id,
                        suspects: actor,
                        external: external,
                        description: description,
                        employeeStatus: true
                    });
                } else {
                    actor_suspects.push({
                        id: id,
                        suspects: actor,
                        external: external,
                        description: description,
                        employeeStatus: false
                    });
                }
            }
        });
        let images = [];
        payload.interrogation_report_images.forEach(element => {
            let id = element.id;
            let image = element.image;
            let note = element.note;
            images.push({
                id: id,
                caption: image,
                note: note
            });
        });
        let orderStatus = true;
        if (payload.interrogation_report_type_id == 0) {
            orderStatus = false;
        }
        let lostStatus = false;
        if (payload.lost_cost > 0) {
            lostStatus = true;
        }
        let typeable_id = 0;
        if (payload.interrogation_report_type_id == 5){
            typeable_id = Number(payload.typeable_id);
        }else{
            typeable_id = payload.typeable_id;
        }

        state.form = {
            incident_category_id: payload.incident_category_id,
            date_of_incident: payload.date_of_incident,
            incident_chronologic: payload.incident_chronologic,
            lost_cost: payload.lost_cost,
            interrogation_report_type_id: payload.interrogation_report_type_id,
            interrogation_report_type: payload.interrogation_report_type,
            typeable_id: typeable_id,
            typeable: payload.typeable,
            is_active: payload.is_active,
            lostStatus: lostStatus,
            orderStatus: orderStatus,
            actor_witness: actor_witness,
            actor_suspects: actor_suspects,
            images: images
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
                .get(`/human-resource/interrogation-reports`, {
                    params: payload
                })
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
                .get(`/human-resource/interrogation-report-list`)
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
            console.log(payload);
            $axios
                .post(`/human-resource/interrogation-reports`, payload, {
                    headers: { "Content-Type": "multipart/form-data" }
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
                .get(`/human-resource/interrogation-reports/${payload.id}`)
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
            //MELALUI STATE FORM
            $axios
                .post(
                    `/human-resource/interrogation-reports/${payload.id}`,
                    payload.form
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
                .delete(`/human-resource/interrogation-reports/${payload}`)
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
