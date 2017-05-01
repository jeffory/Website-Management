<template>
    <div class="modal" :id="id" :class="{
        'is-active': active,
        'has-text-right': (align == 'right'),
        'has-text-centered': (align == 'center')}">
        <div class="modal-background"></div>

        <div class="modal-content box">
            <slot></slot>
        </div>

        <button class="modal-close" @click="hide()"></button>
    </div>
</template>

<script>
    export default {
        props: ['id', 'align'],
        data() {
            return {
                active: false,
                eventbus: this.$parent.eventbus,
                modaldata: {},
            }
        },
        methods: {
            show(data) {
                // FYI: The scope for the components' slot is actually the parent.
                // Therefore we will set any data on the 'global event bus'.
                this.eventbus.modal[data.id] = data;
                this.active = true;
            },
            hide() {
                this.active = false;
            }
        },
        mounted() {
            // Create neccessary modal keys. Needs to use $set for reactivity.
            this.eventbus.$set(this.eventbus.modal, this.id, {});

            this.eventbus.$on('show-modal', (data) => {
                if (data.id == this.id) {
                    this.show(data);
                }
            });

            this.eventbus.$on('hide-modal', (id) => {
                if (id == this.id) {
                    this.hide(id);
                }
            });
        }
    }
</script>

<style type="text/css">
    .modal-background {
        z-index: 9011;
    }
    .modal-content,
    .modal-close {
        z-index: 9012;
    }
</style>