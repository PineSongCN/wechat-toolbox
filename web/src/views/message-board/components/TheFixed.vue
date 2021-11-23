<template>
    <router-link :to="{ name: value.name }" class="message-board-fixed">
        {{value.label}}
    </router-link>
</template>

<script>
export default {
    name: 'TheHead',
    props: {
        value: {
            type: Object,
            default: () => ({})
        }
    },
    computed: {},
    methods: {}
};
</script>

<style lang="scss">
.message-board-fixed {
    width: 80px;
    height: 80px;
    position: fixed;
    bottom: 40px;
    right: 15px;
    border-radius: 50%;
    background: #f1b8af;
    color: #fff;
    box-sizing: border-box;

    display: flex;
    justify-content: center;
    align-items: center;
    letter-spacing: 2px;
    padding: 20px;

    text-align: center;
    font-size: 16px;
    &::after {
        content:' ';
        position: absolute;
        width: 68px;
        height: 68px;
        border: 1px solid #fff;
        border-radius: 50%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
    }
}
</style>
