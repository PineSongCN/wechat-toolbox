import Layout from '@/layout';

const D = {
    path: '/',
    component: Layout,
    redirect: '/home',
    sort: 100,
    children: [
        {
            path: 'home',
            component: () => import('@/views/home/index'),
            name: 'Home',
            meta: { title: '首页', icon: 'dashboard', affix: true }
        }
    ]
}

export default D;
