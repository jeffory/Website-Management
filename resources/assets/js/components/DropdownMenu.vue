<template>
    <div class="dropdown-menu">
        <button @click="toggleMenu()" class="button is-medium is-outlined">
            <slot name="button"></slot>
        </button>

        <aside class="menu" v-show="showMenu">
            <slot name="menu"></slot>
        </aside>
    </div>
</template>

<script>
    export default {
        props: [
            'alignment'
        ],
        data() {
            return {
                menuClasses: {
                    'has-text-right': this.alignment === 'right'
                },
                showMenu: false
            }
        },
        computed: {
            $child() {
                return this.$el.firstElementChild;
            }
        },
        methods: {
            mounted() {
                console.log(this.class);
            },
            toggleMenu() {
                this.showMenu = !this.showMenu;
            }
        }
    }
</script>

<style>
    aside.menu {
        background-color: #fff;
        border: 1px solid #dbdbdb;
        position: absolute;
        transform: translateX(-75%);
        width: fit-content;
        z-index: 9010;
    }

    .menu-list a {
        padding: 1em 2.5em;
    }
</style>