import request from '@/api/_request';
import store from '@/store';

export function write(data = {}) {
    data.client_code = store.getters.clientCode;
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
