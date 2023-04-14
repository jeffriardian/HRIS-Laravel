<template>
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-primary-800">
                    <h6 class="mb-0 font-weight-semibold">{{ $t('appointment',{message:$tc('assign'),subject:$tc('role'),object:$tc('user')}) }}</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">{{ $tc('role') }}</label>
                        <select class="form-control" v-model="role_user.role">
                            <option value="">{{ $t('choose attribute',{attribute:$tc('role')}) }}</option>
                            <option v-for="(row, index) in roles" :value="row.name" :key="index">{{ row.name }}</option>
                        </select>
                        <p class="text-danger" v-if="errors.role_id">{{ errors.role_id[0] }}</p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ $tc('user') }}</label>
                        <select class="form-control" v-model="role_user.user_id">
                            <option value="">{{ $t('choose attribute',{attribute:$tc('user')}) }}</option>
                            <option v-for="(row, index) in users" :value="row.id" :key="index">{{ row.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center">
                    <button class="btn btn-danger btn-sm" @click="setRole">
                    <i class="feather icon-user-check mr-2"></i>{{ $t('set attribute',{attribute:$tc('role')}) }}
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header bg-primary-600">
                    <h6 class="mb-0 font-weight-semibold">{{ $t('appointment',{message:'Set',subject:$tc('permission'),object:$tc('role')}) }}</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">{{ $tc('role') }}</label>
                        <select class="form-control" v-model="role_selected">
                            <option value="">{{ $t('choose attribute',{attribute:$tc('role')}) }}</option>
                            <option v-for="(row, index) in roles" :value="row.id" :key="index">{{ row.name }}</option>
                        </select>
                        <p class="text-danger" v-if="errors.role_id">{{ errors.role_id[0] }}</p>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success btn-sm" @click="checkPermission">
                            <div class="d-flex align-items-center ">
                                <b-spinner v-if="loading" small class="mr-2"></b-spinner>
                                <i v-else class="feather icon-search mr-2"></i> {{ $t('check') }}
                            </div>
                        </button>
                    </div>
                    <div class="form-group">
                        <ul class="nav nav-tabs nav-tabs-highlight justify-content-end">
                            <li class="nav-item"><a href="#permission-assignment-tab" class="nav-link active" data-toggle="tab">{{ $tc('permission',2) }} <i class="dripicons-user-group ml-2"></i></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="permission-assignment-tab" style="height: 240px; overflow-y: scroll;">
                                <template v-for="(row, index) in permissions">
                                    <b-form-checkbox :key="index"
                                        :value="row.name"
                                        :checked="role_permission.findIndex(x => x.name == row.name) != -1"
                                        @click="addPermission(row.name)" >
                                        {{ row.name }}
                                    </b-form-checkbox>
                                    <br :key="'enter' + index" v-if="(index+1) %4 == 0">
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-primary btn-sm" @click="setPermission">
                        <i class="dripicons-user-group mr-2"></i>{{ $t('set attribute',{attribute:$tc('permission')}) }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions, mapState, mapMutations } from 'vuex'

export default {
    name: 'SetPermission',
    data() {
        return {
            role_user: {
                role: '',
                user_id: ''
            },
            role_selected: '',
            new_permission: [],
            loading: false
        }
    },
    created() {
        //KETIKA COMPONENT DI-LOAD, MAKA KITA AKAN ME-REQUEST 3 DATA BERIKUT
        this.getRoles() //DATA ROLES
        this.getAllPermission() //DATA PERMISSIONS
        this.getUserLists() //DATA USERS
    },
    computed: {
        ...mapState(['errors']), //ME-LOAD STATE ERRORS
        ...mapState('user', {
            users: state => state.collectionList, //ME-LOAD STATE USERS
            roles: state => state.roles, //ME-LOAD STATE ROLES
            permissions: state => state.permissions, //ME-LOAD STATE PERMISSION

            //STATE YANG MENAMPUNG PERMISSION YG TELAH DI-ASSIGN
            role_permission: state => state.role_permission
        })
    },
    methods: {
        //LOAD SEMUA FUNGSI YANG ADA DI MODULE STORE USER
        ...mapActions('user', [
            'getUserLists',
            'getRoles',
            'getAllPermission',
            'getRolePermission',
            'setRolePermission',
            'setRoleUser'
        ]),
        //LOAD MUTATIONS DARI STORE USER
        ...mapMutations('user', ['CLEAR_ROLE_PERMISSION']),
        //FUNGSI INI AKAN BERJALAN KETIKA TOMBOL SET ROLE DIKLIK
        setRole() {
            //KIRIM PERMINTAAN KE BACKEND
            this.setRoleUser(this.role_user).then(() => {
                this.$message({
                    type: 'success',
                    message: this.$t('present perfect',{object:this.$tc('role'),message:this.$tc('add',2)})
                });
                setTimeout(() => {
                    //BEBERAPA DETIK KEMUDIAN, SET DEFAULT ROLE USER
                    this.role_user = {
                        role: '',
                        user_id: ''
                    }
                }, 1000)
            })
        },
        //KETIKA LIST PERMISSION DI CENTANG, MAKA FUNGSI INI BERJALAN
        addPermission(name) {
            //DICEK KE NEW_PERMISSION BERDASARKAN NAME
            let index = this.new_permission.findIndex(x => x == name)
            //APABIL TIDAK TERSEDIA, INDEXNYA -1
            if (index == -1) {
                //MAKA TAMBAHKAN KE LIST
                this.new_permission.push(name)
            } else {
                //JIKA SUDAH ADA, MAKA HAPUS DARI LIST
                this.new_permission.splice(index, 1)
            }
        },
        //KETIKA TOMBOL CHECK DITEKAN, MAKA FUNGSI INI BERJALAN
        //FUNGSI INI UNTUK MENGAMBIL LIST PERMISSION YANG TELAH DI ASSIGN
        //KE DALAM ROLE YANG DIPILIH
        checkPermission() {
            this.loading = true //AKTIFKAN LOADING TOMBOL
            //KIRIM PERMINTAAN KE BACKEND
            this.getRolePermission(this.role_selected).then(() => {
                //APABILA BERHASIL, MATIKAN LOADING
                this.loading = false
                //PERMISSION YANG TELAH DIASSIGN AKAN DI MERGE KE NEW_PERMISSION
                this.new_permission = this.role_permission
            })
        },
        //FUNGSI INI BERJALAN KETIKA TOMBOL SET PERMISSION DITEKAN
        setPermission() {
            //KIRIM PERMINTAAN KE SERVER
            this.setRolePermission({
                role_id: this.role_selected,
                permissions: this.new_permission
            }).then((res) => {
                //APABIL BERHASIL
                if (res.status == 'success') {
                    this.$message({
                        type: 'success',
                        message: this.$t('present perfect',{object:this.$tc('permission'),message:this.$tc('assign',2)})
                    });
                    setTimeout(() => {
                        //BEBERAPA DETIK KEMUDIAN, KEMBALIKAN KE DEFAULT
                        this.role_selected = ''
                        this.new_permission = []
                        this.loading = false
                        this.CLEAR_ROLE_PERMISSION()
                    }, 1000)
                }
            })
        }
    }
}
</script>