<template>
    <div class="page-home">
        <TheHead />
        <div class="main">
            <el-button class="button" type="primary" size="large">看留言</el-button>
            <el-button class="button" type="primary" size="large">写留言</el-button>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import TheHead from './components/TheHead.vue';

export default {
    name: 'Home',
    components: {TheHead },
    data() {
        return {
        };
    },
    computed: {
    },
    created() {},
    methods: {
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
    .button {
        width: 26rem;
        +.button {
            margin-left: 0;
            margin-top: 50px;
        }
    }
}
</style>
