import request from '@/api/_request';

export function write(data = {}) {
    return request({
        baseUrl: 'base',
        url: '/message-board',
        method: 'POST',
        data
    });
}

export function read(data = {}) {
    return request({
        baseUrl: 'base',
        url: '/message-board',
        method: 'GET',
        data
    });
}
