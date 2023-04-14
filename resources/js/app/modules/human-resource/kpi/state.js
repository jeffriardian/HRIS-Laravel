import $axios from "../../../plugins/axios";

const state = () => ({
    collectionList: [],
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
    reports: [],
    employees: [],
    formulas: []
});

const mutations = {
    ASSIGN_DATA_REPORT(state, payload) {
        state.reports = payload;
    },
    ASSIGN_DATA_EMPLOYEES(state, payload) {
        state.employees = payload;
    },
    ASSIGN_DATA_FORMULAS(state, payload) {
        state.formulas = payload;
    },
    SET_TABLE_PARAMS(state, payload) {
        state.tableParams = payload;
    }
};

const actions = {
    loadKpiReports({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resources/kpi-reports`, { params: payload })
                .then(response => {
                    commit("ASSIGN_DATA_REPORT", response.data);
                    resolve(response.data);
                });
        });
    },
    loadKpiFormulas({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/kpi-formulas`, {
                    params: payload
                })
                .then(response => {
                    commit("ASSIGN_DATA_FORMULAS", response.data);
                    resolve(response.data);
                });
        });
    },
    loadKpiFormulaOptions({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`/human-resource/kpi-formula-options`).then(response => {
                commit("ASSIGN_DATA_FORMULAS", response.data);
                resolve(response.data);
            });
        });
    },
    loadKpiEmployees({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/kpi-employees`, { params: payload })
                .then(response => {
                    commit("ASSIGN_DATA_EMPLOYEES", response.data);
                    resolve(response.data);
                });
        });
    },
    updateKpiFormula({ dispatch, commit, state }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .put("/human-resource/kpi-formulas", payload)
                .then(response => {
                    dispatch("loadKpiFormulas", state.tableParams).then(() => {
                        resolve(response.data);
                    });
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        commit("SET_ERRORS", error.response.data.errors, {
                            root: true
                        });
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
