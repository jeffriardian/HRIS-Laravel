import $axios from "../../../plugins/axios";

const state = () => ({
    collections: [],
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
    ASSIGN_DATA (state, payload) {
        state.collections = payload;
    },

    SET_PAGE (state, payload) {
        state.tableParams.page = payload;
    },

    SET_TABLE_PARAMS (state, payload) {
        state.tableParams = payload;
    }
};

const actions = {
    load ({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get("/human-resource/activity-logs", { params: payload })
                .then(response => {
                    commit("ASSIGN_DATA", response.data);
                    resolve(response.data);
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
