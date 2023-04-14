    <template>    
        <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <h5 class="card-title">
                                                {{ $tc("Personal data", 2) }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                                                : '/images/default-user.png'
                                        "
                                        @click="uploadPhoto()"
                                        class="img-fluid"
                                    />
                                    <input
                                        type="file"
                                        class="d-none"
                                        ref="image"
                                        @change="getImage($event)"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-row">
                                <div
                                    class="form-group col-md-6"
                                    :class="{ 'has-error': errors.name }"
                                >
                                    <label for>{{ $t("name") }}</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        :class="{
                                            'border-danger': errors.name
                                        }"
                                        v-model="form.candidate.name"        
                                        v-on:keypress="isLetter($event)"
                                    />
                                    <p class="text-danger" v-if="errors.name">
                                        {{ errors.name[0] }}
                                    </p>
                                </div>
                                <b-form-group
                                    class="col-md-6"
                                    :label="$t('gender')"
                                > 
                                    <b-form-radio-group
                                        v-model="form.candidate.gender"
                                        :options="genderOptions"
                                    ></b-form-radio-group>
                                <p class="text-danger" v-if="errors.gender">
                                        {{ errors.gender[0] }}
                                    </p>
                                </b-form-group>
                            </div>
                            <div class="form-row">
                                <div
                                    class="form-group col-md-6"
                                    :class="{ 'has-error': errors.address }"
                                >
                                    <label for>Address</label>
                                        
                                    <textarea class="form-control" :class="{ 'border-danger': errors.address }"  v-model="form.candidate.address"></textarea>
                                    <p class="text-danger" v-if="errors.address">
                                        {{ errors.address[0] }}
                                    </p>
                                </div>
                                <div
                                    class="form-group col-md-6"
                                    :class="{ 'has-error': errors.post_code }"
                                >
                                    <label for>Post Code</label>
                                    <input
                                        type="text"
                                        v-int
                                        minlength="5"
                                        maxlength="5"
                                        class="form-control"
                                        :class="{ 'border-danger': errors.post_code }"
                                        v-model="form.candidate.post_code"
                                    />
                                    <p class="text-danger" v-if="errors.post_code">
                                        {{ errors.post_code[0] }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-row">
                                <div
                                    class="form-group col-md-6"
                                    :class="{ 'has-error': errors.birth_place }"
                                >
                                    <label for>{{ $tc("birth place") }}</label>
                                    <v-select
                                        v-model="form.candidate.birth_place"
                                        :options="cities.data"
                                        label="nama_kabkota"
                                        :reduce="nama_kabkota => nama_kabkota.nama_kabkota"
                                        :class="{ 'border-danger': errors.birth_place }"
                                    ></v-select>
                                    <p class="text-danger" v-if="errors.birth_place">
                                        {{ errors.birth_place[0] }}
                                    </p>
                                </div>
                                <div
                                    class="form-group col-md-6"
                                    :class="{ 'has-error': errors.birth_at }"
                                >
                                    <label for>{{ $tc("birth date") }}</label>
                                    <date-picker
                                        :class="{ 'border-danger': errors.birth_at }"
                                        v-model="form.candidate.birth_at"
                                        format="YYYY-MM-DD"
                                        value-type="format"
                                        type="date"
                                        :default-value="new Date()"
                                        :disabled-date="disabledAfterToday"
                                        
                                    />
                                    <p class="text-danger" v-if="errors.birth_at">
                                        {{ errors.birth_at[0] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div
                            class="form-group col-md-4"
                           :class="{ 'has-error': errors.marital_status_id }"
                        >
                                    <label for>{{ $tc("marital status") }}</label>
                                        <v-select
                                            v-model="form.candidate.marital_status_id"
                                            :options="maritalStatuses.data"
                                            label="name"
                                            :reduce="name => name.id"
                                            :class="{
                                                'border-danger': errors.marital_status_id
                                            }"
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
                            :class="{ 'has-error': errors.religion_id }"
                        >
                            <label for>{{ $tc("religion") }}</label>
                            <v-select
                                v-model="form.candidate.religion_id"
                                :options="religions.data"
                                label="name"
                                :reduce="name => name.id"
                                :class="{ 'border-danger': errors.religion_id }"
                            ></v-select>
                            <p class="text-danger" v-if="errors.religion_id">
                                {{ errors.religion_id[0] }}
                            </p>
                        </div>
                        <div
                            class="form-group col-md-4"
                            :class="{ 'has-error': errors.ktp }"
                        >
                            <label for>{{ $t("Identity Card Number") }}</label>
                            <input
                                type="text"
                                v-int
                                maxlength="16"
                                class="form-control form-control-sm"
                                :class="{ 'border-danger': errors.ktp }"
                                v-model="form.candidate.ktp"
                                
                            />
                            <p class="text-danger" v-if="errors.ktp">
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
                            ></v-select>
                        </div>
                            <div v-if="form.candidate.has_sim == 'Yes'"
                                class="form-group col-md-6"
                                :class="{ 'has-error': errors.sim }"
                            >
                                <label for>{{ $tc("Drive License Number") }}</label>
                                <input
                                    type="text"
                                    v-int
                                    minlength="12"
                                    maxlength="12"
                                    class="form-control form-control-sm"
                                    :class="{ 'border-danger': errors.sim }"
                                    v-model="form.candidate.sim"
                                />
                                <p class="text-danger" v-if="errors.sim">
                                    {{ errors.no_sim[0] }}
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
                                    type="text"
                                    class="form-control"
                                    placeholder="example@gmail.com"
                                    :class="{ 'border-danger': errors.email }"
                                    v-model="form.candidate.email"
                                    v-on:keyup="validateEmail(form.candidate.email)"
                                />
                                <span v-if="msg.email" class="text-danger">{{
                                    msg.email
                                }}</span>
                                <p class="text-danger" v-if="errors.email">
                                    {{ errors.email[0] }}
                                </p>
                            </div>
                        <div
                            class="form-group col-md-6"
                            :class="{ 'has-error': errors.phone_number }"
                        >
                            <label for>{{ $tc("Phone Number") }}</label>
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
                                    v-int
                                    maxlength="11"
                                    class="form-control col-md-9"
                                    v-model="form.candidate.phone_number"
                                    placeholder="812345xxx"
                                />
                            </div>
                            <p class="text-danger" v-if="errors.phone_number">
                                {{ errors.phone_number[0] }}
                            </p>
                        </div>
                    </div>
                    
                    <div>
                        <b-tabs content-class="mt-3">
                            <b-tab active>
                                <template v-slot:title>
                                     <span class="fa fa-user-graduate"></span>&nbsp;Education Backgrounds
                                </template>
                                <div class="form-group">
                                    <div class="card m-b-30">
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    
                                                </div> 
                                                <div class="col-3">
                                                    <button
                                                        type="button"
                                                        @click="addEducation()"
                                                        class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
                                                        v-b-tooltip.hover
                                                        :title="$tc('add')"
                                                    >
                                                        <i class="mdi mdi-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <table
                                            class="table table-bordered table-sm table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th>
                                                        &nbsp;{{ $t("School Name") }}
                                                    </th>
                                                    <th>{{ $t("Major") }}</th>
                                                    <th width="150px">{{ $t("City") }}</th>
                                                    <th>{{ $t("Entry Year") }}</th>
                                                    <th>{{ $t("Graduation Year") }}</th>
                                                    <th>{{ $t("Score") }}</th>
                                                    <th width="70px">{{ $t("action") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in form.educations"
                                                    :key="index"
                                                    class="justify-content-between align-items-center"
                                                >
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.school_name`]
                                                            }"
                                                            v-model="row.school_name"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.major`]
                                                            }"
                                                            v-model="row.major"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <v-select
                                                            v-model="row.city"
                                                            :options="cities.data"
                                                            label="nama_kabkota"
                                                            :reduce="nama_kabkota => nama_kabkota.nama_kabkota"
                                                            :class="{ 'border-danger': errors.city }"
                                                        ></v-select>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <DatePicker
                                                            :class="{
                                                                'border-danger': errors.entry_year
                                                            }"
                                                            v-model="row.entry_year"
                                                            type="year"
                                                            format="YYYY"
                                                            value-type="format"
                                                            :disabled-date="disabledAfterToday"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <DatePicker
                                                            :class="{
                                                                'border-danger': errors.graduation_year
                                                            }"
                                                            v-model="row.graduation_year"
                                                            type="year"
                                                            format="YYYY"
                                                            value-type="format"
                                                            :disabled-date="disabledFromEntryYear"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="text"
                                                            v-on:keypress="isNumber($event)"
                                                            v-model="row.score"
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.score`]
                                                            }"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td class="text-cenxter">
                                                        <a
                                                            href="javascript:void(0)"
                                                            @click="removeEducation(index)"
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
                                    <div class="card m-b-30">
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5>Languages</h5>
                                                </div> 
                                                <div class="col-3">
                                                    <button
                                                        type="button"
                                                        @click="addLanguage()"
                                                        class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
                                                        v-b-tooltip.hover
                                                        :title="$tc('add')"
                                                    >
                                                        <i class="mdi mdi-plus"></i>
                                                    </button>
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
                                                    <th width="70px">{{ $t("action") }}</th>
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
                                                        <v-select
                                                            v-model="row.language"
                                                            label="text"
                                                            :reduce="text => text.value"
                                                            :options="languageOptions"
                                                        ></v-select>
                                                        <p
                                                        class="text-danger"
                                                        v-if="errors[`dataLanguages.${index}.language`]"
                                                        >{{ errors[`dataLanguages.${index}.language`][0] }}</p>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <v-select
                                                            v-model="row.speaking"
                                                            :options="scores.data"
                                                            label="name"
                                                            :reduce="name => name.name"
                                                        ></v-select>
                                                        <p
                                                        class="text-danger"
                                                        v-if="errors[`dataLanguages.${index}.speaking`]"
                                                        >{{ errors[`dataLanguages.${index}.speaking`][0] }}</p>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <v-select
                                                            v-model="row.writing"
                                                            :options="scores.data"
                                                            label="name"
                                                            :reduce="name => name.name"
                                                        ></v-select>
                                                        <p
                                                        class="text-danger"
                                                        v-if="errors[`dataLanguages.${index}.writing`]"
                                                        >{{ errors[`dataLanguages.${index}.writing`][0] }}</p>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <v-select
                                                            v-model="row.reading"
                                                            :options="scores.data"
                                                            label="name"
                                                            :reduce="name => name.name"
                                                        ></v-select>
                                                        <p
                                                        class="text-danger"
                                                        v-if="errors[`dataLanguages.${index}.reading`]"
                                                        >{{ errors[`dataLanguages.${index}.reading`][0] }}</p>
                                                        <br />
                                                    </td>
                                                    <td class="text-cenxter">
                                                        <a
                                                            href="javascript:void(0)"
                                                            @click="removeLanguage(index)"
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
                                                <textarea class="form-control" v-model="form.candidate.achivement"></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Thesis Title</label> 
                                                <input type="text" class="form-control" v-model="form.candidate.thesis_title"></textarea>
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
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    
                                                </div> 
                                                <div class="col-3">
                                                    <button
                                                        type="button"
                                                        @click="addTraining()"
                                                        class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
                                                        v-b-tooltip.hover
                                                        :title="$tc('add')"
                                                    >
                                                        <i class="mdi mdi-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <table
                                            class="table table-bordered table-sm table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;{{ $t("Training Type") }}</th>
                                                    <th>{{ $t("Organizer") }}</th>
                                                    <th>{{ $t("Year") }}</th>
                                                    <th width="70px">{{ $t("action") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in form.trainings"
                                                    :key="index"
                                                    class="justify-content-between align-items-center"
                                                >
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.training_type`]
                                                            }"
                                                            v-model="row.training_type"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.organizer`]
                                                            }"
                                                            v-model="row.organizer"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <DatePicker
                                                            :class="{
                                                                'border-danger': errors.year
                                                            }"
                                                            v-model="row.year"
                                                            type="year"
                                                            value-type="format"
                                                            format="YYYY"
                                                            :disabled-date="disabledAfterToday"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td class="text-cenxter">
                                                        <a
                                                            href="javascript:void(0)"
                                                            @click="removeTraining(index)"
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
                            </b-tab>
                            <b-tab>
                                <template v-slot:title>
                                     <span class="fa fa-user-tie"></span>&nbsp;Working Experiences
                                </template>
                                <div class="form-group">
                                    <div class="card m-b-30">
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                </div> 
                                                <div class="col-3">
                                                    <button
                                                        type="button"
                                                        @click="addJob()"
                                                        class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
                                                        v-b-tooltip.hover
                                                        :title="$tc('add')"
                                                    >
                                                        <i class="mdi mdi-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <table
                                            class="table table-bordered table-sm table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th>
                                                        &nbsp;{{ $t("Company Name") }}
                                                    </th>
                                                    <th>{{ $t("Address") }}</th>
                                                    <th>{{ $t("Phone Number") }}</th>
                                                    <th>{{ $t("Position") }}</th>
                                                    <th>{{ $t("Start Date") }}</th>
                                                    <th>{{ $t("End Date") }}</th>
                                                    <th width="70px">{{ $t("action") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in form.job_experiences"
                                                    :key="index"
                                                    class="justify-content-between align-items-center"
                                                >
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.company_name`]
                                                            }"
                                                            v-model="row.company_name"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <textarea
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.address`]
                                                            }"
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
                                                                v-model="row.phone"
                                                            ></b-input>
                                                        </b-input-group>      
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.position`]
                                                            }"
                                                            v-model="row.position"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <date-picker
                                                            :class="{
                                                                'border-danger': errors.start_date
                                                            }"
                                                            v-model="row.start_date"
                                                            format="YYYY-MM-DD"
                                                            value-type="format"
                                                            type="date"
                                                            :disabled-date="disabledAfterToday"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <date-picker
                                                            :class="{
                                                                'border-danger': errors.end_date
                                                            }"
                                                            v-model="row.end_date"
                                                            format="YYYY-MM-DD"
                                                            value-type="format"
                                                            type="date"
                                                            :disabled-date="disabledAfterToday"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td class="text-cenxter">
                                                        <a
                                                            href="javascript:void(0)"
                                                            @click="removeJob(index)"
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
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5>Candidate References</h5>
                                                </div> 
                                                <div class="col-3">
                                                    <button
                                                        type="button"
                                                        @click="addReference()"
                                                        class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
                                                        v-b-tooltip.hover
                                                        :title="$tc('add')"
                                                    >
                                                        <i class="mdi mdi-plus"></i>
                                                    </button>
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
                                                    <th width="70px">{{ $t("action") }}</th>
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
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.name`]
                                                            }"
                                                            v-model="row.name"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <textarea
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.address`]
                                                            }"
                                                            v-model="row.address"
                                                        ></textarea>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.job`]
                                                            }"
                                                            v-model="row.job"
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
                                                                v-model="row.phone"
                                                            ></b-input>
                                                        </b-input-group>
                                                        <br />
                                                    </td>
                                                    <td class="text-cenxter">
                                                        <a
                                                            href="javascript:void(0)"
                                                            @click="removeReference(index)"
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
                                                        <b-input   
                                                            class="form-control col-md-12"
                                                            :value="value"
                                                            v-imask="mask.amount"
                                                            @complete="onComplete"  
                                                            placeholder='Enter number here'
                                                        ></b-input>
                                                    </b-input-group>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>In Previous Company Benefit</label> 
                                                <input type="text" class="form-control" v-model="form.candidate.last_facility"></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Achivement During Work</label> 
                                                <textarea class="form-control" v-model="form.candidate.achivement_during_work"></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>In Previous Job Desc</label> 
                                                <textarea class="form-control" v-model="form.candidate.last_job_desc"></textarea>
                                            </div>

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
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5 class="card-title">
                                                        {{ $tc("Parent", 2) }}
                                                    </h5>
                                                </div> 
                                                <div class="col-3">
                                                    <button
                                                        type="button"
                                                        @click="addParent()"
                                                        class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
                                                        v-b-tooltip.hover
                                                        :title="$tc('add')"
                                                    >
                                                        <i class="mdi mdi-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <table
                                            responsive class="table table-bordered table-lg table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th width="150px">
                                                        &nbsp;{{ $t("Type") }}
                                                    </th>
                                                    <th>{{ $t("Name") }}</th>
                                                    <th>{{ $t("Date of Birth") }}</th>
                                                    <th width="100px">{{ $t("Gender") }}</th>
                                                    <th>{{ $t("Address") }}</th>
                                                    <th>{{ $t("Phone Number") }}</th>
                                                    <th>{{ $t("Contact Emergency") }}</th>
                                                    <th width="70px">{{ $t("action") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in form.parents"
                                                    :key="index"
                                                    class="justify-content-between align-items-center"
                                                >
                                                    <td>
                                                        <v-select
                                                            v-model="row.type"
                                                            label="text"
                                                            :reduce="text => text.value"
                                                            :options="parentTypeOptions"
                                                        ></v-select>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.name`]
                                                            }"
                                                            v-model="row.name"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <date-picker
                                                            :class="{
                                                                'border-danger': errors.date_of_birth
                                                            }"
                                                            v-model="row.date_of_birth"
                                                            format="YYYY-MM-DD"
                                                            value-type="format"
                                                            type="date"
                                                            :disabled-date="disabledAfterToday"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <v-select
                                                            v-model="row.gender"
                                                            label="text"
                                                            :reduce="text => text.value"
                                                            :options="genderOptions"
                                                        ></v-select>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <textarea
                                                            class="form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.address`]
                                                            }"
                                                            v-model="row.address"
                                                        ></textarea>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <b-input-group prepend="+62" class="mb-2 mr-sm-2 mb-sm-0">
                                                            <b-input
                                                                type="text"
                                                                v-int
                                                                v-on:keyup="emergencyChanged(row.phone, index)"
                                                                maxlength="11"
                                                                class="form-control form-control"
                                                                :class="{
                                                                    'border-danger':
                                                                        errors[`${index}.phone`]
                                                                }"
                                                                v-model="row.phone"
                                                            ></b-input>
                                                        </b-input-group>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <b-form-checkbox
                                                            :disabled="!emergencyStatus[index]"
                                                            v-model="row.emergency"
                                                            v-bind:id="'checkbox-8-'+index+''"
                                                            v-bind:name="'checkbox-8-'+index+''"
                                                            value="Yes"
                                                            unchecked-value="No"
                                                        >
                                                            Yes
                                                        </b-form-checkbox>
                                                        <br />
                                                    </td>
                                                    <td class="text-cenxter">
                                                        <a
                                                            href="javascript:void(0)"
                                                            @click="removeParent(index)"
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
                                <div class="form-group">
                                    <div class="card m-b-30">
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5 class="card-title">
                                                        {{ $tc("Sibling", 2) }}
                                                    </h5>
                                                </div> 
                                                <div class="col-3">
                                                    <button
                                                        type="button"
                                                        @click="addSibling()"
                                                        class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
                                                        v-b-tooltip.hover
                                                        :title="$tc('add')"
                                                    >
                                                        <i class="mdi mdi-plus"></i>
                                                    </button>
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
                                                    <th>{{ $t("Date of Birth") }}</th>
                                                    <th>{{ $t("Gender") }}</th>
                                                    <th width="70px">{{ $t("action") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in form.siblings"
                                                    :key="index"
                                                    class="justify-content-between align-items-center"
                                                >
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.name`]
                                                            }"
                                                            v-model="row.name"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <date-picker
                                                            v-model="row.date_of_birth"
                                                            format="YYYY-MM-DD"
                                                            value-type="format"
                                                            type="date"
                                                            :disabled-date="disabledAfterToday"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <v-select
                                                            v-model="row.gender"
                                                            label="text"
                                                            :reduce="text => text.value"
                                                            :options="genderOptions"
                                                        ></v-select>
                                                        <br />
                                                    </td>
                                                    <td class="text-cenxter">
                                                        <a
                                                            href="javascript:void(0)"
                                                            @click="removeSibling(index)"
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
                                <div class="form-group">
                                    <div class="card m-b-30">
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5 class="card-title">
                                                        {{ $tc("Spouse", 2) }}
                                                    </h5>
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
                                                    <th>{{ $t("Date of Birth") }}</th>
                                                    <th>{{ $t("Gender") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    class="justify-content-between align-items-center"
                                                >
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            v-model="form.couple_name"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <date-picker
                                                            v-model="form.couple_date_of_birth"
                                                            :disabled-date="disabledAfterToday"
                                                            format="YYYY-MM-DD"
                                                            value-type="format"
                                                            type="date"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <v-select
                                                            v-model="form.couple_gender"
                                                            label="text"
                                                            :reduce="text => text.value"
                                                            :options="genderOptions"
                                                        ></v-select>
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
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h5 class="card-title">
                                                        {{ $tc("Children", 2) }}
                                                    </h5>
                                                </div> 
                                                <div class="col-3">
                                                    <button
                                                        type="button"
                                                        @click="addChildren()"
                                                        class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
                                                        v-b-tooltip.hover
                                                        :title="$tc('add')"
                                                    >
                                                        <i class="mdi mdi-plus"></i>
                                                    </button>
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
                                                    <th>{{ $t("Date of Birth") }}</th>
                                                    <th>{{ $t("Gender") }}</th>
                                                    <th width="70px">{{ $t("action") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in form.childrens"
                                                    :key="index"
                                                    class="justify-content-between align-items-center"
                                                >
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`${index}.name`]
                                                            }"
                                                            v-model="row.name"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <date-picker
                                                            v-model="row.date_of_birth"
                                                            format="YYYY-MM-DD"
                                                            value-type="format"
                                                            type="date"
                                                            :disabled-date="disabledAfterToday"
                                                        />
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <v-select
                                                            v-model="row.gender"
                                                            label="text"
                                                            :reduce="text => text.value"
                                                            :options="genderOptions"
                                                        ></v-select>
                                                        <br />
                                                    </td>
                                                    <td class="text-cenxter">
                                                        <a
                                                            href="javascript:void(0)"
                                                            @click="removeChildren(index)"
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
                            </b-tab>
                            <b-tab>
                                <template v-slot:title>
                                    <div v-if="errors.applying_reason || errors.work_environment || errors.expected_facilities || errors.expected_salaries || errors.work_out_of_town || errors.placed_out_of_town" class="text-danger">
                                     <span class="mdi mdi-alert-circle text-danger"></span>&nbsp;Job Application
                                    </div>
                                    <div v-else>                    
                                     <span class="fa fa-suitcase"></span>&nbsp;Job Application
                                    </div>
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
                                                        :class="{
                                                                'border-danger':
                                                                    errors[`applying_reason`]
                                                            }"
                                                    >
                                                    </textarea>
                                                    <p class="text-danger" v-if="errors.applying_reason">
                                                        {{ errors.applying_reason[0] }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>Your preferred work environment</label> 
                                                    <v-select
                                                            v-model="form.candidate.work_environment"
                                                            label="text"
                                                            :reduce="text => text.value"
                                                            :options="environmentOptions"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`work_environment`]
                                                            }"
                                                    ></v-select>
                                                    <p class="text-danger" v-if="errors.work_environment">
                                                        {{ errors.work_environment[0] }}
                                                    </p>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Expected Salaries</label> 
                                                        <b-input-group prepend="Rp." class="mb-2 mr-sm-2 mb-sm-0">
                                                            <b-input   
                                                                class="form-control col-md-12"
                                                                :value="value"
                                                                v-imask="mask.amount"
                                                                @complete="onCompleteExpect"  
                                                                placeholder='Enter number here'
                                                                :class="{
                                                                'border-danger':
                                                                    errors[`expected_salaries`]
                                                                }"
                                                            ></b-input>
                                                        </b-input-group>
                                                        <p class="text-danger" v-if="errors.expected_salaries">
                                                        {{ errors.expected_salaries[0] }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <label>Expected Facility</label> 
                                                    <input
                                                            type="text"
                                                            class="form-control form-control"
                                                            :class="{
                                                                'border-danger':
                                                                    errors[`expected_facilities`]
                                                            }"
                                                            v-model="form.candidate.expected_facilities"
                                                        />
                                                    <p class="text-danger" v-if="errors.expected_facilities">
                                                        {{ errors.expected_facilities[0] }}
                                                    </p>
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
                                                    ></b-form-radio-group>
                                                <p class="text-danger" v-if="errors.work_out_of_town">
                                                        {{ errors.work_out_of_town[0] }}
                                                    </p>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Would you like to be placed out of town?</label> 
                                                    <b-form-radio-group
                                                    v-model="
                                                        form.candidate.placed_out_of_town
                                                    "
                                                    :options="radioOptions"
                                                    ></b-form-radio-group>
                                                <p class="text-danger" v-if="errors.placed_out_of_town">
                                                        {{ errors.placed_out_of_town[0] }}
                                                    </p>
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
                                                        unchecked-value="No"
                                                    >
                                                        Yes
                                                    </b-form-checkbox>
                                                </div>
                                                <div v-if="form.candidate.work_accident == 'Yes'" class="form-group col-md-6">
                                                    <label>When</label> 
                                                    <date-picker
                                                        format="YYYY-MM-DD"
                                                        value-type="format"
                                                        type="date"
                                                        :default-value="new Date()"
                                                        :disabled-date="disabledAfterToday"
                                                        :class="{ 'border-danger': errors.date_of_accident }"
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
                                                        unchecked-value="No"
                                                    >
                                                        Yes
                                                    </b-form-checkbox>
                                                </div>
                                                    <div v-if="form.candidate.psychological_check == 'Yes'" class="form-group col-md-3">
                                                        <label>When</label> 
                                                        <date-picker
                                                            format="YYYY-MM-DD"
                                                            value-type="format"
                                                            type="date"
                                                            :default-value="new Date()"
                                                            :disabled-date="disabledAfterToday"
                                                            :class="{ 'border-danger': errors.date_of_check }"
                                                            v-model="form.candidate.date_of_check"
                                                        />
                                                    </div>
                                                    <div v-if="form.candidate.psychological_check == 'Yes'" class="form-group col-md-3">
                                                        <label>Company Name</label> 
                                                        <input
                                                                type="text"
                                                                class="form-control form-control"
                                                                :class="{
                                                                    'border-danger':
                                                                        errors[`check_place`]
                                                                }"
                                                                v-model="form.candidate.check_place"
                                                            />
                                                    </div>
                                                    <div v-if="form.candidate.psychological_check == 'Yes'" class="form-group col-md-3">
                                                        <label>Purpose</label> 
                                                        <input
                                                                type="text"
                                                                class="form-control form-control"
                                                                :class="{
                                                                    'border-danger':
                                                                        errors[`check_purpose`]
                                                                }"
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
                                                    <button
                                                        type="button"
                                                        @click="addRelative()"
                                                        class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
                                                        v-b-tooltip.hover
                                                        :title="$tc('add')"
                                                    >
                                                        <i class="mdi mdi-plus"></i>
                                                    </button>
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
                                                    <th width="70px">{{ $t("action") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in form.aquintances"
                                                    :key="index"
                                                    class="justify-content-between align-items-center"
                                                >
                                                    <td>
                                                        <v-select
                                                            v-model="row.relation"
                                                            label="text"
                                                            :reduce="text => text.value"
                                                            :options="relationOptions"
                                                        ></v-select>
                                                        <br />
                                                    </td>
                                                    <td>
                                                        <select-employee
                                                            @selectedEmployee="selectedEmployees(index, ...arguments)"
                                                            :data="employees.data"
                                                            :singleSelected="true"
                                                            v-model="row.employee_id"
                                                        ></select-employee>
                                                        <br />
                                                    </td>
                                                    <td class="text-cenxter">
                                                        <a
                                                            href="javascript:void(0)"
                                                            @click="removeRelative(index)"
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
                            </b-tab>
                        </b-tabs>
                    </div>
                </div>
            </div>
    </template>
<script>
import selectEmployee from "../../../../components/elements/custom/select-employee.vue";
import Vue from "vue";
import { Datetime } from "vue-datetime";
import moment from "moment";
import "vue-datetime/dist/vue-datetime.css";
import "vue-datetime/dist/vue-datetime.js";
import vSelect from "vue-select";
import { mapState, mapMutations, mapActions } from "vuex";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import {IMaskDirective} from 'vue-imask';

export default {
    props: ["candidateID"],
    data() {
        return {
            listAllInEmployee: [],
            lastSalary: "",
            mask: {
                amount: {
                    mask: Number,
                    scale: 2, 
                    signed: false,
                    thousandsSeparator: ".", 
                    padFractionalZeros: false, 
                    normalizeZeros: true,
                    radix: ".", 
                    mapToRadix: [","], 
                    max: 100000000
                },
            },
            onAccept (e) {
                const maskRef = e.detail;
                this.value = maskRef.value;
                console.log('accept', maskRef.value);
            },
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
            vehicleTypeOptions: [
                { text: this.$tc("Motorcycle"), value: "Motorcycle" },
                { text: this.$tc("Car"), value: "Car" }
            ],
            languageOptions: [
                { text: this.$tc("English"), value: "English" },
                { text: this.$tc("Mandarin"), value: "Mandarin" },
                { text: this.$tc("Indonesia"), value: "Indonesia" }
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
            emergencyStatus:[],
            value: '',
            status: 0,
            maritalValue: 0,
            msg: []
        };
    },
    created() {
        this.maskLastSalary();
        this.loadrecruitmentStepParameters();
        this.loadScores();
        this.loadCandidates();
        this.loadDepartments();
        this.loadReligions();
        this.loadEmployees();
        this.loadMaritalStatuses();
        this.loadCompanies();
        this.loadPayrollTypes();
        this.loadCities();
        this.loadPositions();
        this.addLanguage();
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
        ...mapState("employee", {
            employees: state => state.collectionList
        }),
        ...mapState("position", {
            positions: state => state.collectionList
        }),
        scoreValue() {
            // return this.form.recruitment_step.recruitment_step_parameter
            return this.form.recruitment_step.recruitment_step_parameter.map(
                item => item["score"]
            );
        },
    },
    watch:{
        email(value) {
            // binding this to the data value in the email input
            this.form.candidate.email = value;
            this.validateEmail(value);
        },        
    },
    methods: {
        maskLastSalary(){
            console.log("Terpanggilkah?",this.form.candidate.last_salary);
            this.value = this.form.candidate.last_salary;
        },
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
        ...mapActions("company", { loadCompanies: "loadList" }),
        ...mapActions("employee", { loadEmployees: "loadList" }),
        ...mapActions("position", { loadPositions: "loadList" }),
        ...mapActions("recruitment", ["updateCandidate"]), //PANGGIL ACTIONSs
        selectedEmployees(index, value) {
            this.form.aquintances[index].employee_id = value;
        },
        isNumber(e) {
            let char = String.fromCharCode(e.keyCode); // Get the character
            if (/^[0-9,/.]+$/.test(char)) return true;
            // Match with regex
            else e.preventDefault(); // If not match, don't add to input text
        },
        isLetter(e) {
            let char = String.fromCharCode(e.keyCode); // Get the character
            if (/^[A-Za-z- ]+$/.test(char)) return true;
            // Match with regex
            else e.preventDefault(); // If not match, don't add to input text
        },
        onComplete (e) {
                const maskRef = e.detail;
                this.form.candidate.last_salary = maskRef.unmaskedValue;
        },
        onCompleteExpect (e) {
                const maskRef = e.detail;
                // console.log('complete', maskRef.unmaskedValue);
                // console.log('dataForm',this.form);
                this.form.candidate.expected_salaries = maskRef.unmaskedValue;
                // console.log('expect_salary', this.form.expected_salaries);
        },
        emergencyChanged(value, index){
            console.log(this.form.parents[index].phone);
            if(this.form.parents[index].phone.length > 5){
                this.emergencyStatus[index] = true
                console.log("Status", this.emergencyStatus[index])
            }else{
                this.emergencyStatus[index] = false
            }
        },
        validateEmail(value) {
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
                this.msg["email"] = "";
            } else {
                this.msg["email"] = "Invalid Email Address";
            }
        },
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
        addEducation() {
            this.form.educations.push({
                school_name: null,
                major: null,
                city: null,
                entry_year: null,
                graduation_year: null,
                score: null
            });
        },
        removeEducation(index) {
            if (this.form.educations.length > 1) {
                this.form.educations.splice(index, 1);
            }
        },
        addTraining() {
            this.form.trainings.push({
                training_type: null,
                organizer: null,
                year: null
            });
        },
        removeTraining(index) {
            if (this.form.trainings.length > 1) {
                this.form.trainings.splice(index, 1);
            }
        },
        addJob() {
            this.form.job_experiences.push({
                company_name: null,
                address: null,
                phone: null,
                position: null,
                start_date: null,
                end_date: null
            });
        },
        removeJob(index) {
            if (this.form.job_experiences.length > 1) {
                this.form.job_experiences.splice(index, 1);
            }
        },
        addParent() {
            this.form.parents.push({
                tyoe: null,
                name: null,
                date_of_birth: null,
                gender: null,
                address: null,
                phone: null,
                contact_emergency: null,
            });
        },
        removeParent(index) {
            if (this.form.parents.length > 1) {
                this.form.parents.splice(index, 1);
            }
        },
        addSibling() {
            this.form.siblings.push({
                type: null,
                name: null,
                date_of_birth: null,
                gender: null,
                last_education: null,
                company_name: null,
                address: null,
                phone: null
            });
        },
        removeSibling(index) {
            if (this.form.siblings.length > 1) {
                this.form.siblings.splice(index, 1);
            }
        },
        addChildren() {
            this.form.childrens.push({
                name: null,
                date_of_birth: null,
                gender: null,
                last_education: null,
                company_name: null
            });
        },
        removeChildren(index) {
            if (this.form.childrens.length > 1) {
                this.form.childrens.splice(index, 1);
            }
        },
        addLanguage() {
            this.form.languages.push({
                language: null,
                speaking: null,
                writing: null,
                reading: null
            });
        },
        removeLanguage(index) {
            if (this.form.languages.length > 1) {
                this.form.languages.splice(index, 1);
            }
        },
        addRelative() {
            this.form.aquintances.push({
                relation: null,
                employee_id: null
            });
        },
        removeRelative(index) {
            if (this.form.aquintances.length > 1) {
                this.form.aquintances.splice(index, 1);
            }
        },
        addReference() {
            this.form.references.push({
                name: null,
                address: null,
                job: null,
                phone: null
            });
        },
        removeReference(index) {
            if (this.form.references.length > 1) {
                this.form.references.splice(index, 1);
            }
        },
        addEmergency() {
            this.form.emergencies.push({
                name: null,
                address: null,
                phone: null
            });
        },
        removeEmergency(index) {
            if (this.form.emergencies.length > 1) {
                this.form.emergencies.splice(index, 1);
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
        disabledAfterToday(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return date > today;
        },
        disabledFromEntryYear(date) {
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return date > today;
        },
        sumScore() {
            let scoreList = Object.values(this.scoreValue);
            this.form.total_score = scoreList.reduce(
                (total, num) => total + num,
                0
            );

            // console.log("test");

            // this.changeAccepted();

            return scoreList.reduce((total, num) => total + num, 0);
        }
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        // this.CLEAR_FORM();
    },
    components: {
        "v-select": vSelect,
        DatePicker,
        selectEmployee,
    },
    directives: {
        imask: IMaskDirective
    }
};
</script>
