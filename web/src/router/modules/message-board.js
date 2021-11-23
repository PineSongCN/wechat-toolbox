import Layout from '@/layout';

const D = {
    path: '/message-board',
    component: Layout,
    sort: 100,
    children: [
        {
            path: '',
            component: () => import('@/views/message-board/index'),
            name: 'MessageBoard',
            meta: { title: '念念不忘 必有回响' }
        },
        {
            path: 'write',
            component: () => import('@/views/message-board/write'),
            name: 'MessageBoardWrite',
            meta: { title: '写留言' }
        },
        {
            path: 'read',
            component: () => import('@/views/message-board/read'),
            name: 'MessageBoardRead',
            meta: { title: '看留言' }
        }
    ]
}

export default D;
