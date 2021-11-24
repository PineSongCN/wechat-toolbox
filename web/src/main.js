import Vue from 'vue';

import 'normalize.css/normalize.css';
// import Element from 'element-ui';
import '@/styles/element-variables.scss';

import '@/styles/index.scss';

import App from './App';
import store from './store';
import router from './router';

import './icons';
import './permission';

import * as filters from './filters';

import clipboard from '@/directive/focus.js';

// import Vant from 'vant';
// import 'vant/lib/index.css';
// import '@vant/touch-emulator';

// Vue.use(Vant);

// Vue.use(Element, {
//     size: 'mini'
// });
Vue.use(clipboard);

Object.keys(filters).forEach((key) => {
    Vue.filter(key, filters[key]);
});

Vue.config.productionTip = false;

import { Button, Loading, MessageBox, Message, Notification } from 'element-ui';
Vue.prototype.$ELEMENT = { size: 'mini', zIndex: 1000 };

Vue.use(Button);
Vue.use(Loading.directive);
Vue.prototype.$loading = Loading.service;
Vue.prototype.$msgbox = MessageBox;
Vue.prototype.$alert = MessageBox.alert;
Vue.prototype.$confirm = MessageBox.confirm;
Vue.prototype.$prompt = MessageBox.prompt;
Vue.prototype.$notify = Notification;
Vue.prototype.$message = Message;

import { Toast, Field, Loading as VantLoading, Notify } from 'vant';

Vue.use(Toast);
Vue.use(Field);
Vue.use(VantLoading);
Vue.use(Notify);

new Vue({
    el: '#app',
    router,
    store,
    render: (h) => h(App)
});
