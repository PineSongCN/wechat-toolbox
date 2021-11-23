const uni = {
    setStorageSync(key, value) {
        localStorage.setItem(key, JSON.stringify(value));
        return true;
    },
    getStorageSync(key) {
        let value = localStorage.getItem(key);
        value = value === null ? null : JSON.parse(value);
        return value;
    },
    removeStorageSync(key) {
        localStorage.removeItem(key);
        return true;
    },
    clearStorageSync() {
        localStorage.clear();
        return true;
    }
};

/**
 * 存储Storage
 */
export const setStorage = (...params) => {
    let key, value, expireTime;
    if (typeof params[0] === 'string') {
        key = params[0];
        value = params[1];
        expireTime = params[2];
    } else {
        key = params[0].key;
        value = params[0].value;
        expireTime = params[0].expireTime;
    }
    if (!expireTime) {
        expireTime =
            Date.now() + (expireTime === 0 ? 777600000000 : 7776000000);
    } else if (expireTime && expireTime < 1000000000000) {
        expireTime = Date.now() + expireTime * 1000;
    }
    if (typeof value === 'undefined') {
        value = null;
    }
    value = {
        value,
        expireTime
    };
    uni.setStorageSync(key, value);
};

/**
 * 获取storage
 */
export const getStorage = (...params) => {
    let key, value, def;

    try {
        if (typeof params[0] === 'string') {
            key = params[0];
            def = params[1] || null;
        } else {
            key = params[0].key;
            def = params[0].def;
        }
        value = uni.getStorageSync(key);
        if (value && value.expireTime && value.expireTime < Date.now()) {
            uni.removeStorageSync(key);
            value = null;
        }
        if (value === null && def) {
            value = {
                value: def
            };
            setStorage({ key, value: def });
        }
        return value && typeof value.value !== 'undefined'
            ? value.value
            : value;
    } catch (e) {
        // console.log('getStorage', e);
        return def;
    }
};

/**
 * 删除storage
 */
export const removeStorage = (...params) => {
    let key;
    if (typeof params[0] === 'string') {
        key = params[0];
    } else {
        key = params[0].key;
    }
    uni.removeStorageSync(key);
};

/**
 * 清空storage
 */
export const clearStorage = () => {
    uni.clearStorageSync();
};

export const vuexStorage = ({ item = '', time = 1000, func = () => {} }) => {
    if (item !== '') {
        const interval = setInterval(() => {
            const value = getStorage(item);
            func(value, interval);
        }, time);
    }
};
