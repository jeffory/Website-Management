<template>
    <div class="datatable" style="width: 100%">
        <table class="table">
            <thead>
                <tr>
                    <th v-for="column, index in view_columns">
                        <span>{{ column }}</span>

                        <div style="float: right; cursor: pointer; margin-top: 4px">
                            <span @click="column_toggle_sort(index)" class="icon is-small">
                                <i v-if="sort.index == index &amp;&amp; sort.desc" class="fa fa-sort-desc"></i>
                                <i v-else-if="sort.index == index &amp;&amp; !sort.desc" class="fa fa-sort-asc"></i>
                                <i v-else style="opacity:.4;" class="fa fa-sort"></i>
                            </span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in view_data">
                    <td v-for="(column, index) in row">

                        <div v-if="index in slot_map" v-html="slot_html[slot_map[index]]">
                            <!-- slot goes here -->
                        </div>

                        <template v-else :name="column">
                            {{ column }}
                        </template>
                    </td>
                </tr>

                <tr v-if="view_data.length == 0">
                    <td :colspan="view_columns.length" class="has-text-centered">No data specified.</td>
                </tr>
            </tbody>
        </table>

        <slot name="controls"></slot>
    </div>
</template>

<script>
    export default {
        props: [
            'data', 'columns', 'query', 'controls_column'
        ],
        data () {
            return {
                view_columns: [],
                view_data: [],
                data_map: {},
                slot_map: {},
                slot_html: {
                    'controls': '<button class="button is-primary">Button</button>'
                },
                sort: {
                    index: 0,
                    desc: true
                }
            }
        },
        created() {
            this.parse_columns();
            this.parse_data();
            this.sort_data();
        },
        methods: {
            showModal(modal, data) {
                // TODO: Find better method.
                this.$parent.$options.methods.showModal(modal, data);
            },
            parse_columns() {
                this.columns.forEach((column, index) => {
                    if (typeof(column) == 'string') {
                        return this.view_columns.push(column);
                    }

                    this.view_columns.push(column[0]);

                    if (column.length > 1) {
                        // So this would make a map like { column_index: field }
                        this.data_map[index] = column[1];

                        if (column[1].startsWith('!')) {
                            // There's a slot to go in this column.
                            this.slot_map[parseInt(index)] = column[1].substring(1);
                        }
                    }
                });
            },
            column_toggle_sort(column_index) {
                if (column_index == this.sort.index) {
                    this.sort.desc = !this.sort.desc;
                } else {
                    this.sort.index = column_index;
                    this.sort.desc = true;
                }

                this.sort_data();
            },
            parse_data() {
                if (this.data == undefined) {
                    return;
                }

                this.view_data = [];

                this.data.forEach((row_object) => {
                    let row = [];

                    // Assume an object if the data_map is set. Otherwise parse as a straight array.
                    if (Object.keys(this.data_map).length > 0) {
                        Object.keys(this.data_map).forEach((data_map_key) => {
                            let data_map_value = this.data_map[data_map_key];

                            if (!data_map_value.startsWith('!') && data_map_value in row_object) {
                                row.push(row_object[data_map_value]); 
                            } else {
                                // Need to check if it's a slot, if it is, insert a blank string so it still is iterated over.
                                if (data_map_key in this.slot_map) {
                                    // let slot_name = this.slot_map[data_map_key];
                                    row.push(''); 
                                }
                            }
                        });
                    } else {
                        row = row_object;
                    }

                    // Ensure query is matched if provided.
                    if (typeof(this.query) !== "undefined" && this.query !== "") {
                        if (this._query_match_row(row, this.query)) {
                            this.view_data.push(row);
                        }
                    } else {
                        this.view_data.push(row);
                    }
                });

            },
            /**
             * Reorder the table by ascending or descending order.
             * This does not change values in the actual table.
             */
            sort_data() {
                this.view_data = _.sortBy(this.view_data, [(row) => {
                    let cell = row[this.sort.index];

                    if (cell == "None") {
                        return 0;
                    }

                    cell = this._convert_file_sizes(row[this.sort.index])

                    return cell;
                }]);

                if (!this.sort.desc) {
                    this.view_data.reverse();
                }
            },
            /**
             * Returns a slot's content.
             * @param {String} Slot name
             * @returns {String} The slot's content.
             */
            _slot_content(name) {
                let slot = this.$slots;

                return slot;
            },
            /**
             * Returns whether a query matches the row.
             * @param {Array} row
             * @param {String} query
             * @returns {Boolean} The filtered row.
             */
            _query_match_row(row, query) {
                let matched = false;

                row.forEach((cell) => {
                    let query = String(this.query).toLowerCase().trim();
                    if (String(cell).toLowerCase().indexOf(query) !== -1) {
                        matched = true;
                    }
                });

                return matched;
            },
            /**
             * Convert a column if it appears to be a file size.
             * @param {String} value
             * @returns {String} Either converted file sizes in bytes or original string if no match
             */
            _convert_file_sizes(value) {
                let sizes = ['KB','MB','GB','TB'];
                let regex = RegExp('^([0-9\\.]+)\\s?(' + sizes.join('|') + ')', 'ig')
                let matches = regex.exec(value);

                if (matches) {
                    return Math.pow(1024, _.indexOf(sizes, matches[2])) * parseFloat(matches[1]);
                } else {
                    return value;
                }
            }
        },
        watch: {
            query() {
                this.parse_data();
                this.sort_data();
            }
        }
    };
</script>