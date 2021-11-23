import { MessageBox, Message } from 'element-ui';

export const session = (h) => ({
    title: '',
    data: [
        {
            content:
                '通过搜寻你在那张诡异的图纸背面发现了潦草的字迹，【3.7】【3.21】【3.21】【3.14】你看了眼窗外，虽然记不清自己今天是第几号，但却清晰记得自己是在3月14日出发的。<br>你来到了暗道边，油灯照亮了黄铜锁上面的触手斑驳残留着触手的纹路。你再次查阅了图纸后面的字迹，开始尝试打开密码锁。',
            next: '开锁',
            class: ['center'],
            beforeNext: async () => {
                const key = Date.now();
                let value = undefined;
                const msgbox = {
                    key: Math.random(),
                    data: [
                        {
                            key: Math.random(),
                            value: ''
                        },
                        {
                            key: Math.random(),
                            value: ''
                        },
                        {
                            key: Math.random(),
                            value: ''
                        },
                        {
                            key: Math.random(),
                            value: ''
                        }
                    ],
                    confirm: ['上弦', '下弦', '下弦', '满月']
                };
                const inputBox = [];
                for (const i in msgbox.data) {
                    inputBox.push(
                        h('input', {
                            key: msgbox.data[i]['key'],
                            class: 'session-input',
                            props: {
                                value: msgbox.data[i]['value']
                            },
                            domProps: {
                                autofocus: i == 0 ? 'autofocus' : ''
                            },
                            on: {
                                input(e) {
                                    const value = e.target.value;
                                    msgbox.data[i]['value'] = value;
                                    // console.log('input', e);
                                },
                                change(e) {
                                    // console.log('change', e);
                                }
                            }
                        })
                    );
                }
                const res = await MessageBox({
                    title: '开锁',
                    message: h('div', { key, class: 'session-box' }, [
                        h(
                            'div',
                            {
                                style: 'margin-bottom:15px;'
                            },
                            '请将正确月相符号输入框内'
                        ),
                        h(
                            'div',
                            {
                                class: 'session-list'
                            },
                            [...inputBox]
                        )
                    ]),
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    beforeClose(action, instance, done) {
                        if (action === 'confirm') {
                            let tag = true;
                            for (const k in msgbox.data) {
                                if (
                                    Object.hasOwnProperty.call(msgbox.data, k)
                                ) {
                                    const value =
                                        msgbox.data[k]['value'].trim();
                                    const confirm = msgbox.confirm[k];
                                    if (value !== confirm) {
                                        tag = false;
                                    }
                                }
                            }
                            if (tag) {
                                Message({
                                    message: '开锁成功',
                                    type: 'success',
                                    duration: 1000
                                });
                                done();
                            } else {
                                Message({
                                    message: '锁未打开，请重试',
                                    type: 'warning',
                                    duration: 1000
                                });
                            }
                        } else {
                            done();
                        }
                    }
                });
            }
        }
    ]
});
