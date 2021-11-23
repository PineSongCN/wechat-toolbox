import request from '@/api/_request';

export function login(data = {}) {
    return request({
        baseUrl: 'base',
        url: '/userLogin',
        method: 'POST',
        data
    });
}

export function getInfo(data = {}) {
    return request({
        baseUrl: 'base',
        url: '/getUserInfo',
        method: 'GET',
        data
    });
}
