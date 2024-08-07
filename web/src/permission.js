import router from '@/router';
import store from '@/store';
import NProgress from 'nprogress'; // progress bar
import 'nprogress/nprogress.css'; // progress bar style
import getPageTitle from '@/utils/get-page-title';
import {
    getStorage,
    setStorage,
} from '@/utils/storage';
// uuid
NProgress.configure({ showSpinner: false }); // NProgress Configuration

const whiteList = [];

router.beforeEach(async (to, from, next) => {
    // start progress bar
    NProgress.start();
    document.title = getPageTitle(to.meta.title);
    // if(!store.getters.clientCode) {
    //     console.log(store.getters)
    //     console.log(store.user)
    //     console.log(getStorage('clientCode'))
    // }
    next();
    NProgress.done();
});

router.afterEach(() => {
    // finish progress bar
    NProgress.done();
});
