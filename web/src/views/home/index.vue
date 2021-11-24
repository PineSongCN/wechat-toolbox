<template>
    <div class="page-home">
        <TheHead />
        <div class="main">
            <router-link :to="{ name: 'MessageBoardRead' }" class="link">
                <el-button class="button" type="primary" size="large">
                    看留言
                </el-button>
            </router-link>
            <router-link :to="{ name: 'MessageBoardWrite' }" class="link">
                <el-button class="button" type="primary" size="large">
                    写留言
                </el-button>
            </router-link>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import TheHead from '@/views/message-board/components/TheHead.vue';

export default {
    name: 'MessageBoard',
    components: { TheHead },
    data() {
        return {};
    },
    computed: {},
    created() {},
    methods: {}
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
.page-home {
    height: 100vh;
    font-size: 18rem;
    padding: 15px;
    position: relative;
    .main {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .link {
        .button {
            width: 60vw;
        }
        + .link {
            margin-left: 0;
            margin-top: 50px;
        }
    }
}
</style>
