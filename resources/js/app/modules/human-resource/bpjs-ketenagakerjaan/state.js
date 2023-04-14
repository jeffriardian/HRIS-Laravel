import $axios from "../../../plugins/axios";

const state = () => ({
    collections: [],
    tableParams: {
        page: 1,
        per_page: 10,
        order_column: "created_at",
        order_direction: false,
        pageOptions: [
            { text: 10, value: 10 },
            { text: 20, value: 20 },
            { text: 50, value: 50 },
            { text: 100, value: 100 }
        ]
    }
});

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.collections = payload;
    },
    SET_PAGE(state, payload) {
        state.tableParams = payload;
    },
    SET_TABLE_PARAMS(state, payload) {
        state.tableParams.page = payload;
    }
};

const actions = {
    load({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get("/human-resource/bpjs-ketenagakerjaan", {
                    params: payload
                })
                .then(response => {
                    commit("ASSIGN_DATA", response.data);
                    resolve(response.data);
                });
        });
    },
    store({ dispatch, commit, state }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .post("/human-resource/bpjs-ketenagakerjaan", payload, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .then(response => {
                    dispatch("load", state.tableParams).then(() => {
                        resolve(response.data);
                    });
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        commit("SET_ERRORS", error.response.data.errors, {
                            root: true
                        });
                    } else {
                        commit(
                            "SET_ERRORS",
                            {
                                extension: [
                                    "Server error / format file tidak benar"
                                ]
                            },
                            {
                                root: true
                            }
                        );
                    }
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
