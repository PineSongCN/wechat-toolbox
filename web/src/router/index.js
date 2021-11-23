import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

import Layout from '@/layout';

const modulesFiles = require.context('./modules', true, /\.js$/);
const modules = modulesFiles.keys().reduce((modules, modulePath) => {
    // const moduleName = modulePath.replace(/^\.\/(.*)\.\w+$/, '$1');
    const value = modulesFiles(modulePath);
    if (value && value.default) {
        modules.push(value.default);
    }
    return modules;
}, []);
modules.sort((a, b) => {
    const s1 = a.sort || 0;
    const s2 = b.sort || 0;
    return s2 - s1;
});
if (process.env.NODE_ENV === 'production') {
    modules.filter((module) => {
        if (module.children) {
            module.children = module.children.filter((child) => {
                return !child.development;
            });
        }
        return !module.development;
    });
}
export const constantRoutes = [
    {
        path: '/redirect',
        component: Layout,
        hidden: true,
        children: [
            {
                path: '/redirect/:path(.*)',
                component: () => import('@/views/redirect/index')
            }
        ]
    },
    ...modules,
    { path: '*', redirect: '/', hidden: true }
];

const createRouter = () =>
    new Router({
        mode: 'history',
        scrollBehavior: () => ({ y: 0 }),
        routes: constantRoutes
    });

const router = createRouter();

export function resetRouter() {
    const newRouter = createRouter();
    router.matcher = newRouter.matcher; // reset router
}

export const asyncRoutes = [];

export default router;
