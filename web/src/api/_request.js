import env from '@/api/_env.js';
import r from '@/utils/request.js';

const Request = async ({
    baseUrl = 'base',
    url,
    method = 'POST',
    data = {},
    params = null,
    _url = false,
    headers = null,
    transformRequest = null
}) => {
    url = env[baseUrl] + url;
    if (_url) {
        return url;
    }
    if (method.toUpperCase() === 'GET' && params === null) {
        params = data;
    }
    const confirmData = {
        url,
        method,
        data,
        params: params || {}
    };
    if(headers) {
        confirmData.headers = headers;
    }
    if(transformRequest) {
        confirmData.transformRequest = transformRequest;
    }
    const res = await r(confirmData);
    return res;
};
export default Request;
