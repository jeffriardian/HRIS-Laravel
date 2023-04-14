import $axios from "../../../../plugins/axios";


const state = () => ({
    collection: [],
    isBusy: false,
    form: "",
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
    formMemo: "",
    formPenalty: ""
});

const mutations = {
    //MEMASUKKAN DATA KE STATE COLLECTION
    ASSIGN_DATA(state, payload) {
        state.collection = payload;
    },
    //MENGUBAH DATA STATE BUSY
    SET_BUSY(state, payload) {
        state.isBusy = payload;
    },
    //MENGUBAH DATA STATE PAGE
    SET_PAGE(state, payload) {
        state.tableParams.page = payload;
    },
    //MENGUBAH DATA STATE PAGE
    SET_TABLE_PARAMS(state, payload) {
        state.tableParams = payload;
    },
    ASSIGN_FORM_MEMORANDUM(state, payload) {
        state.formMemo = payload;
    },
    ASSIGN_FORM_PENALTY(state, payload) {
        state.formPenalty = payload;
    },
    ASSIGN_FORM_INTERROGATION(state, payload) {
        let witnesses = [];
        let suspects = [];
        let history = [];
        payload.interrogation_report_actors.forEach(element => {
            let actor = element.employee_id;
            if (element.interrogation_report_actor_type_id == 1) {
                witnesses.push(actor);
            } else {
                suspects.push(actor);
            }
        });

        let orderStatus = true;
        if (payload.interrogation_report_type_id == 0) {
            orderStatus = false;
        }

        let typeable_id = 0;
        if (payload.interrogation_report_type_id == 5){
            typeable_id = Number(payload.typeable_id);
        }else{
            typeable_id = payload.typeable_id;
        }

        state.form = {
            id: payload.id,
            incident_category_id: payload.incident_category_id,
            date_of_incident: payload.date_of_incident,
            incident_chronologic: payload.incident_chronologic,
            lost_cost: payload.lost_cost,
            is_active: payload.is_active,
            witnesses: witnesses,
            suspects: suspects,
            penalty: payload.penalty,
            approve: payload.approve,
            memorandum_parameter_id: payload.memorandum_parameter_id,
            interrogation_report_actors: payload.interrogation_report_actors,
            interrogation_report_images: payload.interrogation_report_images,
            interrogation_report_type_id: payload.interrogation_report_type_id,
            interrogation_report_type: payload.interrogation_report_type,
            installment_count: payload.installment_count,
            actors: payload.actors,
            history: history,
            typeable_id: typeable_id,
            typeable: payload.typeable,
            orderStatus: orderStatus,
            images: null
        };
    },
};

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    load({ commit }, payload) {
        console.log(payload);
        commit("SET_BUSY", true);
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/reports/interrogation`, {
                    params: payload
                })
                .then(response => {
                    //SIMPAN DATA KE STATE MELALUI MUTATIONS
                    commit("ASSIGN_DATA", response.data);
                    resolve(response.data);
                    commit("SET_BUSY", false);
                })
                .catch(error => {
                    commit("SET_BUSY", false);
                    return [];
                });
        });
    },
    showInterrogation({ commit }, payload) {
        console.log("data", payload);
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            $axios
                .get(`/human-resource/reports/interrogation/${payload}`)
                .then(response => {
                    //APABIL BERHASIL, DI ASSIGN KE FORM
                    commit("ASSIGN_FORM_INTERROGATION", response.data.data);
                    resolve(response.data);
                });
        });
    },
    getMemo({ commit }, payload) {
        console.log("data", payload);
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            $axios
                .get(`/human-resource/reports/interrogation/memorandum/${payload.id}/${payload.status}`)
                .then(response => {
                    //APABIL BERHASIL, DI ASSIGN KE FORM
                    commit("ASSIGN_FORM_MEMORANDUM", response.data.data);
                    commit("ASSIGN_FORM_PENALTY", response.data.penalty);
                    resolve(response.data);
                });
        });
    },
    download({}, payload) {
        // MELAKUKAN DESTRUCTURING ASSIGMENT DARI OBJECT PAYLOAD YANG DIKIRIM DARI INDEX
        const { file } = payload;
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            $axios
                .get(`/human-resource/reports/export-${file}`, {
                    responseType: "blob",
                    params: payload
                })
                .then(response => {
                    let fileType =
                            file == "pdf"
                                ? "application/pdf"
                                : "application/vnd.ms-excel",
                        extension = file != "pdf" ? ".xls" : "";
                    const blob = new Blob([response.data], { type: fileType });
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download =
                        "inspection-" +
                        new Date().toISOString().substring(0, 10) +
                        extension;
                    link.click();
                    URL.revokeObjectURL(link.href);
                })
                .catch(console.error);
        });
    }
};

export default {
    namespaced: true,
    state,
    actions,
    mutations
};
