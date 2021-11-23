<template>
    <div class="message-board-head">
        <img :src="require('@/assets/message-board/logo.jpg')" class="img" />
        <div class="label">
            念念不忘，必有回响。
        </div>
    </div>
</template>

<script>
export default {
    name: 'TheHead',
    props: {},
    computed: {},
    methods: {}
};
</script>

<style lang="scss">
.message-board-head {
    margin: 10px 0 20px 0;
    color: #f1b8af;
    display: flex;
    align-items: center;
    .img {
        width: 100rem;
        height: 100rem;
        display: block;
        border-radius: 50%;
        margin-right: 35rem;
    }
}
</style>
