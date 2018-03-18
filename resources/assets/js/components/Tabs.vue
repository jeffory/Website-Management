<template>
    <div class="tab-container">
        <div class="tabs is-boxed">
            <ul>
                <li v-for="(tab, index) in tabPanes" v-bind:class="{ 'is-active': index == activeTab }">
                    <a :href="'#tab-' + index" @click="activeTab = index">
                        <span class="icon is-small" v-if="tab.icon">
                            <i class="fa" :class="'fa-' + tab.icon"></i>
                        </span>
                        <span>{{ tab.label }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="tab-panes panel">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tabPanes: [],
                activeTab: parseInt(this.active) - 1 || 0
            }
        },
        props: [
            'active'
        ],
        mounted() {
            for (var index = 0; index < this.$children.length; index++) {
                this.$children[index].index = index;
            }
        },
    }
</script>