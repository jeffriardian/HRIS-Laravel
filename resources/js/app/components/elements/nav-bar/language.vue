<template>
    <b-dropdown variant="black" right toggle-class="header-item">
        <template v-slot:button-content>
            <img class="mr-2" :src="`/assets/images/flags/${$i18n.locale}.svg`" alt="Header Language" height="16" />
            <span class="text-capitalize align-middle">{{ ($i18n.locale == 'en') ? 'English' : 'Indonesia' }}</span>
            <span class="mdi mdi-chevron-down"></span>
        </template>
        <b-dropdown-item v-for="lang in languages" :key="lang" @click.prevent="changeLanguage(lang)" href="javascript: void(0);">
            <img :src="`/assets/images/flags/${lang}.svg`" alt="user-image" class="mr-1" height="12" />
            <span class="text-capitalize align-middle">{{ (lang == 'en') ? 'English' : 'Indonesia' }}</span>
        </b-dropdown-item>
    </b-dropdown>
</template>
<script>
export default {
    data(){
        return{
            languages:[
                'en','id'
            ]
        }
    },
    methods: {
        //KETIKA TOMBOL LOGOUT DITEKAN, FUNGSI INI DIJALANKAN
        changeLanguage(lang) {
            this.$i18n.locale = lang;
        }
    }
}
</script>