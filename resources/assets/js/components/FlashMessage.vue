<template>
    <div class="notification is-success" v-if="visible">
        <button class="delete" @click="visible = false"></button>

        <slot></slot>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                timeout: 5000,
                visible: true
            }
        },
        mounted() {
            setTimeout(() => {
                this.visible = false;
            }, this.timeout);
        }
    }
</script>

<style type="text/css">
    .notification {
        min-width: 400px;
        opacity: .95;
        position: fixed;
        right: 1em;
        top: 1em;
        z-index: 9009;
    }
</style>