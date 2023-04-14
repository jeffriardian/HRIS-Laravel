import $axios from "../../../plugins/axios";

function defaultForm() {
    return {
        //DATA CANDIDATE
        identity_card_number: "",
        phone_number: "",
        position_id: "",
        religion_id: "",
        company_id: "",
        marital_status_id: "",
        ktp: "",
        sim: "",
        name: "",
        email: "",
        address: "",
        photo: "",
        birth_at: "",
        birth_place: "",
        post_code: "",
        has_sim: "",
        achivement: "",
        thesis_title: "",
        last_salary: "",
        last_facility: "",
        achievement_during_work: "",
        last_position: "",
        last_job_desc: "",
        applying_reason: "",
        work_environment: "",
        expected_salaries: "",
        expected_facilities: "",
        work_out_of_town: "",
        placed_of_town: "",
        work_accident: "",
        date_of_accident: "",
        psycological_check: "",
        date_of_check: "",
        check_place: "",
        check_purpose: "",
        vehicle_type: "",
        vehicle_belong: "",
        employees_name: "",
        employees_relationship: "",
        couple_name: "",
        couple_date_of_birth: "",
        couple_gender: "",
        couple_last_education: "",
        couple_company_name: "",
        gender: "",
        //DATA CANDIDATE CHILDREN
        childrens: [],
        //DATA CANDIDATE COUPLE
        couples: [],
        //DATA CANDIDATE EMERGENCIES CONTACT
        emergencies: [],
        //DATA CANDIDATE EDUCATION BACKGROUNDS
        educations: [],
        //DATA CANDIDATE JOB EXPERIENCES
        job_experiences: [],
        //DATA CANDIDATE LENGUAGES
        languages: [],
        //DATA CANDIDATE PARENTS
        parents: [],
        //DATA CANDIDATE REFERENCES
        references: [],
        //DATA CANDIDATE AQUINTANCES
        aquintances: [],
        //DATA CANDIDATE SIBLINGS
        siblings: [],
        //DATA CANDIDATE TRAININGS
        trainings: [],
        //RECRUITMENT
        recruitment_files: [],
        old_step: "",
        next_step: "",
        next_date: "",
        image_preview: null,
        is_active: false,
        created_by: "",
        updated_by: "",
        created_at: "",
        updated_at: "",
        deleted_at: "",
        contacts: [],
        salaries: [],
        files: [],
        recruitment_step_id: "",
        newStatus: "",
        status_recruitment_id: "",
        recruitment_date: "",
        min_score: "",
        recruitmentNote: "",
        total_score: "",
        candidate: [],
        contacts: [],
        recruitment_step: [],
        score: []
    };
}

const state = () => ({
    collection: [], //UNTUK MENAMPUNG DATA COLLECTION YANG DIDAPATKAN DARI DATABASE
    collectionList: [],
    collectionHistory: [],
    collectionValue: [],

    //UNTUK MENAMPUNG VALUE DARI FORM INPUTAN NANTINYA
    form: defaultForm(),
    tableParams: {
        page: 1, //UNTUK MENCATAT PAGE PAGINATE YANG SEDANG DIAKSES
        per_page: 10,
        order_column: "created_at",
        order_direction: true, //ASCEDING
        pageOptions: [
            { text: 10, value: 10 },
            { text: 20, value: 20 },
            { text: 50, value: 50 },
            { text: 100, value: 100 }
        ]
    },
    formFinal: ""
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
    ASSIGN_HISTORY(state, payload) {
        state.collectionHistory = payload;
    },
    ASSIGN_VALUE(state, payload) {
        state.collectionValue = payload;
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
        state.form = {
            //DATA SCORE
            score: payload.score,
            //DATA CANDIDATE
            candidate: payload.candidate,
            candidate_id: payload.candidate_id,
            name: payload.candidate.name,
            email: payload.candidate.email,
            position_id: payload.candidate.position_id,
            position_name: payload.candidate.position.name,
            company_name: payload.candidate.company.name,
            company_id: payload.candidate.company_id,
            position_id: payload.position_id,
            religion_id: payload.candidate.religion_id,
            marital_status_id: payload.candidate.marital_status_id,
            company_id: payload.company_id,
            ktp: payload.candidate.ktp,
            has_sim: payload.has_sim,
            achivement: payload.achivement,
            thesis_title: payload.thesis_title,
            last_salary: payload.last_salary,
            last_position: payload.last_position,
            last_job_desc: payload.last_job_desc,
            last_facility: payload.last_facility,
            achievement_during_work: payload.achievement_during_work,
            applying_reason: payload.applying_reason,
            work_environment: payload.work_environment,
            expected_salaries: payload.expected_salaries,
            expected_facilities: payload.expected_facilities,
            work_out_of_town: payload.work_out_of_town,
            placed_out_of_town: payload.placed_out_of_town,
            work_accident: payload.work_accident,
            date_of_accident: payload.date_of_accident,
            psycological_check: payload.psycological_check,
            date_of_check: payload.date_of_check,
            check_place: payload.check_place,
            check_purpose: payload.check_purpose,
            vehicle_type: payload.vehicle_type,
            vehicle_belong: payload.vehicle_belong,
            employees_name: payload.employees_name,
            employees_relationship: payload.employees_relationship,
            sim: payload.sim,
            name: payload.name,
            email: payload.email,
            phone: payload.candidate.phone,
            address: payload.address,
            photo: payload.candidate.photo,
            image_preview: payload.candidate.photo,
            gender: payload.gender,
            birth_place: payload.birth_place,
            birth_at: payload.birth_at,
            //DATA CANDIDATE EDUCATION BACKGROUNDS
            educations: payload.candidate.candidate_education_backgrounds,
            //DATA CANDIDATE CHILDREN
            childrens: payload.candidate.candidate_childrens,
            //DAT CANDIDATE LANGUAGE
            languages: payload.candidate.candidate_languages,
            //DATA CANDIDATE EMERGENCY
            emergencies: payload.candidate.candidate_emergencies,
            //DATA CANDIDATE JOB EXPERIENCE
            job_experiences: payload.candidate.candidate_job_experiences,
            //DATA CANDIDATE PARENT
            parents: payload.candidate.candidate_parents,
            //DATA CANDIDATE REFERENCE
            references: payload.candidate.candidate_references,
            //DATA CANDIDATE RELATIVE
            aquintances: payload.candidate.candidate_aquintances,
            //DATA CANDIDATE SIBLING
            siblings: payload.candidate.candidate_siblings,
            //DATA CANDIDATE TRAINING
            trainings: payload.candidate.candidate_trainings,
            //DATA CANDIDATE AMERGENCY
            emergencies: payload.candidate.candidate_emergencies,
            //DATA RECRUITMENT
            next_step: payload.next_step,
            next_date: payload.next_date,
            recruitment_date: payload.recruitment_date,
            status_recruitment_id: payload.status_recruitment_id,
            recruitment_files: payload.recruitment_files,
            recruitment_step_id: payload.recruitment_step_id,
            total_score: payload.total_score,
            is_active: payload.is_active,
            recruitment_step: payload.recruitment_step,
            parameter: payload.recruitment_step,
            is_active: payload.is_active,
            contacts: payload.contacts,
            old_step: payload.recruitment_step_id
        };
    },
    ASSIGN_FORM_FINAL(state, payload) {
        //     let coba = [];
        //   for(let i = 0; i < payload.length; i++){
        //       let data = {
        //           candidate_id: payload[i].candidate_id,
        //           identity_card_number: payload[i].candidate.identity_card_number,
        //           name: payload[i].candidate.name,
        //           email: payload[i].candidate.email,
        //           phone_number: payload[i].candidate.phone_number,
        //           position_id: payload[i].candidate.position_id,
        //           position_name: payload[i].candidate.position.name,
        //           company_name: payload[i].candidate.company.name,
        //           company_id: payload[i].candidate.company_id,
        //           recruitment_step_id: payload[i].recruitment_step_id,
        //           total_score: payload[i].total_score,
        //           recruitment_date: payload[i].recruitment_date,
        //           is_active: payload[i].is_active,
        //           attachment: payload[i].attachment,
        //           recruitment_step: payload[i].recruitment_step,
        //           recruitment_details: payload[i].recruitment_details,
        //           // parameter: payload.candidate.recruitment_step.recruitment_step_parameter.name
        //       }
        //       coba.push(data);
        //   }
        state.formFinal = payload;
    },
    //ME-RESET STATE FORM MENJADI KOSONG
    CLEAR_FORM(state) {
        state.form = defaultForm();
    }
};

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    load({ commit }, payload) {
        // this.CLEAR_FORM();
        return new Promise((resolve, reject) => {
            $axios
                .get(`/human-resource/recruitments`, { params: payload })
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
            $axios.get(`/human-resource/recruitment-list`).then(response => {
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
                .post(`/human-resource/recruitments`, payload, {
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
    //UNTUK MENGAMBIL SINGLE DATA DARI SERVER BERDASARKAN ID
    show({ commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            $axios
                .get(`/human-resource/recruitments/${payload}`)
                .then(response => {
                    //APABIL BERHASIL, DI ASSIGN KE FORM

                    commit("ASSIGN_FORM", response.data.data);
                    commit("ASSIGN_HISTORY", response.data.detail);
                    commit("ASSIGN_VALUE", response.data.scoreValues);
                    resolve(response.data);
                });
        });
    },
    showFinal({ commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            $axios
                .get(`/human-resource/showFinal/${payload}`)
                .then(response => {
                    //APABIL BERHASIL, DI ASSIGN KE FORM
                    commit("ASSIGN_FORM_FINAL", response.data.data);
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
                .post(
                    `/human-resource/recruitments/${payload.id}`,
                    payload.form,
                    {
                        headers: { "Content-Type": "multipart/form-data" }
                    }
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
    updateCandidate({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            //DAN MENGIRIMKAN DATA TERBARU YANG TELAH DIEDIT
            //MELALUI STATE FORM
            console.log(state.form);
            $axios
                .post(
                    `/human-resource/update-candidate/${payload.id}`,
                    payload.form,
                    {
                        headers: { "Content-Type": "multipart/form-data" }
                    }
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
    reject({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            //DAN MENGIRIMKAN DATA TERBARU YANG TELAH DIEDIT
            //MELALUI STATE FORM
            $axios
                .put(`/human-resource/reject/${payload}`, state.formFinal)
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
    accept({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            //DAN MENGIRIMKAN DATA TERBARU YANG TELAH DIEDIT
            //MELALUI STATE FORM
            $axios
                .put(`/human-resource/accept/${payload}`, state.formFinal)
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
    attend({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN ID DI URL
            //DAN MENGIRIMKAN DATA TERBARU YANG TELAH DIEDIT
            //MELALUI STATE FORM
            $axios
                .put(`/human-resource/attend/${payload}`, state.formFinal)
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
                .delete(`/human-resource/recruitments/${payload}`)
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
