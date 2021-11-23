export const validate = (formMap, rulesBox = {}, $el = {}) => {
    const validator = (rule, value, callback) => {
        const k = rule.name;
        const v = formMap.find((item) => item.key === k);
        try {
            if (v.rule !== true && rulesBox[v.rule]) {
                const VALID = rulesBox[v.rule](value);
                if (VALID === true) {
                    callback();
                } else {
                    callback(new Error(VALID));
                }
            } else if (v.rule && typeof v.rule === 'function') {
                const VALID = v.rule(value, v, $el);
                if (VALID) {
                    callback(new Error(VALID));
                } else {
                    callback();
                }
            } else if (v.rule === true) {
                if (validEmpty(value)) {
                    let label = '请输入';
                    if (
                        [
                            'select',
                            'date',
                            'datetime',
                            'Date',
                            'DateTime',
                            'DateRange',
                            'DateTimeRange',
                            'Month',
                            'radio',
                            'cascader',
                            'checkBox',
                            'image'
                        ].includes(v.type)
                    ) {
                        label = '请选择';
                    }
                    const VALID = `${label}${v.label}`;
                    callback(new Error(VALID));
                } else {
                    callback();
                }
            } else {
                callback();
            }
        } catch (e) {
            console.log(e);
            callback(new Error(e));
        }
    };
    const rulesForm = {};
    for (const i in formMap) {
        const v = formMap[i];
        if (v.rule) {
            const temp = [
                {
                    name: v.key,
                    required: true,
                    trigger: 'blur',
                    validator
                }
            ];
            if (
                ['number', 'date', 'datetime', 'Date', 'DateTime'].includes(
                    v.type
                )
            ) {
                temp[0].type = 'number';
            }
            if (v.ruleType) {
                temp[0].type = v.ruleType;
            }
            rulesForm[v.key] = temp;
        } else {
            if (!v.rulePassed) {
                const temp = [
                    {
                        name: v.key,
                        required: false
                    }
                ];
                if (
                    ['number', 'date', 'datetime', 'Date', 'DateTime'].includes(
                        v.type
                    )
                ) {
                    temp[0].type = 'number';
                }
                if (v.ruleType) {
                    temp[0].type = v.ruleType;
                }
                if (!temp[0].type) {
                    // temp[0].type = 'any';
                }
                rulesForm[v.key] = temp;
            }
        }
    }
    return rulesForm;
};

export const validEmpty = (value, type = false) => {
    if (type) {
        if (validEmpty(value)) {
            return true;
        }
        for (const i in value) {
            const value2 = value[i];
            if (
                typeof value2 === 'undefined' ||
                value2 === null ||
                value2 === '' ||
                value2 == 'null' ||
                value2 == 'undefined'
            ) {
                return true;
            }
        }
        return false;
    } else {
        if (value instanceof Array && value.length == 0) {
            return true;
        } else if (value instanceof Object && JSON.stringify(value) === '{}') {
            return true;
        } else if (
            typeof value === 'undefined' ||
            value === null ||
            value === '' ||
            value == 'null' ||
            value == 'undefined'
        ) {
            return true;
        } else {
            return false;
        }
    }
};

/**
 * @param {string} path
 * @returns {Boolean}
 */
export function isExternal(path) {
    return /^(https?:|mailto:|tel:)/.test(path);
}

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function validUsername(str) {
    const valid_map = ['admin', 'editor'];
    return valid_map.indexOf(str.trim()) >= 0;
}

/**
 * @param {string} url
 * @returns {Boolean}
 */
export function validURL(url) {
    const reg =
        /^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$/;
    return reg.test(url);
}

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function validLowerCase(str) {
    const reg = /^[a-z]+$/;
    return reg.test(str);
}

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function validUpperCase(str) {
    const reg = /^[A-Z]+$/;
    return reg.test(str);
}

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function validAlphabets(str) {
    const reg = /^[A-Za-z]+$/;
    return reg.test(str);
}

/**
 * @param {string} email
 * @returns {Boolean}
 */
export function validEmail(email) {
    const reg =
        /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return reg.test(email);
}

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function isString(str) {
    if (typeof str === 'string' || str instanceof String) {
        return true;
    }
    return false;
}

/**
 * @param {Array} arg
 * @returns {Boolean}
 */
export function isArray(arg) {
    if (typeof Array.isArray === 'undefined') {
        return Object.prototype.toString.call(arg) === '[object Array]';
    }
    return Array.isArray(arg);
}
