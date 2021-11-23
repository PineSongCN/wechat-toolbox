<template>
    <div class="message-board-read">
        <TheHead />
        <div class="main">
            <van-field
                v-model="data.message.to"
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
                @click="confirmSubmit"
            >
                查看留言
            </el-button>
        </div>
        <div v-if="data.list" class="main">
            <div class="title">给你的留言</div>
            <div v-if="data.list.length === 0" class="empty">
                <div class="label">暂时还没有人给你留言哦～</div>
                <div class="label">不过没关系，你可以给TA留个言呀～</div>
            </div>
            <div v-else>
                <div
                    v-for="item in data.list"
                    :key="item.message_board_id"
                    class="row"
                >
                    <div class="user">
                        <div class="user-box">
                            <img :src="item.avatar" class="img" />
                            <div class="label">{{ item.from }}</div>
                        </div>
                        <div class="time">{{ item.createTimeFormat }}</div>
                    </div>
                    <div class="content">
                        {{ item.content }}
                    </div>
                </div>
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
export default {
    name: 'MessageBoardread',
    components: { TheHead, TheFixed },
    data() {
        return {
            data: {
                tip: {
                    to: '请填写你的名字或暗号'
                },
                message: {
                    to: ''
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
            }
        };
    },
    computed: {},
    created() {},
    methods: {
        async confirmSubmit() {
            try {
                this.loading.submit = true;
                const confirmData = { ...this.data.message };
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
                confirmData.pageSize = 100;
                const res = await read(confirmData);
                this.$toast.success('念念不忘\n必有回响');
                for (const k in this.data.message) {
                    if (Object.hasOwnProperty.call(this.data.message, k)) {
                        this.data.message[k] = '';
                    }
                }
                this.data.list = this.formatList(res.list);
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
