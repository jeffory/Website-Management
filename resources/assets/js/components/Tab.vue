<template>
    <div class="tab-pane" v-show="index == activeTab">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: [
            'label',
            'icon'
        ],
        data() {
            return {
                activeTab: this.$parent.activeTab
            }
        },
        created () {
            this.$parent.tabPanes.push({
                index: this.index,
                label: this.label,
                icon: this.icon
            });
        },
        watch: {
            '$parent.activeTab': function (index, prevIndex) {
                this.activeTab = index;
            }
        }
    }
</script>

<style>
    
</style>