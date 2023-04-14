<template>
  <div>
    <b-dropdown right variant="black" toggle-class="header-item">
      <template v-slot:button-content>
        <img
          class="rounded-circle header-profile-user"
          src="/assets/images/user.png"
          alt="Header Avatar"
        />
        <span class="text-capitalize d-none d-xl-inline-block ml-1">{{ currentUser.name }}</span>
        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
      </template>
      <a class="dropdown-item" href="/contacts/profile">
        <i class="bx bx-user font-size-16 align-middle mr-1"></i> Profile
      </a>
      <a class="dropdown-item d-block" href="javascript: void(0);">
        <i class="bx bx-wrench font-size-16 align-middle mr-1"></i> Settings
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item text-danger" href="javascript: void(0);" @click="logout">
        <i class="bx bx-power-off font-size-16 align-middle mr-1 text-danger"></i> Logout
      </a>
    </b-dropdown>
  </div>
</template>
<script>
import { mapActions } from "vuex";
export default {
  computed: {
    currentUser: {
      cache: false,
      get() {
        return this.$store.state.utilities.auth.authenticated;
      },
    },
  },
  methods: {
    ...mapActions("notification", ["disconnect"]),
    //KETIKA TOMBOL LOGOUT DITEKAN, FUNGSI INI DIJALANKAN
    logout() {
      return new Promise((resolve, reject) => {
        localStorage.removeItem("token"); //MENGHAPUS TOKEN DARI LOCALSTORAGE
        resolve();
      }).then(() => {
        //MEMPERBAHARUI STATE TOKEN
        this.$store.state.token = localStorage.getItem("token");
        this.$router.push("/login"); //REDIRECT KE PAGE LOGIN
        this.$router.go(); //REDIRECT KE PAGE LOGIN
        this.disconnect();
      });
    },
  },
};
</script>