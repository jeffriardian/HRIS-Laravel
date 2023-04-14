import $axios from "../../../plugins/axios";

const state = () => ({
    collectionList: []
});

const mutations = {
    ASSIGN_LIST(state, payload) {
        state.collectionList = payload;
    }
};

const actions = {
    loadList({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/incident-category-list`)
                .then(response => {
                    //SIMPAN DATA KE STATE MELALUI MUTATIONS
                    commit("ASSIGN_LIST", response.data);
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
