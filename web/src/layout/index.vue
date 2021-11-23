<template>
    <section class="app-main">
        <transition name="fade-transform" mode="out-in">
            <keep-alive :include="cachedViews">
                <router-view :key="key" />
            </keep-alive>
        </transition>
    </section>
</template>

<script>
export default {
    name: 'AppMain',
    computed: {
        cachedViews() {
            return [];
        },
        key() {
            return this.$route.path;
        }
    }
};
</script>

<style lang="scss" scoped>
.app-main {
    min-height: 100vh;
    width: 100%;
    position: relative;
    overflow: auto;
}

</style>

<style lang="scss">
</style>
