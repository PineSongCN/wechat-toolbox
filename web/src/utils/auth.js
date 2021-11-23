import { getStorage, setStorage, removeStorage } from '@/utils/storage';

export function getToken() {
    return getStorage('token');
}

export function setToken(token) {
    return setStorage('token', token);
}

export function removeToken() {
    return removeStorage('token');
}
