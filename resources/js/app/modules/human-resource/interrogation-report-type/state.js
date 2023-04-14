import $axios from "../../../plugins/axios";

function defaultForm() {
    return {
        employee_id: "",
        memorandum_parameter_id: "",
        start_date: "",
        end_date: "",
        description: "",
        is_active: false,
        created_by: "",
        updated_by: "",
        created_at: "",
        updated_at: "",
        deleted_at: ""
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
    }
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
    ASSIGN_FORM(state, payload) {
        state.form = payload;
    },
    //ME-RESET STATE FORM MENJADI KOSONG
    CLEAR_FORM(state) {
        state.form = defaultForm();
    }
};

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    load({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/interrogation-report-types`, { params: payload })
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
            $axios.get(`/human-resource/interrogation-report-type-list`).then(response => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit("ASSIGN_LIST", response.data);
                resolve(response.data);
            });
        });
    },
};

export default {
    namespaced: true,
    state,
    actions,
    mutations
};
