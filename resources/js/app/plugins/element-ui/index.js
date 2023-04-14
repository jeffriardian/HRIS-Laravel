import Vue from 'vue'
import ElementUi from 'element-ui'
import Lang from 'element-ui/lib/locale/lang/en';
import Locale from 'element-ui/lib/locale';

import 'element-ui/lib/theme-chalk/index.css'

Locale.use(Lang);

Vue.use(ElementUi)

export default ElementUi