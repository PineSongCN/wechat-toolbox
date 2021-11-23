<template>
    <div id="app" :class="comClass">
        <router-view />
    </div>
</template>

<script>
import device from 'current-device';

export default {
    name: 'App',
    data() {
        const comClass = [];
        const isDesktop = device.desktop();
        if (isDesktop) {
            comClass.push('client-web');
        }
        // const desktop = device.desktop();
        return {
            // desktop,
            comClass
        };
    },
    beforeMount() {
        // if (this.desktop && typeof document !== 'undefined') {
        //     document.querySelector('body').className = 'client-web';
        // }
    },
    mounted() {}
};
</script>
