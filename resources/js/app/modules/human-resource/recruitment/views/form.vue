<template>
    <div>
        <div>
            <div class="form-row">
                <div class="col-md-9">
                    <h4>Candidate Detail</h4>
                </div>
                <div class="col-md-3">
                    <b-form-checkbox
                        class="float-right"
                        v-model="form.status_recruitment_id"
                        id="checkbox-1"
                        name="checkbox-1"
                        value="4"
                        unchecked-value="1"
                    >
                        Not Attend
                    </b-form-checkbox>
                </div>
            </div>
            <hr />
            <div class="form-row">
                <div class="col-md-4">
                    <div class="form-group">
                        <div v-if="form.image_preview != null">
                            <img
                                :src="
                                    imagePreview
                                        ? imagePreview
                                        : '/storage/human-resources/recruitment/' +
                                          form.image_preview
                                "
                                class="img-fluid"
                            />
                        </div>
                        <div v-if="form.image_preview == null">
                            <img
                                :src="
                                    imagePreview
                                        ? imagePreview
                                        : '/images/default.jpg'
                                "
                                class="img-fluid"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-row">
                        <div
                            class="form-group col-md-12"
                            :class="{ 'has-error': errors.name }"
                        >
                            <label for>{{ $t("name") }}</label>
                            <input
                                type="text"
                                class="form-control"
                                :class="{ 'border-danger': errors.name }"
                                v-model="form.candidate.name"
                                disabled
                            />
                            <p class="text-danger" v-if="errors.name">
                                {{ errors.name[0] }}
                            </p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div
                            class="form-group col-md-6"
                            :class="{ 'has-error': errors.phone }"
                        >
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
                                    v-model="form.candidate.phone_number"
                                    disabled
                                ></b-form-input>
                            </b-input-group>
                            <p class="text-danger" v-if="errors.phone">
                                {{ errors.phone[0] }}
                            </p>
                        </div>
                        <div
                            class="form-group col-md-6"
                            :class="{ 'has-error': errors.kk }"
                        >
                            <label for>Company</label>
                            <input
                                type="text"
                                minlength="16"
                                maxlength="16"
                                class="form-control form-control-sm"
                                :class="{
                                    'border-danger': errors.company_name
                                }"
                                v-model="form.company_name"
                                disabled
                            />
                            <p class="text-danger" v-if="errors.company_name">
                                {{ errors.company_name[0] }}
                            </p>
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
                                v-model="form.candidate.email"
                                disabled
                            />
                            <p class="text-danger" v-if="errors.email">
                                {{ errors.email[0] }}
                            </p>
                        </div>
                        <div
                            class="form-group col-md-6"
                            :class="{ 'has-error': errors.position_name }"
                        >
                            <label for>Position</label>
                            <input
                                type="text"
                                minlength="12"
                                maxlength="12"
                                class="form-control form-control-sm"
                                :class="{
                                    'border-danger': errors.position_name
                                }"
                                v-model="form.position_name"
                                disabled
                            />
                            <p class="text-danger" v-if="errors.position_name">
                                {{ errors.position_name[0] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group" v-if="history.length != 0">
            <formHistory></formHistory>
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
                                                :class="{
                                                    'has-error': errors.name
                                                }"
                                            >
                                                <label for>{{
                                                    $t("name")
                                                }}</label>
                                                <input
                                                    type="text"
                                                    class="form-control form-control-sm"
                                                    :class="{
                                                        'border-danger':
                                                            errors.name
                                                    }"
                                                    v-model="
                                                        form.candidate.name
                                                    "
                                                    disabled
                                                />
                                                <p
                                                    class="text-danger"
                                                    v-if="errors.name"
                                                >
                                                    {{ errors.name[0] }}
                                                </p>
                                            </div>
                                            <b-form-group
                                                class="col-md-6"
                                                :label="$t('gender')"
                                            >
                                                <b-form-radio-group
                                                    v-model="
                                                        form.candidate.gender
                                                    "
                                                    :options="genderOptions"
                                                    disabled
                                                ></b-form-radio-group>
                                                <p
                                                    class="text-danger"
                                                    v-if="errors.gender"
                                                >
                                                    {{ errors.gender[0] }}
                                                </p>
                                            </b-form-group>
                                        </div>
                                        <div class="form-row">
                                            <div
                                                class="form-group col-md-6"
                                                :class="{
                                                    'has-error': errors.address
                                                }"
                                            >
                                                <label for>Address</label>

                                                <textarea
                                                    class="form-control"
                                                    :class="{
                                                        'border-danger':
                                                            errors.address
                                                    }"
                                                    v-model="
                                                        form.candidate.address
                                                    "
                                                    disabled
                                                ></textarea>
                                                <p
                                                    class="text-danger"
                                                    v-if="errors.address"
                                                >
                                                    {{ errors.address[0] }}
                                                </p>
                                            </div>
                                            <div
                                                class="form-group col-md-6"
                                                :class="{
                                                    'has-error':
                                                        errors.post_code
                                                }"
                                            >
                                                <label for>Post Code</label>
                                                <input
                                                    type="number"
                                                    minlength="16"
                                                    maxlength="16"
                                                    class="form-control"
                                                    :class="{
                                                        'border-danger':
                                                            errors.post_code
                                                    }"
                                                    v-model="
                                                        form.candidate.post_code
                                                    "
                                                    disabled
                                                />
                                                <p
                                                    class="text-danger"
                                                    v-if="errors.post_code"
                                                >
                                                    {{ errors.post_code[0] }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div
                                                class="form-group col-md-6"
                                                :class="{
                                                    'has-error':
                                                        errors.birth_place
                                                }"
                                                disabled
                                            >
                                                <label for>{{
                                                    $tc("birth place")
                                                }}</label>
                                                <v-select
                                                    v-model="
                                                        form.candidate
                                                            .birth_place
                                                    "
                                                    :options="cities.data"
                                                    label="nama_kabkota"
                                                    :reduce="
                                                        nama_kabkota =>
                                                            nama_kabkota.nama_kabkota
                                                    "
                                                    :class="{
                                                        'border-danger':
                                                            errors.birth_place
                                                    }"
                                                    disabled
                                                ></v-select>
                                                <p
                                                    class="text-danger"
                                                    v-if="errors.birth_place"
                                                >
                                                    {{ errors.birth_place[0] }}
                                                </p>
                                            </div>
                                            <div
                                                class="form-group col-md-6"
                                                :class="{
                                                    'has-error': errors.birth_at
                                                }"
                                            >
                                                <label for>{{
                                                    $tc("birth date")
                                                }}</label>
                                                <date-picker
                                                    format="YYYY-MM-DD"
                                                    value-type="format"
                                                    type="date"
                                                    :class="{
                                                        'border-danger':
                                                            errors.birth_at
                                                    }"
                                                    v-model="
                                                        form.candidate.birth_at
                                                    "
                                                    disabled
                                                />
                                                <p
                                                    class="text-danger"
                                                    v-if="errors.birth_at"
                                                >
                                                    {{ errors.birth_at[0] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div
                                        class="form-group col-md-4"
                                        :class="{
                                            'has-error':
                                                errors.marital_status_id
                                        }"
                                    >
                                        <label for>{{
                                            $tc("marital status")
                                        }}</label>
                                        <v-select
                                            v-model="
                                                form.candidate.marital_status_id
                                            "
                                            :options="maritalStatuses.data"
                                            label="name"
                                            :reduce="name => name.id"
                                            :class="{
                                                'border-danger':
                                                    errors.marital_status_id
                                            }"
                                            disabled
                                        ></v-select>
                                        <p
                                            class="text-danger"
                                            v-if="errors.marital_status_id"
                                        >
                                            {{ errors.marital_status_id[0] }}
                                        </p>
                                    </div>
                                    <div
                                        class="form-group col-md-4"
                                        :class="{
                                            'has-error': errors.religion_id
                                        }"
                                    >
                                        <label for>{{ $tc("religion") }}</label>
                                        <v-select
                                            v-model="form.candidate.religion_id"
                                            :options="religions.data"
                                            label="name"
                                            :reduce="name => name.id"
                                            :class="{
                                                'border-danger':
                                                    errors.religion_id
                                            }"
                                            disabled
                                        ></v-select>
                                        <p
                                            class="text-danger"
                                            v-if="errors.religion_id"
                                        >
                                            {{ errors.religion_id[0] }}
                                        </p>
                                    </div>
                                    <div
                                        class="form-group col-md-4"
                                        :class="{ 'has-error': errors.ktp }"
                                    >
                                        <label for>{{ $t("ktp") }}</label>
                                        <input
                                            type="number"
                                            class="form-control form-control-sm"
                                            :class="{
                                                'border-danger': errors.ktp
                                            }"
                                            v-model="form.candidate.ktp"
                                            disabled
                                        />
                                        <p
                                            class="text-danger"
                                            v-if="errors.ktp"
                                        >
                                            {{ errors.ktp[0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div
                                        class="form-group col-md-3"
                                        :class="{ 'has-error': errors.has_sim }"
                                    >
                                        <label for>{{ $tc("Has License") }}</label>
                                        <b-form-checkbox
                                            v-model="form.candidate.has_sim"
                                            id="checkbox-4"
                                            name="checkbox-4"
                                            value="Yes"
                                            unchecked-value="No"
                                            disabled
                                        >
                                            Yes
                                        </b-form-checkbox>
                                        <p class="text-danger" v-if="errors.has_sim">
                                            {{ errors.has_sim[0] }}
                                        </p>
                                    </div>
                                    <div v-if="form.candidate.has_sim == 'Yes'" class="form-group col-md-3">
                                        <label>Vehicle Type</label>
                                        <v-select
                                            v-model="form.candidate.vehicle_type"
                                            label="text"
                                            :reduce="text => text.value"
                                            :options="vehicleTypeOptions"
                                            disabled
                                        ></v-select>
                                    </div>
                                    <div
                                    v-if="form.candidate.has_sim == 'Yes'"
                                        class="form-group col-md-6"
                                        :class="{ 'has-error': errors.sim }"
                                    >
                                        <label for>{{
                                            $tc("Drive License Number")
                                        }}</label>
                                        <input
                                            type="number"
                                            class="form-control form-control-sm"
                                            :class="{
                                                'border-danger': errors.sim
                                            }"
                                            v-model="form.candidate.sim"
                                            disabled
                                        />
                                        <p
                                            class="text-danger"
                                            v-if="errors.sim"
                                        >
                                            {{ errors.sim[0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div
                                        class="form-group col-md-6"
                                        :class="{ 'has-error': errors.email }"
                                    >
                                        <label for>{{ $tc("Email") }}</label>
                                        <input
                                            type="email"
                                            class="form-control"
                                            :class="{
                                                'border-danger': errors.email
                                            }"
                                            v-model="form.candidate.email"
                                            disabled
                                        />
                                        <p
                                            class="text-danger"
                                            v-if="errors.email"
                                        >
                                            {{ errors.email[0] }}
                                        </p>
                                    </div>
                                    <div
                                        class="form-group col-md-6"
                                        :class="{
                                            'has-error': errors.phone_number
                                        }"
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
                                                :class="{
                                                    'border-danger':
                                                        errors.phone_number
                                                }"
                                                v-model="
                                                    form.candidate.phone_number
                                                "
                                                disabled
                                            />
                                            <p
                                                class="text-danger"
                                                v-if="errors.phone_number"
                                            >
                                                {{ errors.phone_number[0] }}
                                            </p>
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
                                                            index) in form
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
                                                    index) in form.languages"
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
                                                <textarea class="form-control" v-model="form.candidate.achivement" disabled></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Thesis Title</label> 
                                                <input type="text" class="form-control" v-model="form.candidate.thesis_title" disabled></textarea>
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
                                                            index) in form.candidate.candidate_trainings"
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
                                                                    $t("Phone Number")
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
                                                                        "Start Year"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    $t(
                                                                        "End Year"
                                                                    )
                                                                }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr
                                                            v-for="(row,
                                                            index) in form.candidate.candidate_job_experiences"
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
                                                            index) in form.references"
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
                                                        <label>In Previous Company Salary</label>
                                                        <b-input-group prepend="Rp." class="mb-2 mr-sm-2 mb-sm-0"> 
                                                            <b-input type="text" v-int class="form-control" :value="formatSalary(form.candidate.last_salary)" disabled></b-input>
                                                        </b-input-group>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label>In Previous Company Benefit</label> 
                                                        <input type="text" class="form-control" v-model="form.candidate.last_facility" disabled></textarea>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label>Achivement During Work</label> 
                                                        <textarea class="form-control" v-model="form.candidate.achivement_during_work" disabled></textarea>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label>In Previous Company Job Desc</label> 
                                                        <textarea class="form-control" v-model="form.candidate.last_job_desc" disabled></textarea>
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
                                                                    $t("Phone Number")
                                                                }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr
                                                            v-for="(row,
                                                            index) in form.candidate.candidate_parents"
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
                                                            index) in form.candidate.candidate_siblings"
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
                                                            index) in form.candidate.candidate_couples"
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
                                                            index) in form.candidate.candidate_childrens"
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
                                                        v-model="form.candidate.applying_reason"
                                                        disabled
                                                    >
                                                    </textarea>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>Your preferred work environment</label> 
                                                    <v-select
                                                            v-model="form.candidate.work_environment"
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
                                                                :value="formatSalary(form.candidate.expected_salaries)"
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
                                                            v-model="form.candidate.expected_facilities"
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
                                                        v-model="form.candidate.work_accident"
                                                        id="checkbox-3"
                                                        name="checkbox-3"
                                                        value="Yes"
                                                        disabled
                                                        unchecked-value="No"
                                                    >
                                                        Yes
                                                    </b-form-checkbox>
                                                </div>
                                                <div v-if="form.candidate.work_accident != null" class="form-group col-md-6">
                                                    <label>When</label> 
                                                    <input
                                                        type="date"
                                                        disabled
                                                        class="form-control"
                                                        v-model="form.candidate.date_of_accident"
                                                    />
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-3">
                                                    <label>Have you ever check your psychologi?</label> 
                                                    <b-form-checkbox
                                                        v-model="form.candidate.psychological_check"
                                                        id="checkbox-2"
                                                        name="checkbox-2"
                                                        value="Yes"
                                                        disabled
                                                        unchecked-value="No"
                                                    >
                                                        Yes
                                                    </b-form-checkbox>
                                                </div>
                                                    <div v-if="form.candidate.psychological_check == 'Yes'" class="form-group col-md-3">
                                                        <label>When</label> 
                                                        <input
                                                            type="date"
                                                            class="form-control"
                                                            disabled
                                                            v-model="form.candidate.date_of_check"
                                                        />
                                                    </div>
                                                    <div v-if="form.candidate.psychological_check == 'Yes'" class="form-group col-md-3">
                                                        <label>Company Name</label> 
                                                        <input
                                                                type="text"
                                                                class="form-control form-control"
                                                                
                                                                v-model="form.candidate.check_place"
                                                                disabled
                                                            />
                                                    </div>
                                                    <div v-if="form.candidate.psychological_check == 'Yes'" class="form-group col-md-3">
                                                        <label>Purpose</label> 
                                                        <input
                                                                type="text"
                                                                class="form-control form-control"
                                                                disabled
                                                                v-model="form.candidate.check_purpose"
                                                        />
                                                    </div>
                                            </div>
                                               
                                        </div>
                                    </div>
                                              <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5>In Company Aquintance</h5>
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
                                                    index) in form.candidate.candidate_aquintances"
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
                                                    index) in form.emergencies"
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
                                                    index) in form.candidate.candidate_files"
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
        </div> <!-- akhir -->
        <div class="form-row">
            <div v-if="form.status_recruitment_id == 4" class="col-md-6">
                <h4>Not Attending Reason</h4>
            </div>
            <div v-else-if="history.length != 0" class="col-md-6">
                <h4>
                    Recruitment History Process
                </h4>
            </div>
            <div class="col-md-6">
                
            </div>
        </div>
        <hr />
        <div v-if="form.status_recruitment_id == 4">
            <div
                class="form-group"
                :class="{ 'has-error': errors.recruitmentNote }"
            >
                <label for>{{ $t("Note") }}</label>
                <textarea
                    cols="5"
                    rows="5"
                    class="form-control"
                    v-model="form.recruitmentNote"
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
                        v-model="form.newStatus"
                        id="checkbox-2"
                        name="checkbox-2"
                        value="5"
                        unchecked-value="4"
                    >
                        Reschedule
                    </b-form-checkbox>
                </div>
                <div
                    v-if="form.newStatus == 5"
                    class="form-group col-md-8"
                    :class="{ 'has-error': errors.recruitment_date }"
                >
                    <label>{{ $t("Test Date") }}</label>
                    <input
                        type="date"
                        v-model="form.recruitment_date"
                        class="form-control col-md-12"
                        :class="{
                            'border-danger': errors.recruitment_date
                        }"
                    />
                </div>
            </div>
        </div>
        <div v-else-if="form.status_recruitment_id == 1">
            <div v-if="history.length != 0">
            <b-card no-body>
                <b-tabs pills card vertical>
                    <div v-for="(data, index) in history" v-bind:key="index">
                        <b-tab
                            :title="data[1].recruitment_step.name"
                            :active="index == 0"
                        >
                            <b-card-text>
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td>
                                                &nbsp;
                                                <b>
                                                    {{ $t("Description") }}
                                                </b>
                                                <p>
                                                    &nbsp;
                                                    {{
                                                        data[1].recruitment_step
                                                            .description
                                                    }}
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-striped">
                                    <tbody>
                                        <tr
                                            v-for="(details, index) in data"
                                            v-bind:key="index"
                                        >
                                            <td>
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
                                                        `detail-${data[1].recruitment_step.id}-${index}`
                                                    "
                                                >
                                                    <i
                                                        class="bx bx-help-circle"
                                                    ></i>
                                                </span>
                                                <b-modal
                                                    :id="
                                                        `detail-${data[1].recruitment_step.id}-${index}`
                                                    "
                                                    size="lg"
                                                    scrollable
                                                    :title="
                                                        `${details.recruitment_step_parameter.name}`
                                                    "
                                                    ok-only
                                                    no-close-on-backdrop
                                                >
                                                    <p>
                                                        {{
                                                            details
                                                                .recruitment_step_parameter
                                                                .description
                                                        }}
                                                    </p>
                                                    <div class="card m-b-30">
                                                        <div
                                                            class="card-header"
                                                        >
                                                            <div class="col-9">
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
                                                                            id="rating-lg-no-border"
                                                                            no-border
                                                                            readonly
                                                                            background-color="black"
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
                                                        details.score.score
                                                    "
                                                    variant="warning"
                                                    class="mb-2"
                                                    inline
                                                    no-border
                                                    readonly
                                                ></b-form-rating>
                                                {{ details.score.name }}
                                            </td>
                                            <td
                                                class="float-right align-middle"
                                                rowspan="2"
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
                            <br />
                            <div class="form-row">
                                <div class="col-md-5">
                                    <a
                                        v-b-modal="
                                            `attachment-${data[1].recruitment_id}-${index}`
                                        "
                                        class="btn btn-info text-white"
                                        ><span
                                            class="fa fa-eye text-white"
                                        ></span>
                                        Preview</a
                                    >
                                    <b-modal
                                        :id="
                                            `attachment-${data[1].recruitment_id}-${index}`
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
                                                        index) in data[1]
                                                            .recruitment
                                                            .recruitment_files"
                                                    >
                                                        <td>
                                                            {{ uploads.note }}
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
                                    Total Score :
                                    {{ data[1].recruitment.total_score }}
                                </div>
                                <div class="col-md-3">
                                    <span
                                        class="badge badge-pill badge-soft-success mb-2 font-size-16 float-right"
                                        >{{ $tc("Success") }}</span>
                                </div>
                            </div>
                        </b-tab>
                    </div>
                </b-tabs>
            </b-card>
            <hr />
            </div>
            <h3>Asessment : {{ form.recruitment_step.name }}</h3>
            <hr />
            <div class="form-row">
                <table class="table table-striped">
                    <tbody>
                        <tr
                            v-for="(data, index) in form.recruitment_step
                                .recruitment_step_parameter"
                            v-bind:key="index"
                        >
                            <td>
                                <label>{{ data.name }}</label>
                                <span
                                    class="text-info mr-2"
                                    v-b-modal="
                                        `detail-${data.recruitment_step_id}-${index}`
                                    "
                                >
                                    <i class="bx bx-help-circle"></i>
                                </span>
                                <b-modal
                                    :id="
                                        `detail-${data.recruitment_step_id}-${index}`
                                    "
                                    size="lg"
                                    scrollable
                                    :title="`${data.name}`"
                                    ok-only
                                    no-close-on-backdrop
                                >
                                    <p>
                                        {{ data.description }}
                                    </p>
                                    <div class="card m-b-30">
                                        <div class="card-header">
                                            <div class="col-9">
                                                <h5 class="card-title">
                                                    {{
                                                        $tc("Asessment Details")
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
                                                    <th width="25%">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;{{
                                                            $t("Rating")
                                                        }}
                                                    </th>
                                                    <th>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;{{
                                                            $t("description")
                                                        }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(parameterScore,
                                                    index) in data.recruitment_step_parameter_scores"
                                                >
                                                    <td>
                                                        <b-form-rating
                                                            v-model="
                                                                parameterScore.score_id
                                                            "
                                                            variant="warning"
                                                            class="mb-2 no-bg"
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
                                <input
                                    type="hidden"
                                    v-model="data.recruitment_step_parameter_id"
                                />
                                <b-form-rating
                                    @change="test(data.score)"
                                    :value="data.score"
                                    v-model="data.score"
                                    size="lg"
                                    variant="warning"
                                    class="mb-2"
                                    no-border
                                ></b-form-rating>
                                <center>
                                    <b
                                        >{{
                                            (ratingValue(data.score)[0] || {})
                                                .name
                                        }}
                                    </b>
                                </center>
                            </td>
                            <td>
                                <label>Note : </label>
                                <br />
                                <textarea
                                    rows="3"
                                    class="form-control"
                                    v-model="data.note"
                                ></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="sumScore() < form.recruitment_step.min_score">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <p>Total Score : {{ sumScore() }}</p>
                    </div>
                    <div class="form-group col-md-4">
                        <center>
                            <span
                                            class="badge badge-pill badge-soft-danger font-size-16"
                                            >{{ $tc("Failed") }}</span>
                        </center>
                    </div>
                    <div class="form-group col-md-4">
                        <p>
                            Minimal Score :
                            {{ form.recruitment_step.min_score }}
                        </p>
                    </div>
                </div>
            </div>
            <div v-if="sumScore() >= form.recruitment_step.min_score">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <p>Total Score : {{ sumScore() }}</p>
                    </div>
                    <div class="form-group col-md-4">
                        <center>
                            <span
                                            class="badge badge-pill badge-soft-success font-size-16"
                                            >{{ $tc("Success") }}</span>
                        </center>
                    </div>
                    <div class="form-group col-md-4">
                        <p class="float-right">
                            Minimal Score :
                            {{ form.recruitment_step.min_score }}
                        </p>
                    </div>
                </div>
                <div v-if="history.length == 0">
                    {{ changeAccepted() }}
                    {{ changeDisabledAccepted() }}
                </div>
            </div>

            <div
                class="form-group"
                :class="{ 'has-error': errors.recruitment_files }"
            >
                <div class="card m-b-30">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h5 class="card-title">
                                    {{ $tc("File Upload", 2) }}
                                </h5>
                            </div>
                            <div class="col-3">
                                <button
                                    type="button"
                                    @click="addFile()"
                                    class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
                                    v-b-tooltip.hover
                                    :title="$tc('add')"
                                >
                                    <i class="mdi mdi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-sm table-hover">
                        <thead>
                            <tr>
                                <th width="50%">
                                    &nbsp;{{ $t("File Upload") }}
                                </th>
                                <th>{{ $t("Note") }}</th>
                                <th width="70px">{{ $t("action") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(row, index) in form.recruitment_files"
                                :key="index"
                                class="justify-content-between align-items-center"
                            >
                                <td>
                                    <input
                                        type="file"
                                        class="form-control form-control"
                                        @change="uploadFile($event, index)"
                                        :class="{
                                            'border-danger':
                                                errors[`${index}.file`]
                                        }"
                                    />
                                    <br />
                                </td>
                                <td>
                                    <textarea
                                        class="form-control form-control"
                                        :class="{
                                            'border-danger':
                                                errors[`${index}.note`]
                                        }"
                                        v-model="row.note"
                                    ></textarea>
                                </td>
                                <td class="text-cenxter">
                                    <a
                                        href="javascript:void(0)"
                                        @click="removeFile(index)"
                                        class="text-danger"
                                        v-b-tooltip.hover
                                        :title="$tc('delete')"
                                    >
                                        <i
                                            class="mdi mdi-trash-can-outline font-size-16"
                                        ></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="form-row">
                <div
                    v-if="sumScore() >= form.recruitment_step.min_score"
                    class="form-group col-md-6"
                    :class="{ 'has-error': errors.next_step }"
                >
                <ValidationProvider rules="required" v-slot="{ errors }">
                    <label for="">{{ $t("Next Test Step") }}</label>
                    <v-select
                        v-model="form.next_step"
                        :options="recruitmentSteps.data"
                        label="name"
                        :reduce="name => name.id"
                        :class="{
                            'border-danger': errors.next_step
                        }"
                    ></v-select>
                    <p class="text-danger" v-if="errors.next_step">
                        {{ errors.next_step[0] }}
                    </p>
                </ValidationProvider>

                </div>
                <div
                    v-if="sumScore() >= form.recruitment_step.min_score"
                    class="form-group col-md-6"
                    :class="{ 'has-error': errors.next_date }"
                >
                    <label for="">{{ $tc("Next Test Date") }}</label>
                    <!-- buat datepicker disini -->
                    <date-picker
                        :class="{ 'border-danger': errors.next_date }"
                        v-model="form.next_date"
                        format="YYYY-MM-DD"
                        value-type="format"
                        type="date"
                        :default-value="new Date()"
                        :disabled-date="disabledBeforeToday"
                    />
                    <p class="text-danger" v-if="errors.next_date">
                        {{ errors.next_date[0] }}
                    </p>
                </div>
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
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import { ValidationProvider } from 'vee-validate';
import { extend } from 'vee-validate';
import { required, email } from 'vee-validate/dist/rules';

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
            radioOptions: [
                { text: this.$tc("Yes"), value: "Yes" },
                { text: this.$tc("No"), value: "No" }
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
            status: 0
        };
    },
    created() {
        this.loadrecruitmentStepParameters();
        this.loadScores();
        this.loadEmployees();
        this.loadCandidates();
        this.loadDepartments();
        this.loadReligions();
        this.loadBloodTypes();
        this.loadMaritalStatuses();
        this.loadCompanies();
        this.loadPayrollTypes();
        this.loadCities();
        this.loadPositions();
        // this.addFile();
    },
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("recruitment", {
            form: state => state.form, //MENGAMBIL STATE DATA
            history: state => state.collectionHistory //MENGAMBIL STATE DATA
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
        ...mapState("payrollType", {
            payrollTypes: state => state.collectionList
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
        ...mapState("bloodType", {
            bloodTypes: state => state.collectionList
        }),
        ...mapState("maritalStatus", {
            maritalStatuses: state => state.collectionList
        }),
        ...mapState("company", {
            companies: state => state.collectionList
        }),
        ...mapState("position", {
            positions: state => state.collectionList
        }),
        ...mapState("employee", {
            employees: state => state.collectionList
        }),
        scoreValue() {
            return this.form.recruitment_step.recruitment_step_parameter.map(
                item => item["score"]
            );
        }
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
        ...mapActions("bloodType", { loadBloodTypes: "loadList" }),
        ...mapActions("payrollType", { loadPayrollTypes: "loadList" }),
        ...mapActions("maritalStatus", { loadMaritalStatuses: "loadList" }),
        ...mapActions("employee", { loadEmployees: "loadList" }),
        ...mapActions("company", { loadCompanies: "loadList" }),
        ...mapActions("position", { loadPositions: "loadList" }),
        ...mapActions("recruitment", ["update"]), //PANGGIL ACTIONS
        uploadFile(event, index) {
            console.log(event);
            this.form.recruitment_files[index].caption = event.target.files[0];
            console.log(this.form.recruitment_files);
        },
        addFile() {
            this.form.recruitment_files.push({ caption: null, note: null });
        },
        removeFile(index) {
            if (this.form.recruitment_files.length > 1) {
                this.form.recruitment_files.splice(index, 1);
            }
        },
        changeAccepted() {
                this.$emit("onAccepted", true);
        },
        changeDisabledAccepted() {
            if(this.form.next_step != null && this.form.next_date != null){
                this.$emit("onDisabledAccepted", true);
            }else{
                this.$emit("onDisabledAccepted", false);
            }
        },
        handlePreview(file) {
            console.log(file);
        },
        changed: function() {
            console.log(this.notes);
        },

        ratingValue(id) {
            let data = _.filter(this.scores.data, {
                id: id
            });
            return data;
            // console.log(data)
        },
        uploadPhoto(event) {
            let inputImage = event.target;
            if (inputImage.files && inputImage.files[0]) {
                let reader = new FileReader();
                reader.onload = e => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(inputImage.files[0]);
            }
            // console.log(event);
            this.form.photo = event.target.files[0];
            // console.log(this.form.photo)
        },
        test(value) {
            if (value != null) {
                this.scoreChecked[0] = 0;
                this.scoreChecked[value] = value;
            }
            this.loadrecruitmentSteps(this.form.candidate_id);
        },
        disabledAfterToday(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return date > today;
        },
        formatSalary(value) {
            let val = (value/1).toFixed(2).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        disabledBeforeToday(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return date < today;
        },
        sumScore() {
            let scoreList = Object.values(this.scoreValue);
            this.form.total_score = scoreList.reduce(
                (total, num) => total + num,
                0
            );

            // console.log("test");

            // this.changeAccepted();
            // console.log(this.form.score);

            return scoreList.reduce((total, num) => total + num, 0);
        }
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
    },
    components: {
        "v-select": vSelect,
        DatePicker,
        ValidationProvider
    }
};
</script>