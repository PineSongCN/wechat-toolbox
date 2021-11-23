import request from '@/api/_request';

//用户效验检查
export function ssoMember(data = {}, isFile) {
    let headers = null;
    let transformRequest = null;
    console.log('data', data);
    console.log('data.file', data.file);
    if (isFile) {
        headers = {
            'Content-Type': 'multipart/form-data'
        };
    } else {
        headers = {
            'Content-Type': 'application/x-www-form-urlencoded'
        };
        transformRequest = [
            function (data) {
                let ret = '';
                for (let it in data) {
                    ret +=
                        encodeURIComponent(it) +
                        '=' +
                        encodeURIComponent(data[it]) +
                        '&';
                }
                ret = ret.substring(0, ret.lastIndexOf('&'));
                return ret;
            }
        ];
    }
    return request({
        baseUrl: 'base',
        url: '/sso/member',
        method: 'POST',
        data,
        headers,
        transformRequest
    });
}

//模板文件下载
export function tplDownload(data = {}) {
    const { filename } = data;
    return request({
        baseUrl: 'base',
        url: `/common/tplDownload/${filename}`,
        method: 'GET',
        data,
        _url: true
    });
}

//模板文件类型列表
export function tplFile(data = {}) {
    return request({
        baseUrl: 'base',
        url: '/common/tplFile/list',
        method: 'GET',
        data
    });
}

//模板文件上传解析
export function tplUpload(data = new FormData()) {
    const filename = data.get('filename');
    const headers = {
        'Content-Type': 'multipart/form-data'
    };
    return request({
        baseUrl: 'base',
        url: `/common/tplUpload/${filename}`,
        method: 'POST',
        data,
        headers
    });
}

//图片文件上传
export function upload(data = {}) {
    const headers = {
        'Content-Type': 'multipart/form-data'
    };
    const formData = new FormData();
    for (const k in data) {
        if (Object.hasOwnProperty.call(data, k)) {
            const file = data[k];
            formData.append(k, file, file.name);
        }
    }
    return request({
        baseUrl: 'base',
        url: '/public/upload/picture',
        method: 'POST',
        data: formData,
        headers
    });
}
