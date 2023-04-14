<template>
    <Layout>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-8 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-soft-primary">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    <p>Sign in to continue to Application.</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="/assets/images/profile-img.png" alt class="img-fluid" />
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div>
                            <router-link tag="a" to="/">
                                <div class="avatar-md profile-user-wid mb-4">
                                    <span class="avatar-title rounded-circle bg-light">
                                    <img src="/assets/images/logo.svg" alt height="34" />
                                    </span>
                                </div>
                            </router-link>
                        </div>
                        <div class="alert alert-danger" v-if="errors.invalid">{{ errors.invalid }}</div>
                        <b-form class="p-2" @submit.prevent="postLogin">
                            <b-form-group id="input-group-1" label="Username" label-for="input-1" :class="{'has-error': errors.name}">
                                <b-form-input id="input-1" type="text" v-model="form.name" placeholder="Username" :class="{ 'border-danger': errors.name }"/>
                                <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
                            </b-form-group>
                            <b-form-group id="input-group-2" label="Password" label-for="input-2" :class="{'has-error': errors.password}">
                                <b-form-input
                                    id="input-2"
                                    type="password"
                                    placeholder="Password"
                                    v-model="form.password"
                                    :class="{ 'border-danger': errors.password }" 
                                    />
                            </b-form-group>
                            <div class="form-group">
                                <b-form-checkbox v-model="form.remember_me" value=1 unchecked-value=0>
                                    {{ $t('remember') }}
                                </b-form-checkbox>
                            </div>
                            <div class="mt-3">
                                <b-button type="submit" variant="primary" class="btn-block" @click.prevent="postLogin">Log In</b-button>
                            </div>
                            <div class="mt-4 text-center">
                                <router-link tag="a" to="/forgot-password" class="text-muted">
                                    <i class="mdi mdi-lock mr-1"></i> Forgot your password?
                                </router-link>
                            </div>
                        </b-form>
                    </div>
                </div>
                <div class="mt-5 text-center">
                    <p>&copy; {{ new Date().getFullYear() }}. Sarana Makin Mulya All Rights Reserved</p>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import { mapActions, mapMutations, mapGetters, mapState } from 'vuex';
import Layout from '../../../../components/layouts/auth';
export default {
    data() {
        return {
            form: {
                name: '',
                password: '',
                remember_me: false
            }
        }
    },
    //SEBELUM COMPONENT DI-RENDER
    created() {
        //KITA MELAKUKAN PENGECEKAN JIKA SUDAH LOGIN DIMANA VALUE isAuth BERNILAI TRUE
        if (this.isAuth) {
            //MAKA DI-DIRECT KE ROUTE DENGAN NAME DASHBOARD
            this.$router.push({ name: 'dashboard' })
        }
    },
    components: { Layout },
    computed: {
        ...mapGetters(['isAuth']), //MENGAMBIL GETTERS isAuth DARI VUEX
      	...mapState(['errors'])
    },
    methods: {
        ...mapActions('auth', ['submit']), //MENGISIASI FUNGSI submit() DARI VUEX AGAR DAPAT DIGUNAKAN PADA COMPONENT TERKAIT. submit() BERASAL DARI ACTION PADA FOLDER STORES/auth.js
        ...mapActions('auth', ['getUserLogin']),
        ...mapMutations(['CLEAR_ERRORS']),
      
      	//KETIKA TOMBOL LOGIN DITEKAN, MAKA AKAN MEMINCU METHODS postLogin()
        postLogin() {
            //DIMANA TOMBOL INI AKAN MENJALANKAN FUNGSI submit() DENGAN MENGIRIMKAN DATA YANG DIBUTUHKAN
            this.submit(this.form).then(() => {
                //KEMUDIAN DI CEK VALUE DARI isAuth
                //APABILA BERNILAI TRUE
                if (this.isAuth) {
                    this.CLEAR_ERRORS()
                    //MAKA AKAN DI-DIRECT KE ROUTE DENGAN NAME home
                    this.$router.push({ name: 'dashboard' })
                    this.$router.go()
                }
            })
        }
    },
    destroyed() {
        this.getUserLogin()
    }
}
</script>