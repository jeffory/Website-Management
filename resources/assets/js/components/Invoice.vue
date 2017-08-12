<template>
    <div>
        <span v-model="date"></span>

        <table class="invoice-items">
            <thead>
            <tr class="highlighted">
                <th></th>
                <th class="has-text-left">Description</th>
                <th>Cost</th>
                <th>Hrs/Quantity</th>
                <th>Total</th>
            </tr>
            </thead>
            <transition-group name="invoice-items" tag="tbody">
                <tr v-for="item in items" :key="item">
                    <td style="padding: 2.3em .7em 2.3em 2em" class="has-text-centered">
                        <input type="checkbox" class="checkbox">
                    </td>

                    <td style="width: 70vw">
                        <textarea name="description[]" class="textarea" rows="2" v-model="item.description"
                                  @input="addLineIfNecessary"></textarea>
                    </td>

                    <td class="numeric" style="width: 11em">
                        <numeric name="cost[]" class="input " v-model="item.cost" :precision="2" currency="$"
                                 @input="addLineIfNecessary" style="max-width: 100%"></numeric>
                    </td>

                    <td class="numeric" style="width: 10em">
                        <numeric name="quantity[]" class="input" v-model="item.quantity" :precision="1"
                                 @input="addLineIfNecessary"></numeric>
                    </td>

                    <td class="line-total has-text-right numeric">
                        <numeric :value="lineTotal(item)" :precision="2" :read-only="true" currency="$"></numeric>
                    </td>
                </tr>
            </transition-group>

            <tr class="totals-row">
                <td colspan="3" rowspan="2" class="borderless">
                    <div>
                        <textarea class="textarea" v-model="note" style="height: 6em; width: 500px" name="note"
                                  placeholder="Invoice note"></textarea>
                    </div>

                </td>
                <td class="has-text-centered highlighted">Total</td>
                <td class="has-text-right highlighted numeric">
                    <numeric v-bind:value="total" v-bind:precision="2" v-bind:read-only="true" currency="$"></numeric>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
    import Numeric from 'vue-numeric';
    import Draggable from 'vuedraggable'

    export default {
        components: {
            Numeric,
            Draggable
        },

        data() {
            return {
                items: []
            }
        },

        mounted() {
            if (!this.isLastItemBlank()) {
                this.addNewLine();
            }
        },

        methods: {
            selectAllIfDefaults(event) {
                console.log(event);
            },

            lineTotal(item) {
                return item.cost * item.quantity;
            },

            isLastItemBlank() {
                return this.isItemBlank(this.items.length - 1);
            },

            isItemBlank(index) {
                if (typeof(this.items[index]) === 'undefined') {
                    return false;
                }
                return (this.items[index].description === null ||
                    this.items[index].description.trim() === '') &&
                    this.items[index].cost === 0 &&
                    this.items[index].quantity === 0
            },

            addNewLine() {
                this.items.push({
                    description: null,
                    cost: 0,
                    quantity: 0
                });
            },

            addLineIfNecessary() {
                this.trimExcessItems();

                if (!this.isLastItemBlank()) {
                    this.addNewLine();
                }
            },

            trimExcessItems() {
                // Remove extra empty lines from the end of the invoice, should only be one.
                if (this.items.length <= 2) return;

                for (let i = this.items.length - 1; i > 1; i--) {
                    if (this.isItemBlank(i) && this.isItemBlank(i - 1)) {
                        this.items.splice(i, 1);
                    }

                    if (this.isItemBlank(i) && i !== this.items.length - 1) {
                        this.items.splice(i, 1);
                    }
                }
            }
        },

        computed: {
            total() {
                let total = 0;

                for (let i = 0; i < this.items.length - 1; i++) {
                    total += this.lineTotal(this.items[i]);
                }

                return total;
            }
        }
    }
</script>

<style>
    .invoice-items .input,
    .invoice-items .textarea {
        border: 2px #bbb dotted;
        box-shadow: none;
    }

    .input {
        min-width: 6em;
    }

    .textarea {
        min-height: 4.3em;
    }

    .line-total {
        min-width: 6em;
    }
</style>