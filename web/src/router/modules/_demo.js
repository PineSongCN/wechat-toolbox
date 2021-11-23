const Demo = {
    path: '/demo',
    component: () => import('@/views/home/index'),
    redirect: 'noRedirect',
    sort: 0,
    meta: {
        title: 'demo',
        icon: 'el-icon-help'
    },
    hidden: true,
    children: []
};

export default Demo;


// import Layout from '@/layout';
// const D = {
//     path: '/demo',
//     component: Layout,
//     sort: 80,
//     meta: {
//         title: 'demo',
//         icon: 'el-icon-help'
//     },
//     children: [
//         {
//             path: 'index',
//             component: () => import('@/views/home/index.vue'),
//             meta: {
//                 title: 'demo'
//             }
//         }
//     ]
// };

// export default D;
