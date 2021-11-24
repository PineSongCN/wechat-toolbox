import { v1 as uuidV1 } from 'uuid';
import { login, logout, getInfo } from '@/api/user';
import {
    getStorage,
    setStorage,
    removeStorage,
    clearStorage
} from '@/utils/storage';
import { validEmpty } from '@/utils/validate';

const map = [
    {
        key: 'token',
        def: getStorage('token', '')
    },
    {
        key: 'clientCode',
        def: getStorage('clientCode', uuidV1())
    },
    {
        key: 'userId',
        def: getStorage('userId', '')
    },
    {
        key: 'userName',
        def: getStorage('userName', '')
    },
    {
        key: 'userInfo',
        def: getStorage('userInfo', {})
    },
];

const state = {};
const mutations = {};

for (let i = map.length - 1; i >= 0; i--) {
    let { key, def, type = 1 } = map[i];
    const setKey = `SET_${key.toUpperCase()}`;
    const removeKey = `REMOVE_${key.toUpperCase()}`;
    if (type === 2) {
        state[key] = def;

        mutations[setKey] = (state, value) => {
            value =
                typeof value === 'object' && value !== null
                    ? JSON.parse(JSON.stringify(value))
                    : value;
            state[key] = value;
        };
    } else {
        state[key] = getStorage({
            key,
            def
        });
        setStorage({
            key,
            value: def,
            type
        });

        mutations[setKey] = (state, value) => {
            value =
                typeof value === 'object' && value !== null
                    ? JSON.parse(JSON.stringify(value))
                    : value;
            state[key] = value;
            setStorage({
                key,
                value,
                type
            });
        };

        mutations[removeKey] = (state, value) => {
            removeStorage({
                key,
                type
            });
        };
    }
}

const actions = {
    async login({ commit, dispatch }, userInfo) {
        try {
            const { username, password } = userInfo;
            const response = await login({
                username: username.trim(),
                password: password
            });
            console.log(response)
            const { token } = response;
            commit('SET_TOKEN', token);

            userInfo = await dispatch('getInfo');
            return userInfo;
        } catch (e) {
            console.log('store:user:login:e', e);
            throw e;
        }
    },

    async getInfo({ commit, state }) {
        try {
            const response = await getInfo(state.token);
            const { userId, userName } = response;
            commit('SET_USERINFO', response);
            commit('SET_USERID', userId);
            commit('SET_USERNAME', userName);
            return response;
        } catch (e) {
            console.log('store:user:getInfo:e', e);
            throw e;
        }
    },

    async logout({ commit, state, dispatch }) {
        try {
            commit('SET_TOKEN', null);
            commit('SET_USERINFO', null);
            commit('SET_USERID', null);
            commit('SET_USERNAME', null);
            resetRouter();
            // dispatch('tagsView/delAllViews', null, { root: true });
            return true;
        } catch (e) {
            console.log('store:user:logout:e', e);
            throw e;
        }
    },

    async resetToken({ dispatch }) {
        try {
            await dispatch('logout');
            return true;
        } catch (e) {
            console.log('store:user:resetToken:e', e);
            throw e;
        }
    }
};
export default {
    namespaced: true,
    state,
    mutations,
    actions
};
