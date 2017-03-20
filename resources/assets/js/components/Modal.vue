<template>
    <div class="modal" :class="{ 'is-active': active }">
        <div class="modal-background"></div>

        <div class="modal-content box">
            <slot dataset="dataset"></slot>
        </div>

        <button class="modal-close" @click="hide()"></button>
    </div>
</template>

<script>
    export default {
        props: [
            'visible'
        ],
        data() {
            return {
                active: false,
                modal_data: {}
            }
        },
        created() {
            if (this.visible === true) {
                this.active = true;
            }
        },
        methods: {
            show(data) {
                this.modal_data = Object.assign({}, data);
                this.active = true;
            },
            hide() {
                this.active = false;
            }
        }
    }
</script>