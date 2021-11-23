import Vue from 'vue';

import 'normalize.css/normalize.css';
import Element from 'element-ui';
import './styles/element-variables.scss';

import '@/styles/index.scss';

import App from './App';
import store from './store';
import router from './router';

import './icons';
import './permission'; 

import * as filters from './filters';

import clipboard from '@/directive/focus.js';

import Vant from 'vant';
import 'vant/lib/index.css';
import '@vant/touch-emulator';

Vue.use(Vant);

Vue.use(Element, {
    size: 'mini'
});
Vue.use(clipboard);

Object.keys(filters).forEach((key) => {
    Vue.filter(key, filters[key]);
});

Vue.config.productionTip = false;

new Vue({
    el: '#app',
    router,
    store,
    render: (h) => h(App)
});
