<template>
    <div class="dropdown-menu">
        <button @click="toggleMenu()" class="button is-outlined" @blur="hideMenu">
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
            toggleMenu() {
                this.showMenu = !this.showMenu;
            },
            hideMenu() {
                // Without a timeout the menu is closed before the element can be clicked.
                setTimeout(() => {
                    this.showMenu = false;
                }, 200);
            }
        }
    }
</script>

<style>
    aside.menu {
        background-color: #fff;
        border: 1px solid #dbdbdb;
        position: absolute;
        transform: translateX(-53%);
        width: fit-content;
        z-index: 9010;
    }

    .menu-list a {
        padding: 1em 2.5em;
    }
</style>