<template>
    <div class="message-board-write">
        <TheHead />
        <div class="main">
            <van-field
                v-model="data.message.to"
                label-width="60"
                label="好友名字"
                placeholder="请填写好友名字或暗号"
                clearable
                autofocus
            />
            <van-field
                v-model="data.message.content"
                label-width="60"
                rows="5"
                autosize
                label="留言内容"
                type="textarea"
                maxlength="521"
                placeholder="请填写留言内容"
                show-word-limit
                clearable
            />
            <el-button
                class="button"
                type="primary"
                size="large"
                :loading="loading.submit"
                @click="confirmSubmit"
            >
                提交留言
            </el-button>
            <div class="disabled-label">感谢留言，暂时只能自己查看。</div>
        </div>
        <TheFixed :value="data.fixed" />
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import TheHead from './components/TheHead.vue';
import TheFixed from './components/TheFixed.vue';
import { write } from '@/api/message-board';
export default {
    name: 'MessageBoardWrite',
    components: { TheHead, TheFixed },
    data() {
        return {
            data: {
                tip: {
                    to: '请填写好友名字或暗号',
                    content: '请填写留言内容'
                },
                message: {
                    to: '',
                    content: ''
                },
                fixed: {
                    name: 'MessageBoardRead',
                    label: '查看留言'
                }
            },
            loading: {
                submit: false
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
                const res = await write(confirmData);
                this.$toast.success('念念不忘\n必有回响');
                // for (const k in this.data.message) {
                //     if (Object.hasOwnProperty.call(this.data.message, k)) {
                //         this.data.message[k] = '';
                //     }
                // }
                this.data.message.content = '';
                this.loading.submit = false;
            } catch (e) {
                console.log('confirmSubmit:e', e);
                this.loading.submit = false;
                this.data.message.content = '';
            }
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
.message-board-write {
    height: 100vh;
    font-size: 18rem;
    padding: 15px;
    position: relative;
    .main {
        width: 100%;
        box-sizing: border-box;
        background: #fff;
        padding: 20px;
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
        .disabled-label {
            font-size: 12px;
            color: #888;
            text-align: center;
            margin-top: 10px;
        }
    }
}
</style>
