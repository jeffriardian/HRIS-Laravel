import Vue from 'vue'
import VueI18n from 'vue-i18n'

import en from '../../locales/en.json'
import id from '../../locales/id.json'

const options = {
    locale: 'en',
    messages : {
        en:en,
        id:id
    },
}

Vue.use(VueI18n)

export default new VueI18n(options)