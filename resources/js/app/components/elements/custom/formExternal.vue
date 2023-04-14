<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for> {{ $t("Identity Card Number") }} </label>
                <input
                    type="text"
                    class="form-control"
                    v-model="form.identity_card_number"
                    minlength="16"
                    maxlength="16"
                    v-int
                    placeholder="Enter Indentity Card Number Here"
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for> {{ $t("License Number") }} </label>
                <input
                    v-int
                    v-model="form.license_number"
                    minlength="12"
                    maxlength="12"
                    type="text"
                    class="form-control"
                    placeholder="Enter License Number Here"
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for> {{ $t("Name") }} </label>
                <input
                    type="text"
                    v-model="form.name"
                    class="form-control"
                    v-on:keypress="isLetter($event)"
                    placeholder="Enter Name Here"
                />
                <p class="text-danger" v-if="errors.name">
                    {{ errors.name[0] }}
                </p>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for> {{ $t("Phone Number") }} </label>

                <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                    <b-input-group-prepend>
                        <span class="input-group-text"
                            ><img
                                src="/assets/images/flags/id.svg"
                                :width="25"
                            />
                            &nbsp;+62</span
                        >
                    </b-input-group-prepend>
                    <b-form-input
                        v-model="form.phone_number"
                        type="text"
                        v-int
                        maxlength="11"
                        placeholder="Enter Phone Number Here"
                        class="form-control form-control"
                    ></b-form-input>
                </b-input-group>
                <p class="text-danger" v-if="errors.phone_number">
                    {{ errors.phone_number[0] }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import moment from "moment";
import { mapState, mapMutations, mapActions } from "vuex";

export default {
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("externalEmployee", {
            form: state => state.form //MENGAMBIL STATE DATA
        })
    },
    methods: {
        ...mapMutations(["CLEAR_ERRORS"]),
        ...mapMutations("externalEmployee", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
        isLetter(e) {
            let char = String.fromCharCode(e.keyCode); // Get the character
            if (/^[A-Za-z- ]+$/.test(char)) return true;
            // Match with regex
            else e.preventDefault(); // If not match, don't add to input text
        }
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
};
</script>
