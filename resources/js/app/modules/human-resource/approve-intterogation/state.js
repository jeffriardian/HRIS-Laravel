import $axios from "../../../plugins/axios";

function defaultForm() {
    return {
        id: "",
        incident_category_id: "",
        date_of_incident: "",
        description: "",
        lost_cost: "",
        is_active: false,
        witnesses: "",
        suspects: "",
        installment_count: "",
        start_date:"",        
        end_date:"",        
        memorandum_parameter_id: "",
        penalty: "",
        approve: "",
        images: null,
        actors: [],
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

    formMemorandum: "",
    formPenalty: ""
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
                        employeeStatus: true,
                        statusMemorandum: false,
                        statusPenalty: false,
                        penalties: [],
                        period: 1,
                        penalty: 0
                    });
                } else {
                    actor_suspects.push({
                        id: id,
                        suspects: actor,
                        external: external,
                        description: description,
                        employeeStatus: false,
                        statusMemorandum: false,
                        statusPenalty: false,
                        penalties: [],
                        period: 1,
                        penalty: 0
                    });
                }
            }
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
            ...state.form,
            id: payload.id,
            incident_category_id: payload.incident_category_id,
            date_of_incident: payload.date_of_incident,
            incident_chronologic: payload.incident_chronologic,
            lost_cost: payload.lost_cost,
            is_active: payload.is_active,
            witnesses: actor_witness,
            suspects: actor_suspects,
            penalty: payload.penalty,
            approve: payload.approve,
            memorandum_parameter_id: payload.memorandum_parameter_id,
            interrogation_report_actors: payload.interrogation_report_actors,
            interrogation_report_images: payload.interrogation_report_images,
            memorandum: payload.interrogation_report_actors.memorandums,
            installment_count: payload.installment_count,
            interrogation_report_type_id: payload.interrogation_report_type_id,
            interrogation_report_type: payload.interrogation_report_type,
            actors: payload.actors,
            typeable_id: typeable_id,
            typeable: payload.typeable,
            lostStatus: lostStatus,
            orderStatus: orderStatus,
            images: null
        };
    },
    //ME-RESET STATE FORM MENJADI KOSONG
    CLEAR_FORM(state) {
        state.form = defaultForm();
    },
    //NEW STATE FORM
    ASSIGN_FORM_MEMORANDUM(state, payload) {
        state.formMemorandum = payload;
    },
    ASSIGN_FORM_PENALTY(state, payload) {
        state.formPenalty = payload;
    },
};

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    load({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/approve-intterogations`, {
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
                .get(`/human-resource/approve-intterogation-list`)
                .then(response => {
                    //SIMPAN DATA KE STATE MELALUI MUTATIONS
                    commit("ASSIGN_LIST", response.data);
                    resolve(response.data);
                });
        });
    },
    //UNTUK MENGAMBIL SINGLE DATA DARI SERVER BERDASARKAN ID
    show({ commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            $axios
                .get(`/human-resource/approve-intterogations/${payload}`)
                .then(response => {
                    //APABIL BERHASIL, DI ASSIGN KE FORM
                    commit("ASSIGN_FORM", response.data.data);
                    resolve(response.data);
                });
        });
    },
    showMemorandum({ commit }, payload) {
        return new Promise((resolve, reject) => {
            // console.log(payload);
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            $axios
                .get(
                    `/human-resource/memorandum-history?id=${payload.id}&status=${payload.status}`
                )
                .then(response => {
                    //APABIL BERHASIL, DI ASSIGN KE FORM
                    commit("ASSIGN_FORM_MEMORANDUM", response.data.data);
                    resolve(response.data);
                });
        });
    },
    showPenalty({ commit }, payload) {
        return new Promise((resolve, reject) => {
            // console.log(payload);
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            $axios
                .get(
                    `/human-resource/penalty-history?id=${payload.id}&status=${payload.status}`
                )
                .then(response => {
                    //APABIL BERHASIL, DI ASSIGN KE FORM
                    commit("ASSIGN_FORM_PENALTY", response.data.data);
                    resolve(response.data);
                });
        });
    },
    store({ dispatch, commit, state }) {
        return new Promise((resolve, reject) => {
            //MENGIRIMKAN PERMINTAAN KE SERVER DAN MELAMPIRKAN DATA YANG AKAN DISIMPAN
            //DARI STATE FORM
            console.log("hjhhjhj", state.form);
            $axios
                .post(`/human-resource/approve-intterogations`, state.form)
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
    //UNTUK MENGUPDATE DATA BERDASARKAN ID YANG SEDANG DIEDIT
    update({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            //DAN MENGIRIMKAN DATA TERBARU YANG TELAH DIEDIT
            //MELALUI STATE FORM
            $axios
                .put(
                    `/human-resource/approve-intterogations/${payload}`,
                    state.form
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
                .delete(`/human-resource/approve-intterogations/${payload}`)
                .then(response => {
                    //APABILA BERHASIL, FETCH DATA TERBARU DARI SERVER
                    dispatch("load", state.tableParams).then(() => resolve());
                });
        });
    },
    //APPROVE DATA
    approve({ dispatch, state }, payload) {
        return new Promise((resolve, reject) => {
            //MENGIRIM PERMINTAAN KE SERVER UNTUK MENGHAPUS DATA
            $axios
                .get(`/human-resource/approve-intterogations/${payload}`)
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
