import axios from 'axios';
import { MessageBox, Message } from 'element-ui';
import store from '@/store';
import { getToken } from '@/utils/auth';

// create an axios instance
const service = axios.create({
    baseURL: '',
    // withCredentials: true, // send cookies when cross-domain requests
    timeout: 600000
});

// request interceptor
service.interceptors.request.use(
    (config) => {
        if (store.getters.token) {
            config.headers['Authorization'] = store.getters.token; //getToken();
        }
        return config;
    },
    (error) => {
        console.log('request:e', error);
        return Promise.reject(error);
    }
);

service.interceptors.response.use(
    (response) => {
        const res = response.data;

        if (res.code !== 200 && res.code !== 0) {
            Message({
                message: res.msg || '系统错误',
                type: 'error',
                duration: 5 * 1000
            });

            if (res.code === 401) {
                MessageBox.confirm('登录失效，是否注销并前往登录页面', '', {
                    confirmButtonText: '去登录',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(async () => {
                    // await store.dispatch('user/logout');
                    await store.dispatch('user/resetToken');
                    location.reload();
                });
            } else if (res.code === 403) {
                MessageBox.confirm(
                    res.msg || '没有权限，请联系管理员授权',
                    '',
                    {
                        confirmButtonText: '去首页',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }
                ).then(async () => {
                    location.href = '/';
                });
            }
            return Promise.reject(new Error(res.msg || res));
        } else {
            return res.data;
        }
    },
    (error) => {
        let message = error.message || error.msg || '系统错误';
        try {
            if (error && error.response && error.response.data) {
                message =
                    error.response.data.message ||
                    error.response.data.msg ||
                    message;
            }
        } catch (e) {
            console.log('error:e', e);
        }
        try {
            console.log('error:e', error);
            console.log('error:config', error.config);
            console.log('error:response', error.response);
            console.log('error:request', error.request);
        } catch (e) {}
        Message({
            message: message,
            type: 'error',
            duration: 5 * 1000
        });
        return Promise.reject(error);
    }
);

export default service;
