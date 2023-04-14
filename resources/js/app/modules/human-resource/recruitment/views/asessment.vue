<template>
    <div>
        <div class="form-row">
            <div
                v-if="formFinal[0].status_recruitment_id == 4"
                class="col-md-6"
            >
                <h4>
                    Not Attending Reason
                </h4>
            </div>
            <div v-else class="col-md-6">
                <h4>
                    Candidate Detail
                </h4>
            </div>
            <div class="col-md-6">
                <b-form-checkbox
                    class="float-right"
                    v-model="formFinal[0].status_recruitment_id"
                    id="checkbox-1"
                    name="checkbox-1"
                    value="4"
                    unchecked-value="2"
                    @change="emitToParent"
                >
                    Not Attend
                </b-form-checkbox>
            </div>
        </div>
        <hr />
        <div v-if="formFinal[0].status_recruitment_id == 4">
            <div
                class="form-group"
                :class="{ 'has-error': errors.recruitmentNote }"
            >
                <label for>{{ $t("Note") }}</label>
                <textarea
                    cols="5"
                    rows="5"
                    class="form-control"
                    v-model="formFinal[0].recruitmentNote"
                ></textarea>
                <p class="text-danger" v-if="errors.recruitmentNote">
                    {{ errors.recruitmentNote[0] }}
                </p>
            </div>
            <div class="form-row">
                <div
                    class="form-group col-md-4"
                    :class="{ 'has-error': errors.newStatus }"
                >
                    <b-form-checkbox
                        v-model="formFinal[0].newStatus"
                        id="checkbox-2"
                        name="checkbox-2"
                        value="5"
                        unchecked-value="4"
                    >
                        Reschedule {{ formFinal[0].newStatus }}
                    </b-form-checkbox>
                </div>
                <div
                    v-if="formFinal[0].newStatus == 5"
                    class="form-group col-md-8"
                    :class="{ 'has-error': errors.recruitment_date }"
                >
                    <label>{{ $t("Test Date") }}</label>
                    <input
                        type="date"
                        v-model="formFinal[0].recruitment_date"
                        class="form-control col-md-12"
                        :class="{
                            'border-danger': errors.recruitment_date
                        }"
                    />
                </div>
            </div>
        </div>
        <div v-else-if="formFinal[0].status_recruitment_id == 2">
            <div class="form-row">
                <div class="col-md-4">
                    <div class="form-group">
                        <img
                            :src="
                                imagePreview
                                    ? imagePreview
                                    : '/storage/human-resources/recruitment/' +
                                      formFinal[0].candidate.photo
                            "
                            class="img-fluid"
                        />
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for>{{ $t("name") }}</label>
                            <input
                                type="text"
                                class="form-control form-control"
                                v-model="formFinal[0].candidate.name"
                                disabled
                            />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for>Phone Number</label>
                            <b-input-group size="sm">   
                                <b-input-group-prepend>
                                    <span class="input-group-text"> 
                                        <img
                                            src="/assets/images/flags/id.svg"
                                            :width="25"
                                        >&nbsp;+62
                                    </span>
                                </b-input-group-prepend>
                                <b-form-input
                                    type="text"
                                    class="form-control form-control-sm"
                                    :class="{ 'border-danger': errors.phone }"
                                    v-model="formFinal[0].candidate.phone_number"
                                    disabled
                                ></b-form-input>
                            </b-input-group>
                        </div>
                        <div class="form-group col-md-6">
                            <label for>Company</label>
                            <input
                                type="text"
                                class="form-control form-control-sm"
                                v-model="formFinal[0].candidate.company.name"
                                disabled
                            />
                        </div>
                    </div>
                    <div class="form-row">
                        <div
                            class="form-group col-md-6"
                            :class="{ 'has-error': errors.email }"
                        >
                            <label for>Email</label>
                            <input
                                type="text"
                                minlength="15"
                                maxlength="15"
                                class="form-control form-control-sm"
                                :class="{ 'border-danger': errors.email }"
                                v-model="formFinal[0].candidate.email"
                                disabled
                            />
                            <p class="text-danger" v-if="errors.email">
                                {{ errors.email[0] }}
                            </p>
                        </div>
                        <div class="form-group col-md-6">
                            <label for>Position</label>
                            <input
                                type="text"
                                class="form-control form-control-sm"
                                v-model="formFinal[0].candidate.position.name"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
            <div class="card m-b-30">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-9">
                            <h5 class="card-title">
                                {{ $tc("Personal") }}
                            </h5>
                        </div>
                        <div class="col-3">
                            <button
                                type="button"
                                class="btn btn-info waves-effect waves-light float-right"
                                v-b-toggle="`detail-data-candidate`"
                                variant="primary"
                                v-b-tooltip.hover
                                :title="$t('Detail')"
                            >
                                {{ $t("Show") }}
                                
                            </button>
                        </div>
                        <b-collapse
                            :id="`detail-data-candidate`"
                            class="mt-2 col-md-12"
                        >
                            <b-card>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-row">
                                            <div
                                                class="form-group col-md-6"
                                            >
                                                <label for>{{
                                                    $t("name")
                                                }}</label>
                                                <input
                                                    type="text"
                                                    class="form-control form-control-sm"
                                                    v-model="
                                                        formFinal[0].candidate.name
                                                    "
                                                    disabled
                                                />
                                            </div>
                                            <b-form-group
                                                class="col-md-6"
                                                :label="$t('gender')"
                                            >
                                                <b-form-radio-group
                                                    v-model="
                                                        formFinal[0].candidate.gender
                                                    "
                                                    :options="genderOptions"
                                                    disabled
                                                ></b-form-radio-group>
                                            </b-form-group>
                                        </div>
                                        <div class="form-row">
                                            <div
                                                class="form-group col-md-6"
                                            >
                                                <label for>Address</label>

                                                <textarea
                                                    class="form-control"
                                                    v-model="
                                                        formFinal[0].candidate.address
                                                    "
                                                    disabled
                                                ></textarea>
                                            </div>
                                            <div
                                                class="form-group col-md-6"
                                            >
                                                <label for>Post Code</label>
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    v-model="
                                                        formFinal[0].candidate.post_code
                                                    "
                                                    disabled
                                                />
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div
                                                class="form-group col-md-6"
                                                disabled
                                            >
                                                <label for>{{
                                                    $tc("birth place")
                                                }}</label>
                                                <v-select
                                                    v-model="
                                                        formFinal[0].candidate
                                                            .birth_place
                                                    "
                                                    :options="cities.data"
                                                    label="nama_kabkota"
                                                    :reduce="
                                                        nama_kabkota =>
                                                            nama_kabkota.nama_kabkota
                                                    "
                                                    disabled
                                                ></v-select>
                                            </div>
                                            <div
                                                class="form-group col-md-6"
                                            >
                                                <label for>{{
                                                    $tc("birth date")
                                                }}</label>
                                                <date-picker
                                                    format="YYYY-MM-DD"
                                                    value-type="format"
                                                    type="date"
                                                    v-model="
                                                        formFinal[0].candidate.birth_at
                                                    "
                                                    disabled
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div
                                        class="form-group col-md-4"
                                    >
                                        <label for>{{
                                            $tc("marital status")
                                        }}</label>
                                        <v-select
                                            v-model="
                                                formFinal[0].candidate.marital_status_id
                                            "
                                            :options="maritalStatuses.data"
                                            label="name"
                                            :reduce="name => name.id"
                                            disabled
                                        ></v-select>
                                    </div>
                                    <div
                                        class="form-group col-md-4"
                                    >
                                        <label for>{{ $tc("religion") }}</label>
                                        <v-select
                                            v-model="formFinal[0].candidate.religion_id"
                                            :options="religions.data"
                                            label="name"
                                            :reduce="name => name.id"
                                            disabled
                                        ></v-select>
                                    </div>
                                    <div
                                        class="form-group col-md-4"
                                        :class="{ 'has-error': errors.ktp }"
                                    >
                                        <label for>{{ $t("ktp") }}</label>
                                        <input
                                            type="number"
                                            class="form-control form-control-sm"
                                            v-model="formFinal[0].candidate.ktp"
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for>{{ $tc("Has License") }}</label>
                                        <b-form-checkbox
                                            v-model="formFinal[0].candidate.has_sim"
                                            id="checkbox-4"
                                            name="checkbox-4"
                                            value="Yes"
                                            unchecked-value="No"
                                            disabled
                                        >
                                            Yes
                                        </b-form-checkbox>
                                    </div>
                                    <div v-if="formFinal[0].candidate.has_sim == 'Yes'" class="form-group col-md-3">
                                        <label>Vehicle Type</label>
                                        <input
                                            type="text"
                                            class="form-control form-control"
                                            
                                            v-model="
                                                formFinal[0].candidate.vehicle_type
                                            "
                                        />
                                    </div>
                                    <div
                                        v-if="formFinal[0].candidate.has_sim == 'Yes'"
                                        class="form-group col-md-6"
                                    >
                                        <label for>{{
                                            $tc("Drive License Number")
                                        }}</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            v-model="formFinal[0].candidate.sim"
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div
                                        class="form-group col-md-6"
                                    >
                                        <label for>{{ $tc("Email") }}</label>
                                        <input
                                            type="email"
                                            class="form-control"
                                            
                                            v-model="formFinal[0].candidate.email"
                                            disabled
                                        />
                                        
                                    </div>
                                    <div
                                        class="form-group col-md-6"
                                        
                                    >
                                        <label for>{{
                                            $tc("Phone Number")
                                        }}</label>
                                        <div class="row">
                                            <span class="input-group-text col-1.5 ml-3">
                                                <img
                                                    src="/assets/images/flags/id.svg"
                                                    :width="25"
                                                />
                                            </span>
                                            <span class="input-group-text col-1.5">+62</span>
                                            <input
                                                type="text"
                                                class="form-control col-md-9"
                                                v-model="
                                                    formFinal[0].candidate.phone_number
                                                "
                                                disabled
                                            />
                                        </div>
                                       
                                    </div>
                                </div>
                                <b-tabs content-class="mt-3">
                                    <b-tab active>
                                        <template v-slot:title>
                                            <span class="fa fa-user-graduate"></span>&nbsp;Education Backgrounds
                                        </template>
                                        <div class="form-group">
                                            <div class="card m-b-30">
                                                <div class="card-header">
                                                    <div
                                                        class="row align-items-center"
                                                    >
                                                        <div
                                                            class="col-9"
                                                        ></div>
                                                        <div
                                                            class="col-3"
                                                        ></div>
                                                    </div>
                                                </div>
                                                <table
                                                    class="table table-bordered table-sm table-hover"
                                                >
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                &nbsp;{{
                                                                    $t(
                                                                        "School Name"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t("Major")
                                                                }}
                                                            </th>
                                                            <th width="150px">
                                                                {{ $t("City") }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t(
                                                                        "Entry Year"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t(
                                                                        "Graduation Year"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t("Score")
                                                                }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr
                                                            v-for="(row,
                                                            index) in formFinal[0]
                                                                .candidate
                                                                .candidate_education_backgrounds"
                                                            :key="index"
                                                            class="justify-content-between align-items-center"
                                                        >
                                                            <td>
                                                                <input
                                                                    type="text"
                                                                    class="form-control form-control"
                                                                    :class="{
                                                                        'border-danger':
                                                                            errors[
                                                                                `${index}.school_name`
                                                                            ]
                                                                    }"
                                                                    v-model="
                                                                        row.school_name
                                                                    "
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="text"
                                                                    class="form-control form-control"
                                                                    :class="{
                                                                        'border-danger':
                                                                            errors[
                                                                                `${index}.major`
                                                                            ]
                                                                    }"
                                                                    v-model="
                                                                        row.major
                                                                    "
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <v-select
                                                                    v-model="
                                                                        row.city
                                                                    "
                                                                    :options="
                                                                        cities.data
                                                                    "
                                                                    label="nama_kabkota"
                                                                    :reduce="
                                                                        nama_kabkota =>
                                                                            nama_kabkota.nama_kabkota
                                                                    "
                                                                    disabled
                                                                ></v-select>
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="year"
                                                                    class="form-control"
                                                                    :class="{
                                                                        'border-danger':
                                                                            errors.entry_year
                                                                    }"
                                                                    v-model="
                                                                        row.entry_year
                                                                    "
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="year"
                                                                    class="form-control"
                                                                    :class="{
                                                                        'border-danger':
                                                                            errors.graduation_year
                                                                    }"
                                                                    v-model="
                                                                        row.graduation_year
                                                                    "
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="text"
                                                                    class="form-control form-control"
                                                                    :class="{
                                                                        'border-danger':
                                                                            errors[
                                                                                `${index}.score`
                                                                            ]
                                                                    }"
                                                                    v-model="
                                                                        row.score
                                                                    "
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="card m-b-30">
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5>Languages</h5>
                                                </div> 
                                                <div class="col-3">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <table
                                            class="table table-bordered table-sm table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th>
                                                        &nbsp;{{ $t("Bahasa") }}
                                                    </th>
                                                    <th>{{ $t("Speaking") }}</th>
                                                    <th>{{ $t("Writing") }}</th>
                                                    <th>{{ $t("Reading") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in formFinal[0].candidate.candidate_languages"
                                                    :key="index"
                                                    class="justify-content-between align-items-center"
                                                >
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.language`]
                                                            }"
                                                            v-model="row.language"
                                                            disabled
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <v-select
                                                            v-model="row.speaking"
                                                            :options="scores.data"
                                                            label="name"
                                                            disabled
                                                            :reduce="name => name.name"
                                                        ></v-select>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <v-select
                                                            v-model="row.writing"
                                                            :options="scores.data"
                                                            label="name"
                                                            disabled
                                                            :reduce="name => name.name"
                                                        ></v-select>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <v-select
                                                            v-model="row.reading"
                                                            :options="scores.data"
                                                            label="name"
                                                            disabled
                                                            :reduce="name => name.name"
                                                        ></v-select>
                                                        <br />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card m-b-30">
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5>Education Information</h5>
                                                </div> 
                                                <div class="col-3">
                    
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="container">
                                            <div class="form-group col-md-12">
                                                <label>Achivement</label> 
                                                <textarea class="form-control" v-model="formFinal[0].candidate.achivement" disabled></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Thesis Title</label> 
                                                <input type="text" class="form-control" v-model="formFinal[0].candidate.thesis_title" disabled></textarea>
                                            </div>
                                        </div>
                                    </div>
                                        </div>
                                    </b-tab>
                                    <b-tab>
                                        <template v-slot:title>
                                            <span class="fa fa-book"></span>&nbsp;Training
                                        </template>
                                        <div class="form-group">
                                            <div class="card m-b-30">
                                                <div class="card-header">
                                                    <div
                                                        class="row align-items-center"
                                                    >
                                                        <div
                                                            class="col-9"
                                                        ></div>
                                                        <div
                                                            class="col-3"
                                                        ></div>
                                                    </div>
                                                </div>
                                                <table
                                                    class="table table-bordered table-sm table-hover"
                                                >
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                &nbsp;{{
                                                                    $t(
                                                                        "Training Type"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t(
                                                                        "Organizer"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{ $t("Year") }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr
                                                            v-for="(row,
                                                            index) in formFinal[0].candidate.candidate_trainings"
                                                            :key="index"
                                                            class="justify-content-between align-items-center"
                                                        >
                                                            <td>
                                                                <input
                                                                    type="text"
                                                                    class="form-control form-control"
                                                                    v-model="row.training_type"
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="text"
                                                                    class="form-control form-control"
                                                                    v-model="row.organizer"
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="year"
                                                                    v-model="
                                                                        row.year
                                                                    "
                                                                    class="form-control"
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </b-tab>
                                    <b-tab>
                                        <template v-slot:title>
                                            <span class="fa fa-user-tie"></span>&nbsp;Working Experiences
                                        </template>
                                        <div class="form-group">
                                            <div class="card m-b-30">
                                                <div class="card-header">
                                                    <div
                                                        class="row align-items-center"
                                                    >
                                                        <div
                                                            class="col-9"
                                                        ></div>
                                                        <div
                                                            class="col-3"
                                                        ></div>
                                                    </div>
                                                </div>
                                                <table
                                                    class="table table-bordered table-sm table-hover"
                                                >
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                &nbsp;{{
                                                                    $t(
                                                                        "Company Name"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t(
                                                                        "Address"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t("Phone")
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t(
                                                                        "Position"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t(
                                                                        "Start Date"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t(
                                                                        "End Date"
                                                                    )
                                                                }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr
                                                            v-for="(row,
                                                            index) in formFinal[0].candidate.candidate_job_experiences"
                                                            :key="index"
                                                            class="justify-content-between align-items-center"
                                                        >
                                                            <td>
                                                                <input
                                                                    type="text"
                                                                    class="form-control form-control"
                                                                    v-model="row.company_name"
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <textarea
                                                                    class="form-control form-control"
                                                                    v-model="row.address"
                                                                    disabled
                                                                ></textarea>
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <b-input-group prepend="+62" class="mb-2 mr-sm-2 mb-sm-0">
                                                                    <b-input
                                                                        type="text"
                                                                        v-int
                                                                        maxlength="11"
                                                                        class="form-control form-control"
                                                                        :class="{
                                                                            'border-danger':
                                                                                errors[`${index}.phone`]
                                                                        }"
                                                                        disabled
                                                                        v-model="row.phone"
                                                                    ></b-input>
                                                                </b-input-group>
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="text"
                                                                    class="form-control form-control"
                                                                    v-model="row.position"
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="date"
                                                                    v-model="
                                                                        row.start_date
                                                                    "
                                                                    class="form-control"
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="date"
                                                                    class="form-control"
                                                                    v-model="
                                                                        row.end_date
                                                                    "
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5>Candidate References</h5>
                                                </div> 
                                                <div class="col-3">

                                                </div>
                                            </div>
                                        </div>
                                        <table
                                            class="table table-bordered table-sm table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th>
                                                        &nbsp;{{ $t("Name") }}
                                                    </th>
                                                    <th>{{ $t("Address") }}</th>
                                                    <th>{{ $t("Job") }}</th>
                                                    <th>{{ $t("Phone Number") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in formFinal[0].candidate.candidate_references"
                                                    :key="index"
                                                    class="justify-content-between align-items-center"
                                                >
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            disabled
                                                            v-model="row.name"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <textarea
                                                            class="form-control form-control"
                                                            disabled
                                                            v-model="row.address"
                                                        ></textarea>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            v-model="row.job"
                                                            disabled
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <b-input-group prepend="+62" class="mb-2 mr-sm-2 mb-sm-0">
                                                                    <b-input
                                                                        type="text"
                                                                        v-int
                                                                        maxlength="11"
                                                                        class="form-control form-control"
                                                                        :class="{
                                                                            'border-danger':
                                                                                errors[`${index}.phone`]
                                                                        }"
                                                                        disabled
                                                                        v-model="row.phone"
                                                                    ></b-input>
                                                                </b-input-group>
                                                        <br />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                            </div>
                                            <div class="card m-b-30">
                                                <div class="card-header">
                                                    <div class="row align-items-center">
                                                        <div class="col-9">
                                                            <h5>Experience Information</h5>
                                                        </div> 
                                                        <div class="col-3">
                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="container">
                                                    <div class="form-group col-md-12">
                                                        <label>Last Salary</label> 
                                                        <b-input-group prepend="Rp." class="mb-2 mr-sm-2 mb-sm-0"> 
                                                            <b-input type="text" v-int class="form-control" :value="formatSalary(formFinal[0].candidate.last_salary)" disabled></b-input>
                                                        </b-input-group>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label>Last Facility</label> 
                                                        <input type="text" class="form-control" v-model="formFinal[0].candidate.last_facility" disabled></textarea>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label>Achivement During Work</label> 
                                                        <textarea class="form-control" v-model="formFinal[0].candidate.achivement_during_work" disabled></textarea>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label>Job Desc</label> 
                                                        <textarea class="form-control" v-model="formFinal[0].candidate.last_job_desc" disabled></textarea>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </b-tab>
                                    <b-tab>
                                        <template v-slot:title>
                                            <span class="fa fa-users"></span>&nbsp;Family
                                        </template>
                                        <div class="form-group">
                                            <div class="card m-b-30">
                                                <div class="card-header">
                                                    <div
                                                        class="row align-items-center"
                                                    >
                                                        <div class="col-9">
                                                            <h5
                                                                class="card-title"
                                                            >
                                                                {{
                                                                    $tc(
                                                                        "Parent",
                                                                        2
                                                                    )
                                                                }}
                                                            </h5>
                                                        </div>
                                                        <div
                                                            class="col-3"
                                                        ></div>
                                                    </div>
                                                </div>
                                                <table
                                                    class="table table-bordered table-sm table-hover"
                                                >
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                &nbsp;{{
                                                                    $t("Type")
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{ $t("Name") }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t(
                                                                        "Date of Birth"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t("Gender")
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t(
                                                                        "Address"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t("Phone")
                                                                }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr
                                                            v-for="(row,
                                                            index) in formFinal[0].candidate.candidate_parents"
                                                            :key="index"
                                                            class="justify-content-between align-items-center"
                                                        >
                                                            <td>
                                                                <v-select
                                                                    v-model="row.type"
                                                                    label="text"
                                                                    disabled
                                                                    :reduce="text => text.value"
                                                                    :options="parentTypeOptions"
                                                                ></v-select>
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="text"
                                                                    class="form-control form-control"
                                                                    v-model="row.name"
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    class="form-control"
                                                                    type="date"
                                                                    disabled
                                                                    v-model="row.date_of_birth"
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    v-if="row.gender == '0'"
                                                                    class="form-control"
                                                                    type="text"
                                                                    disabled
                                                                    value="Female"
                                                                />
                                                                <input
                                                                    v-if="row.gender == '1'"
                                                                    class="form-control"
                                                                    type="text"
                                                                    disabled
                                                                    value="Male"
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <textarea
                                                                    class="form-control"
                                                                    v-model="row.address"
                                                                    disabled
                                                                ></textarea>
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <b-input-group prepend="+62" class="mb-2 mr-sm-2 mb-sm-0">
                                                                    <b-input
                                                                        type="text"
                                                                        v-int
                                                                        maxlength="11"
                                                                        class="form-control form-control"
                                                                        :class="{
                                                                            'border-danger':
                                                                                errors[`${index}.phone`]
                                                                        }"
                                                                        disabled
                                                                        v-model="row.phone"
                                                                    ></b-input>
                                                                </b-input-group>
                                                                <br />
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="card m-b-30">
                                                <div class="card-header">
                                                    <div
                                                        class="row align-items-center"
                                                    >
                                                        <div class="col-9">
                                                            <h5
                                                                class="card-title"
                                                            >
                                                                {{
                                                                    $tc(
                                                                        "Sibling",
                                                                        2
                                                                    )
                                                                }}
                                                            </h5>
                                                        </div>
                                                        <div
                                                            class="col-3"
                                                        ></div>
                                                    </div>
                                                </div>
                                                <table
                                                    class="table table-bordered table-sm table-hover"
                                                >
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                &nbsp;{{
                                                                    $t("Name")
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t(
                                                                        "Date of Birth"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t("Gender")
                                                                }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr
                                                            v-for="(row,
                                                            index) in formFinal[0].candidate.candidate_siblings"
                                                            :key="index"
                                                            class="justify-content-between align-items-center"
                                                        >
                                                            <td>
                                                                <input
                                                                    type="text"
                                                                    class="form-control form-control"
                                                                    v-model="row.name"
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="date"
                                                                    v-model="
                                                                        row.date_of_birth
                                                                    "
                                                                    class="form-control"
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    v-if="row.gender == '0'"
                                                                    class="form-control"
                                                                    type="text"
                                                                    disabled
                                                                    value="Female"
                                                                />
                                                                <input
                                                                    v-if="row.gender == '1'"
                                                                    class="form-control"
                                                                    type="text"
                                                                    disabled
                                                                    value="Male"
                                                                />
                                                                <br />
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="card m-b-30">
                                                <div class="card-header">
                                                    <div
                                                        class="row align-items-center"
                                                    >
                                                        <div class="col-9">
                                                            <h5
                                                                class="card-title"
                                                            >
                                                                {{
                                                                    $tc(
                                                                        "Spouse",
                                                                        2
                                                                    )
                                                                }}
                                                            </h5>
                                                        </div>
                                                        <div
                                                            class="col-3"
                                                        ></div>
                                                    </div>
                                                </div>
                                                <table
                                                    class="table table-bordered table-sm table-hover"
                                                >
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                &nbsp;{{
                                                                    $t("Name")
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t(
                                                                        "Date of Birth"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t("Gender")
                                                                }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr
                                                            v-for="(row,
                                                            index) in formFinal[0].candidate.candidate_couples"
                                                            :key="index"
                                                            class="justify-content-between align-items-center"
                                                        >
                                                            <td>
                                                                <input
                                                                    type="text"
                                                                    class="form-control form-control"
                                                                    v-model="row.name"
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="date"
                                                                    v-model="
                                                                        row.date_of_birth
                                                                    "
                                                                    class="form-control"
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    v-if="row.gender == '0'"
                                                                    class="form-control"
                                                                    type="text"
                                                                    disabled
                                                                    value="Female"
                                                                />
                                                                <input
                                                                    v-if="row.gender == '1'"
                                                                    class="form-control"
                                                                    type="text"
                                                                    disabled
                                                                    value="Male"
                                                                />
                                                                <br />
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="card m-b-30">
                                                <div class="card-header">
                                                    <div
                                                        class="row align-items-center"
                                                    >
                                                        <div class="col-9">
                                                            <h5
                                                                class="card-title"
                                                            >
                                                                {{
                                                                    $tc(
                                                                        "Children",
                                                                        2
                                                                    )
                                                                }}
                                                            </h5>
                                                        </div>
                                                        <div
                                                            class="col-3"
                                                        ></div>
                                                    </div>
                                                </div>
                                                <table
                                                    class="table table-bordered table-sm table-hover"
                                                >
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                &nbsp;{{
                                                                    $t("Name")
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t(
                                                                        "Date of Birth"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t("Gender")
                                                                }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr
                                                            v-for="(row,
                                                            index) in formFinal[0].candidate.candidate_childrens"
                                                            :key="index"
                                                            class="justify-content-between align-items-center"
                                                        >
                                                            <td>
                                                                <input
                                                                    type="text"
                                                                    class="form-control form-control"
                                                                    v-model="row.name"
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="date"
                                                                    v-model="
                                                                        row.date_of_birth
                                                                    "
                                                                    class="form-control"
                                                                    disabled
                                                                />
                                                                <br />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    v-if="row.gender == '0'"
                                                                    class="form-control"
                                                                    type="text"
                                                                    disabled
                                                                    value="Female"
                                                                />
                                                                <input
                                                                    v-if="row.gender == '1'"
                                                                    class="form-control"
                                                                    type="text"
                                                                    disabled
                                                                    value="Male"
                                                                />
                                                                <br />
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </b-tab>
                                    <b-tab>
                                    <template v-slot:title>
                                        <span class="fa fa-suitcase"></span>&nbsp;Job Application
                                    </template>
                                <div class="form-group">
                                    <div class="card m-b-30">
                                        <div class="card m-b-30">
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5>Information</h5>
                                                </div> 
                                                <div class="col-3">
                    
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="container">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <label>Your reason / purpose for applying to this company</label> 
                                                    <textarea
                                                        class="form-control"
                                                        v-model="formFinal[0].candidate.applying_reason"
                                                        disabled
                                                    >
                                                    </textarea>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>Your preferred work environment</label> 
                                                    <v-select
                                                            v-model="formFinal[0].candidate.work_environment"
                                                            label="text"
                                                            disabled
                                                            :reduce="text => text.value"
                                                            :options="environmentOptions"
                                                        ></v-select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Expected Salaries</label> 
                                                    <b-input-group prepend="Rp." class="mb-2 mr-sm-2 mb-sm-0"> 
                                                        <b-input
                                                                type="text"
                                                                class="form-control form-control"
                                                                disabled
                                                                :value="formatSalary(formFinal[0].candidate.expected_salaries)"
                                                        ></b-input>
                                                    </b-input-group>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <label>Expected Facility</label> 
                                                    <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            v-model="formFinal[0].candidate.expected_facilities"
                                                            disabled
                                                        />
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-3">
                                                    <label>Would you like to work out of town?</label> 
                                                    <b-form-radio-group
                                                    v-model="
                                                        form.candidate.work_out_of_town
                                                    "
                                                    :options="radioOptions"
                                                    disabled
                                                    ></b-form-radio-group>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Would you like to be placed out of town?</label> 
                                                    <b-form-radio-group
                                                    v-model="
                                                        form.candidate.placed_out_of_town
                                                    "
                                                    :options="radioOptions"
                                                    disabled
                                                    ></b-form-radio-group>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </b-tab>
                            <b-tab>
                                <template v-slot:title>
                                     <span class="fa fa-recycle"></span>&nbsp;Other
                                </template>
                                <div class="form-group">
                                    <div class="card m-b-30">
                                        <div class="card m-b-30">
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5>Experience Information</h5>
                                                </div> 
                                                <div class="col-3">
                    
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="container">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>Have you ever had a serious illness or work accident?</label> 
                                                    <b-form-checkbox
                                                        v-model="formFinal[0].candidate.work_accident"
                                                        id="checkbox-3"
                                                        name="checkbox-3"
                                                        value="Yes"
                                                        disabled
                                                        unchecked-value="No"
                                                    >
                                                        Yes
                                                    </b-form-checkbox>
                                                </div>
                                                <div v-if="formFinal[0].candidate.work_accident != null" class="form-group col-md-6">
                                                    <label>When</label> 
                                                    <input
                                                        type="date"
                                                        disabled
                                                        class="form-control"
                                                        v-model="formFinal[0].candidate.date_of_accident"
                                                    />
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-3">
                                                    <label>Have you ever check your psychologi?</label> 
                                                    <b-form-checkbox
                                                        v-model="formFinal[0].candidate.psychological_check"
                                                        id="checkbox-2"
                                                        name="checkbox-2"
                                                        value="Yes"
                                                        disabled
                                                        unchecked-value="No"
                                                    >
                                                        Yes
                                                    </b-form-checkbox>
                                                </div>
                                                    <div v-if="formFinal[0].candidate.psychological_check == 'Yes'" class="form-group col-md-3">
                                                        <label>When</label> 
                                                        <input
                                                            type="date"
                                                            class="form-control"
                                                            disabled
                                                            v-model="formFinal[0].candidate.date_of_check"
                                                        />
                                                    </div>
                                                    <div v-if="formFinal[0].candidate.psychological_check == 'Yes'" class="form-group col-md-3">
                                                        <label>Company Name</label> 
                                                        <input
                                                                type="text"
                                                                class="form-control form-control"
                                                                
                                                                v-model="formFinal[0].candidate.check_place"
                                                                disabled
                                                            />
                                                    </div>
                                                    <div v-if="formFinal[0].candidate.psychological_check == 'Yes'" class="form-group col-md-3">
                                                        <label>Purpose</label> 
                                                        <input
                                                                type="text"
                                                                class="form-control form-control"
                                                                disabled
                                                                v-model="formFinal[0].candidate.check_purpose"
                                                        />
                                                    </div>
                                            </div>
                                               
                                        </div>
                                    </div>
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5>in Company Aquintances</h5>
                                                </div> 
                                                <div class="col-3">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <table
                                            class="table table-bordered table-sm table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th>
                                                        &nbsp;{{ $t("Relation") }}
                                                    </th>
                                                    <th>{{ $t("Name") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in formFinal[0].candidate.candidate_aquintances"
                                                    :key="index"
                                                    class="justify-content-between align-items-center"
                                                >
                                                    <td>
                                                        <v-select
                                                            v-model="row.relation"
                                                            label="text"
                                                            disabled
                                                            :reduce="text => text.value"
                                                            :options="relationOptions"
                                                        ></v-select>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <v-select
                                                            v-model="row.employee_id"
                                                            label="name"
                                                            disabled
                                                            :reduce="name => name.id"
                                                            :options="employees.data"
                                                        ></v-select>
                                                        <br />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5>Candidate Emergencies</h5>
                                                </div> 
                                                <div class="col-3">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <table
                                            class="table table-bordered table-sm table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th>
                                                        &nbsp;{{ $t("Name") }}
                                                    </th>
                                                    <th>{{ $t("Address") }}</th>
                                                    <th>{{ $t("Phone Number") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in formFinal[0].candidate.candidate_emergencies"
                                                    :key="index"
                                                    class="justify-content-between align-items-center"
                                                >
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            disabled
                                                            v-model="row.name"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <textarea
                                                            class="form-control form-control"
                                                            disabled
                                                            v-model="row.address"
                                                        ></textarea>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <b-input-group prepend="+62" class="mb-2 mr-sm-2 mb-sm-0">
                                                                    <b-input
                                                                        type="text"
                                                                        v-int
                                                                        maxlength="11"
                                                                        class="form-control form-control"
                                                                        :class="{
                                                                            'border-danger':
                                                                                errors[`${index}.phone`]
                                                                        }"
                                                                        disabled
                                                                        v-model="row.phone"
                                                                    ></b-input>
                                                                </b-input-group>
                                                        <br />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5>Candidate Files</h5>
                                                </div> 
                                                <div class="col-3">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <table
                                            class="table table-bordered table-sm table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th>
                                                        &nbsp;{{ $t("Description") }}
                                                    </th>
                                                    <th>{{ $t("Action") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in formFinal[0].candidate.candidate_files"
                                                    :key="index"
                                                    class="justify-content-between align-items-center"
                                                >
                                                    <td>
                                                        <textarea
                                                            class="form-control form-control"
                                                            disabled
                                                            v-model="row.note"
                                                        ></textarea>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <a
                                                                :href="
                                                                    `/storage/human-resources/recruitment/${row.caption}`
                                                                "
                                                                target="_blank"
                                                                class="btn btn-info text-white"
                                                                ><span
                                                                    class="fa fa-download text-white"
                                                                ></span>
                                                                Download</a
                                                            >
                                                        <br />
                                                    </td> 
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </b-tab>
                                </b-tabs>
                            </b-card>
                        </b-collapse>
                    </div>
                </div>
            </div>
        </div>

            <h3>Data Asessment</h3>
            <hr />
            <div>
                <b-card no-body>
                    <b-tabs pills card vertical>
                        <div
                            v-for="(data, index) in formFinal"
                            v-bind:key="index"
                        >
                            <b-tab
                                :title="data.recruitment_step.name"
                                :active="index == 0"
                            >
                                <b-card-text>
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr
                                                v-for="(details,
                                                index) in data.recruitment_details"
                                                v-bind:key="index"
                                            >
                                                <td>
                                                    <p>
                                                        &nbsp;
                                                        <b
                                                            >{{
                                                                details
                                                                    .recruitment_step_parameter
                                                                    .name
                                                            }}
                                                        </b>
                                                        <span
                                                            class="text-info mr-2"
                                                            v-b-modal="
                                                                `detail-${index}`
                                                            "
                                                        >
                                                            <i
                                                                class="bx bx-help-circle"
                                                            ></i>
                                                        </span>
                                                        <b-modal
                                                            :id="
                                                                `detail-${index}`
                                                            "
                                                            size="lg"
                                                            scrollable
                                                            title="Information"
                                                            ok-only
                                                            no-close-on-backdrop
                                                        >
                                                            <h3>
                                                                {{
                                                                    $tc(
                                                                        "Parameter Details"
                                                                    )
                                                                }}
                                                            </h3>
                                                            <hr />
                                                            <p>
                                                                {{
                                                                    details
                                                                        .recruitment_step_parameter
                                                                        .description
                                                                }}
                                                            </p>
                                                            <div
                                                                class="card m-b-30"
                                                            >
                                                                <div
                                                                    class="card-header"
                                                                >
                                                                    <div
                                                                        class="col-9"
                                                                    >
                                                                        <h5
                                                                            class="card-title"
                                                                        >
                                                                            {{
                                                                                $tc(
                                                                                    "Asessment Details"
                                                                                )
                                                                            }}
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                                <table
                                                                    class="table table-bordered table-sm table-hover"
                                                                >
                                                                    <thead>
                                                                        <tr
                                                                            class="justify-content-between align-items-center"
                                                                        >
                                                                            <th
                                                                                width="25%"
                                                                            >
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;{{
                                                                                    $t(
                                                                                        "Rating"
                                                                                    )
                                                                                }}
                                                                            </th>
                                                                            <th>
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;{{
                                                                                    $t(
                                                                                        "description"
                                                                                    )
                                                                                }}
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr
                                                                            v-for="(parameterScore,
                                                                            index) in details
                                                                                .recruitment_step_parameter
                                                                                .recruitment_step_parameter_scores"
                                                                        >
                                                                            <td>
                                                                                <b-form-rating
                                                                                    v-model="
                                                                                        parameterScore.score_id
                                                                                    "
                                                                                    variant="warning"
                                                                                    class="mb-2"
                                                                                    inline
                                                                                    no-border
                                                                                    readonly
                                                                                ></b-form-rating>
                                                                            </td>
                                                                            <td>
                                                                                <p>
                                                                                    &nbsp;&nbsp;&nbsp;&nbsp;{{
                                                                                        parameterScore.description
                                                                                    }}
                                                                                </p>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </b-modal>
                                                        <br />
                                                        <b-form-rating
                                                            v-model="
                                                                details.score
                                                                    .score
                                                            "
                                                            variant="warning"
                                                            class="mb-2"
                                                            inline
                                                            no-border
                                                            readonly
                                                        ></b-form-rating>
                                                        {{ details.score.name }}
                                                    </p>
                                                </td>
                                                <td
                                                    class="float-right align-middle"
                                                >
                                                    <p>
                                                        &nbsp;
                                                        {{ details.note }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </b-card-text>
                                <div class="form-row">
                                    <div class="col-md-5">
                                        <a
                                            v-b-modal="
                                                `file-${formFinal[0].recruitment_id}-${index}`
                                            "
                                            class="btn btn-info text-white"
                                            ><span
                                                class="fa fa-eye text-white"
                                            ></span>
                                            Preview</a
                                        >
                                        <b-modal
                                            :id="
                                                `file-${formFinal[0].recruitment_id}-${index}`
                                            "
                                            size="lg"
                                            scrollable
                                            title="Information"
                                            ok-only
                                            no-close-on-backdrop
                                        >
                                            <div class="card m-b-30">
                                                <div class="card-header">
                                                    <div class="col-9">
                                                        <h5 class="card-title">
                                                            {{
                                                                $tc(
                                                                    "Attachment Files"
                                                                )
                                                            }}
                                                        </h5>
                                                    </div>
                                                </div>
                                                <table
                                                    class="table table-striped table-sm table-hover"
                                                >
                                                    <thead>
                                                        <tr
                                                            class="justify-content-between align-items-center"
                                                        >
                                                            <th>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;{{
                                                                    $t(
                                                                        "Description"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th width="25%">
                                                                &nbsp;&nbsp;&nbsp;&nbsp;{{
                                                                    $t("Action")
                                                                }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr
                                                            v-for="(uploads,
                                                            index) in formFinal[0]
                                                                .candidate.recruitment[index].recruitment_files"
                                                        >
                                                            <td>
                                                                {{
                                                                    uploads.note
                                                                }}
                                                            </td>
                                                            <td>
                                                                <a
                                                                    :href="
                                                                        `/storage/human-resources/recruitment/${uploads.caption}`
                                                                    "
                                                                    target="_blank"
                                                                    class="btn btn-info text-white"
                                                                    ><span
                                                                        class="fa fa-download text-white"
                                                                    ></span>
                                                                    Download</a
                                                                >
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </b-modal>
                                    </div>
                                    <div class="col-md-4">
                                        Total Score : {{ data.total_score }}
                                    </div>
                                    <div class="col-md-3">
                                        <span
                                            class="badge badge-pill badge-soft-success mb-2 font-size-16 float-right"
                                            >{{ $tc("Success") }}</span
                                        >
                                    </div>
                                </div>
                            </b-tab>
                        </div>
                    </b-tabs>
                </b-card>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { Datetime } from "vue-datetime";
import moment from "moment";
import "vue-datetime/dist/vue-datetime.css";
import "vue-datetime/dist/vue-datetime.js";
import vSelect from "vue-select";
import { mapState, mapMutations, mapActions } from "vuex";

export default {
    props: ["modelId"],
    data() {
        return {
            fileList: [],
            scoreChecked: [],
            genderOptions: [
                { text: this.$tc("Male"), value: 1 },
                { text: this.$tc("Female"), value: 0 }
            ],
            relationOptions: [
                { text: this.$tc("Friend"), value: "Friend" },
                { text: this.$tc("Family"), value: "Family" }
            ],
            parentTypeOptions: [
                { text: this.$tc("Biological"), value: "Biological" },
                { text: this.$tc("In Law"), value: "In Law" }
            ],
            environmentOptions: [
                { text: this.$tc("Office"), value: "Office" },
                { text: this.$tc("Factory"), value: "Factory" },
                { text: this.$tc("Laboratorium"), value: "Laboratorium" },
                { text: this.$tc("Field"), value: "Field" }
            ],
            imagePreview: "",
            value: null,
            status: 0,
            coba: false
        };
    },
    created() {
        this.loadrecruitmentStepParameters();
        this.loadScores();
        this.loadEmployees();
        this.loadCandidates();
        this.loadReligions();
        this.loadMaritalStatuses();
        this.loadCompanies();
        this.loadCities();
        this.loadPositions();
    },
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("recruitment", {
            formFinal: state => state.formFinal //MENGAMBIL STATE DATA
        }),
       ...mapState("city", {
            cities: state => state.collectionList
        }),
        ...mapState("recruitmentStepParameter", {
            recruitmentStepParameters: state => state.collectionList
        }),
        ...mapState("score", {
            scores: state => state.collectionList
        }),
        ...mapState("recruitmentStep", {
            recruitmentSteps: state => state.collectionList
        }),
        ...mapState("candidate", {
            candidates: state => state.collectionList
        }),
        ...mapState("department", {
            departments: state => state.collectionList
        }),
        ...mapState("religion", {
            religions: state => state.collectionList
        }),
        ...mapState("maritalStatus", {
            maritalStatuses: state => state.collectionList
        }),
        ...mapState("employee", {
            employees: state => state.collectionList
        }),
        ...mapState("company", {
            companies: state => state.collectionList
        }),
        ...mapState("position", {
            positions: state => state.collectionList
        }),
    },
    methods: {
        ...mapMutations(["CLEAR_ERRORS"]),
        ...mapMutations("recruitment", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions("recruitmentStepParameter", {
            loadrecruitmentStepParameters: "loadList"
        }),
        ...mapActions("score", { loadScores: "loadList" }),
        ...mapActions("candidate", { loadCandidates: "loadList" }),
        ...mapActions("recruitmentStep", {
            loadrecruitmentSteps: "loadNextList"
        }),
        ...mapActions("city", { loadCities: "loadList" }),
        ...mapActions("department", { loadDepartments: "loadList" }),
        ...mapActions("religion", { loadReligions: "loadList" }),
        ...mapActions("maritalStatus", { loadMaritalStatuses: "loadList" }),
        ...mapActions("company", { loadCompanies: "loadList" }),
        ...mapActions("employee", { loadEmployees: "loadList" }),
        ...mapActions("position", { loadPositions: "loadList" }),
        ...mapActions("recruitment", ["update"]), //PANGGIL ACTIONS
        emitToParent(event) {
            this.coba = !this.coba;
            this.$emit("childToParent", this.coba);
        },
        formatSalary(value) {
            let val = (value/1).toFixed(2).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        // uploadPhoto(event){
        //     console.log(event);
        //     this.form.attachment = event.target.files[0];
        //     console.log(this.form.attachment)
        // },
        uploadPhoto(event) {
            dd(formFinal[0].candidate.photo);
            let inputImage = event.target;
            if (inputImage.files && inputImage.files[0]) {
                let reader = new FileReader();
                reader.onload = e => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(inputImage.files[0]);
            }
        },
        test(data, item) {
            if (data.id != null || item.score != null) {
                this.scoreChecked[0] = 0;
                this.scoreChecked[data.id] = item.score;
            }
        }
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    },
    components: {
        "v-select": vSelect,
        datetime: Datetime
    }
};
</script>
