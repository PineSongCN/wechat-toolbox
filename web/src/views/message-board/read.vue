<template>
    <div class="message-board-read">
        <TheHead />
        <div class="main">
            <van-field
                v-model="data.to"
                label-width="60"
                label="我的名字"
                placeholder="请填写你的名字或暗号"
                clearable
                autofocus
            />
            <el-button
                class="button"
                type="primary"
                size="large"
                :loading="loading.submit"
                @click="doConfirm"
            >
                查看留言
            </el-button>
        </div>
        <div v-if="data.list" class="main">
            <div class="title">
                <span>给你的留言</span>
                <div v-if="data.list.length > 0">
                    <el-switch
                        v-if="showMultiavatar"
                        v-model="multiavatar"
                        size="mini"
                        @touchstart.native="touchstart"
                        @touchend.native="touchend"
                        @change="switchMultiavatar"
                    />
                </div>
            </div>
            <div v-if="data.list.length === 0" class="empty">
                <div class="label">暂时还没有人给你留言哦～</div>
                <div class="label">不过没关系，你可以给TA留个言呀～</div>
            </div>
            <div v-else>
                <van-list
                    v-model="loading.submit"
                    :finished="finished"
                    finished-text="没有更多了"
                    @load="confirmSubmit"
                >
                    <div
                        v-for="item in data.list"
                        :key="item.message_board_id"
                        class="row"
                    >
                        <div class="user">
                            <div class="user-box">
                                <img :src="Avatar(item)" class="img" />
                                <div class="label">{{ item.from }}</div>
                            </div>
                            <div class="time">{{ item.createTimeFormat }}</div>
                        </div>
                        <div class="content">
                            {{ item.content }}
                        </div>
                    </div>
                </van-list>
            </div>
        </div>
        <TheFixed :value="data.fixed" />
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import TheHead from './components/TheHead.vue';
import TheFixed from './components/TheFixed.vue';
import { read } from '@/api/message-board';
import { parseTime } from '@/utils';
import { getStorage, setStorage } from '@/utils/storage';
// https://api.multiavatar.com/null.png
export default {
    name: 'MessageBoardread',
    components: { TheHead, TheFixed },
    data() {
        const multiavatar = getStorage('multiavatar', false);
        const showMultiavatar = getStorage('showMultiavatar', true);
        return {
            data: {
                to: '',
                tip: {
                    to: '请填写你的名字或暗号'
                },
                message: {
                    to: ''
                    // endId: null,
                },
                list: null,
                fixed: {
                    name: 'MessageBoardWrite',
                    label: '创建留言'
                }
            },
            loading: {
                submit: false,
                list: false
            },
            pageNo: 1,
            finished: false,
            multiavatar,
            showMultiavatar: false,
            touchTime: 0
        };
    },
    computed: {
        Avatar() {
            return ({ avatar, create_time, client_code }) => {
                const CODE = client_code ? client_code : create_time;
                return this.multiavatar
                    ? `https://api.multiavatar.com/${CODE}.png`
                    : avatar;
            };
        }
    },
    created() {},
    methods: {
        switchMultiavatar(e) {
            setStorage('multiavatar', e);
        },
        touchstart() {
            this.touchTime = Date.now();
        },
        async touchend() {
            console.log(
                'Date.now() - this.touchTime',
                Date.now() - this.touchTime
            );
            try {
                if (Date.now() - this.touchTime > 1000) {
                    await this.$dialog.confirm({
                        message: '是否关闭并隐藏该按钮？'
                    });
                    this.multiavatar = false;
                    this.showMultiavatar = false;
                    setStorage('multiavatar', false);
                    setStorage('showMultiavatar', false);
                }
            } catch (e) {}
        },
        doConfirm() {
            if (this.data.to) {
                this.data.message.to = this.data.to;
                this.pageNo = 1;
                this.confirmSubmit();
            }
        },
        async confirmSubmit() {
            try {
                this.loading.submit = true;
                const confirmData = {
                    ...this.data.message
                };
                let error = null;
                for (const k in confirmData) {
                    if (Object.hasOwnProperty.call(confirmData, k)) {
                        const v = confirmData[k];
                        console.log('k', k, v);
                        confirmData[k] = v && v.trim();
                        if (!confirmData[k]) {
                            error = this.data.tip[k];
                            break;
                        }
                    }
                }
                if (error) {
                    this.$notify({
                        message: error,
                        type: 'warning'
                    });
                    this.loading.submit = false;
                    return;
                }
                confirmData.pageNo = this.pageNo;
                confirmData.pageSize = 10;
                const res = await read(confirmData);
                if (this.pageNo == 1) {
                    this.$toast.success('念念不忘\n必有回响');
                }
                // for (const k in this.data.message) {
                //     if (Object.hasOwnProperty.call(this.data.message, k)) {
                //         this.data.message[k] = '';
                //     }
                // }
                const dataList = this.pageNo > 1 ? this.data.list : [];
                const list = this.formatList(res.list);
                this.data.list = [...dataList, ...list];
                if (this.pageNo < res.totalPage) {
                    this.pageNo++;
                } else if (this.pageNo >= res.totalPage) {
                    this.finished = true;
                }
                this.loading.submit = false;
            } catch (e) {
                console.log('confirmSubmit:e', e);
                this.loading.submit = false;
            }
        },
        formatList(list) {
            const temp = [];
            for (const t of list) {
                const v = { ...t };
                v.createTimeFormat = parseTime(v.create_time);
                temp.push(v);
            }
            return temp;
        }
    }
};
</script>
<style lang="scss">
.client-web {
    .page-home {
        .content {
            text-indent: 2em;
        }
    }
}
</style>
<style lang="scss">
.message-board-read {
    height: 100vh;
    font-size: 18rem;
    padding: 15px;
    position: relative;
    .main {
        width: 100%;
        box-sizing: border-box;
        background: #fff;
        padding: 20px;
        + .main {
            margin-top: 10px;
        }
        .van-field__label {
            color: #f1b8af;
        }
        .van-cell {
            &::after {
                border-color: #f1b8af;
            }
            + .van-cell {
                &::after {
                    display: none;
                }
            }
        }
        .button {
            width: 100%;
            margin-top: 10px;
        }
        .title {
            border-bottom: 1px solid #f1b8af;
            padding-bottom: 10px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .empty {
            .label {
                text-align: center;
            }
        }
        .row {
            position: relative;
            padding: 10px 0;
            // + .row {
            //     margin-top: 10px;
            // }
            &::after {
                content: ' ';
                position: absolute;
                width: 100%;
                height: 1px;
                left: 0;
                bottom: 0;
                background-color: #f1b8af;
            }
            .user {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
                font-size: 12rem;
                color: #999;
                .user-box {
                    display: flex;
                    align-items: center;
                    .img {
                        width: 30px;
                        height: 30px;
                        border-radius: 50%;
                    }
                    .label {
                        margin-left: 5px;
                    }
                }
            }
            .content {
                color: #666;
                font-size: 14rem;
                white-space: pre-wrap;
            }
        }
    }
}
</style>
