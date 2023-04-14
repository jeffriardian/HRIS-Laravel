import $axios from "../../../plugins/axios";

function defaultForm() {
    return {
        employee_id: "",
        approved_by: "",
        request_date: "",
        overtime_before_duration: null,
        overtime_after_duration: null,
        total_time: "",
        description: "",
        attachment: "",
        shift_id: "",
        shift_name: "",
        without_reducing_rest_hours: "0",
        is_holiday: "0",
        is_saturday: "0",
        // is_normal: "0",
        schedule_in: "",
        schedule_out: "",
        start_time_holiday: null,
        end_time_holiday: null
    };
}

function formUpload() {
    return {
        excel: null,
        approved_by: "",
        attachment: null
    };
}

function formApproval() {
    return {
        overtime_id: "",
        approved_status: "",
        reject_description: ""
    };
}

const state = () => ({
    collection: [], //UNTUK MENAMPUNG DATA COLLECTION YANG DIDAPATKAN DARI DATABASE
    collectionList: [],

    //UNTUK MENAMPUNG VALUE DARI FORM INPUTAN NANTINYA
    form: defaultForm(),
    formUpload: formUpload(),
    formApproval: formApproval(),
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
    tableAutoOvertimeParams: {
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
    dataTemporary: [],
    detail: [],
    dataAutoOvertime: []
});

const mutations = {
    //MEMASUKKAN DATA KE STATE COLLECTION
    ASSIGN_DATA(state, payload) {
        state.collection = payload;
    },
    ASSIGN_DATA_AUTO_OVERTIME(state, payload) {
        state.dataAutoOvertime = payload;
    },
    ASSIGN_DATA_TEMPORARY(state, payload) {
        state.dataTemporary = payload;
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
    //MENGUBAH DATA STATE PAGE
    SET_AUTO_OVERTIME_PAGE(state, payload) {
        state.tableAutoOvertimeParams.page = payload;
    },
    //MENGUBAH DATA STATE PAGE
    SET_TABLE_AUTO_OVERTIME_PARAMS(state, payload) {
        state.tableAutoOvertimeParams = payload;
    },
    //MENGUBAH DATA STATE FORM
    ASSIGN_FORM(state, payload) {
        state.form = {
            employee_id: payload.employee_id,
            approved_by: payload.approved_by,
            request_date: payload.request_date,
            overtime_before_duration: payload.overtime_before_duration,
            overtime_after_duration: payload.overtime_after_duration,
            total_time: payload.total_time,
            description: payload.description,
            attachment: payload.attachment,
            shift_id: payload.shift.shift_id,
            shift_name: payload.shift.shift.name,
            schedule_in: payload.shift.schedule_in,
            schedule_out: payload.shift.schedule_out,
            without_reducing_rest_hours: payload.without_reducing_rest_hours,
            is_holiday: payload.is_holiday,
            is_saturday: payload.is_saturday,
            // is_normal: payload.is_normal,
            start_time_holiday:
                payload.start_time_holiday != null
                    ? payload.start_time_holiday.split(" ")[1]
                    : null,
            end_time_holiday:
                payload.end_time_holiday != null
                    ? payload.end_time_holiday.split(" ")[1]
                    : null
        };
    },
    //ME-RESET STATE FORM MENJADI KOSONG
    CLEAR_FORM(state) {
        state.form = defaultForm();
        state.formUpload = formUpload();
        state.formApproval = formApproval();
    },
    ASSIGN_DETAIL(state, payload) {
        state.detail = payload;
    }
};

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    load({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/overtimes`, { params: payload })
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
            $axios.get(`/human-resource/overtime-list`).then(response => {
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
            $axios
                .post(`/human-resource/overtimes`, payload, {
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
                    if (error.response.status == 422) {
                        commit("SET_ERRORS", error.response.data.errors, {
                            root: true
                        });
                    }
                });
        });
    },
    importExcelToTemporary({ dispatch, commit, state }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .post(
                    "/human-resource/overtime-import-excel-to-temporary",
                    payload,
                    {
                        headers: { "Content-Type": "multipart/form-data" }
                    }
                )
                .then(response => {
                    dispatch("load", state.tableParams).then(() => {
                        commit("ASSIGN_DATA_TEMPORARY", response.data);
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
                                excel: [
                                    "Server error / format file tidak benar"
                                ]
                            },
                            { root: true }
                        );
                    }
                });
        });
    },
    migrationFromTemporary({ commit }) {
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/overtime-migration-from-temporary`)
                .then(response => {
                    //SIMPAN DATA KE STATE MELALUI MUTATIONS
                    commit("ASSIGN_DATA_TEMPORARY", []);
                    commit("CLEAR_FORM");
                    resolve(response.data);
                });
        });
    },
    cancelStoreImport({ commit }) {
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/overtime-cancel-store-import`)
                .then(response => {
                    //SIMPAN DATA KE STATE MELALUI MUTATIONS
                    commit("ASSIGN_DATA_TEMPORARY", []);
                    commit("CLEAR_FORM");
                    resolve(response.data);
                });
        });
    },
    //UNTUK MENGAMBIL SINGLE DATA DARI SERVER BERDASARKAN ID
    show({ commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            $axios
                .get(`/human-resource/overtimes/${payload}`)
                .then(response => {
                    //APABIL BERHASIL, DI ASSIGN KE FORM
                    commit("ASSIGN_FORM", response.data.data);
                    resolve(response.data);
                });
        });
    },

    loadDetail({ commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            $axios
                .get(`/human-resource/overtimes/${payload}`)
                .then(response => {
                    //APABIL BERHASIL, DI ASSIGN KE FORM
                    commit("ASSIGN_DETAIL", response.data.data);
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
                .post(`/human-resource/overtimes/${payload.id}`, payload.data, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
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
                .delete(`/human-resource/overtimes/${payload}`)
                .then(response => {
                    //APABILA BERHASIL, FETCH DATA TERBARU DARI SERVER
                    dispatch("load", state.tableParams).then(() => resolve());
                });
        });
    },
    //Approval
    storeOrUpdateApproval({ dispatch, commit, state }) {
        return new Promise((resolve, reject) => {
            $axios
                .post("/human-resource/overtime-approval", state.formApproval)
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
                    }
                });
        });
    },
    loadAutoOvertime({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/auto-overtimes`, { params: payload })
                .then(response => {
                    //SIMPAN DATA KE STATE MELALUI MUTATIONS
                    commit("ASSIGN_DATA_AUTO_OVERTIME", response.data);
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
