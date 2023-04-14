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
    ASSIGN_FORM(state, payload) {
        state.form = payload;
    },
};

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    load({ commit }, payload) {
        commit("SET_BUSY", true);
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/reports/memorandum`, {
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
