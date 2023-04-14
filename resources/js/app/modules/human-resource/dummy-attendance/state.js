import $axios from "../../../plugins/axios";

const state = () => ({
    schedule: {
        shift_id: "",
        shift_name: "",
        schedule_in: "",
        schedule_out: ""
    },
    params: {
        employee_id: "",
        request_date: ""
    }
});

const mutations = {
    ASSIGN_DATA(state, payload) {
        if (payload != null) {
            state.schedule = {
                shift_id: payload.shift_id,
                shift_name: payload.shift.name,
                schedule_in: payload.scan_in.split(" ")[1],
                schedule_out: payload.scan_out.split(" ")[1]
            };
        } else {
            state.schedule = {
                shift_id: "",
                shift_name: "",
                schedule_in: "",
                schedule_out: ""
            };
        }
    },
    CLEAR_SCHEDULE(state) {
        state.schedule = {
            shift_id: "",
            shift_name: "",
            schedule_in: "",
            schedule_out: ""
        };
    }
};

const actions = {
    show({ state, commit }) {
        return new Promise((resolve, reject) => {
            $axios
                .post("/human-resource/get-attendance", state.params)
                .then(response => {
                    commit("ASSIGN_DATA", response.data.data);
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
