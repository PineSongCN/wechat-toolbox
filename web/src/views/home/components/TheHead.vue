<template>
    <div class="home-head">
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
.home-head {
    margin: 10px 0 20px 0;
    color: #f1b8af;
    display: flex;
    align-items: center;
    .img {
        width: 10rem;
        height: 10rem;
        display: block;
        border-radius: 50%;
        margin-right: 3.5rem;
    }
}
</style>
