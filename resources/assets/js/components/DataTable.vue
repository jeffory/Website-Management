<template>
  <div>
    <table class="table datatable">
      <thead>
        <tr>
          <th v-for="(column, index) in data.columns">
            <span>{{ column.caption }}</span>

            <div class="column-sort-handler" v-if="!column.type">
              <span @click="sort_by_column(column.key)" class="icon is-small">
                <i v-if="sortBy.key == column.key && sortBy.desc" class="fa fa-sort-desc"></i>
                <i v-else-if="sortBy.key == column.key && !sortBy.desc" class="fa fa-sort-asc"></i>
                <i v-else style="opacity: .4;" class="fa fa-sort"></i>
              </span>
            </div>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in parsedData.data" :class="{'is-clickable': row_has_link(row)}" @click="row_click(row)">
          <template v-for="(column, index) in parsedData.columns">

            <td :class="column.classes">
              <strong v-if="column.mobile_caption" class="is-hidden-tablet is-label" v-text="column.caption"></strong>

              <div v-if="column.type == 'buttons'">
                <template v-for="button in column.buttons">

                  <a :href="parse_text(button.link, row)" :class="'button ' + button.classes" v-if="button.hasOwnProperty('link')">
                    <span class="icon is-small" v-if="button.hasOwnProperty('faIcon')">
                      <i :class="'fa fa-' + button['faIcon']"></i>
                    </span>&nbsp;

                    <span>{{ button.caption }}</span>
                  </a>

                  <button :class="'button ' + button.classes" @click.stop="eventbus.$emit(button.emit, row)" v-else>
                    <span class="icon is-small" v-if="button.hasOwnProperty('faIcon')">
                      <i :class="'fa fa-' + button['faIcon']"></i>
                    </span>&nbsp;

                    {{ button.caption }}
                  </button>

                </template>
              </div>

              <template v-if="column.type == 'tags' && column.tags.hasOwnProperty(row[column.key])">
                <span class="tag" :class="column.tags[row[column.key]].classes" v-text="column.tags[row[column.key]].text"></span>
              </template>

              <span v-else>
                <a :href="row._link" v-if="column._link">
                  {{ get_column_from_row(column.key, row) }}
                </a>

                <template v-else>
                  {{ get_column_from_row(column.key, row) }}
                </template>
              </span>
            </td>

          </template>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style type="text/css">
  .datatable {
    width: 100%;
  }

  .column-sort-handler {
    cursor: pointer;
    float: right;
    margin-top: 4px;
  }

  td {
    padding: 5px;
    vertical-align: middle;
  }

  td button:not(:last-child) {
    margin-right: 5px;
  }

  tr.is-clickable {
    cursor: pointer;
  }

  tr.is-clickable:hover {
    background-color: #e3f7fa;
  }

  @media screen and (max-width: 768px) {
    .mobile-full-width {
      width: 100%;
    }

    .mobile-half-width {
      width: 49%;
    }

    .table tbody,
    .table tr,
    .table td {
      border: 0;
      padding: .2em 0;
    }

    .table tr {
      border-bottom: 1px solid #dbdbdb;
      display: block;
      padding: 1.5em 0 1em;
    }

    .table tr:after {
      content: " ";
      display: table;
      clear: both;
    }

    .table td {
      display: block;
      clear: right;
      line-height: 1.8em;
      padding: .1em .25em;
    }

    .table thead {
      display: none;
    }
  }
</style>

<script>
  export default {
    props: {
      data: {},
      query: { default: '' }
    },
    data () {
      return {
        eventbus: window.eventbus,
        parsedData: {},
        sortBy: {
          key: 'title',
          desc: true
        }
      }
    },
    created () {
      this.parsedData = _.cloneDeep(this.data)
      this.sort_data(this.sortBy)
      this.filter_data(this.query)
    },
    watch: {
      query (query) {
        this.parsedData = _.cloneDeep(this.data)
        this.sort_data(this.sortBy)
        this.filter_data(query)
      }
    },
    methods: {
      /**
        * Determine if a row is clickable
        * @param {object} row.
        */
      row_has_link (row) {
        return _.has(row, '_link')
      },
      /**
        * Handle a row click.
        * @param {object} row.
        */
      row_click (row) {
        if (this.row_has_link(row)) {

          if (typeof(Turbolinks) !== 'undefined') {
            Turbolinks.visit(row['_link'], {action: 'advance'})
          } else {
            document.location.href = row['_link']
          }
        }
      },
      /**
        * Sort table by a specified column.
        *
        * @param {integer} columnIndex.
        */
      sort_by_column (columnKey) {
        if (columnKey === this.sortBy.key) {
          this.sortBy.desc = !this.sortBy.desc
        } else {
          this.sortBy.key = columnKey
          this.sortBy.desc = true
        }

        this.sort_data(this.sortBy)
      },
      /**
        * Whether a column is defined in the column definition.
        *
        * @param {string} column name
        */
      has_column (columnKey) {
        return _.find(this.parsedData.columns, {key: columnKey}) !== undefined
      },
      /**
        * Retrieve a column by key/name from a supplied row.
        *
        * @param {string} column name
        * @param {object} row
        */
      get_column_from_row (key, row) {
        if (!_.has(row, key)) {
          return ''
        }

        if (typeof row[key] !== 'object') {
          return row[key]
        }
      },
      /**
        * Compare two values and determine if they are similar.
        *
        * @param {string} value1
        * @param {string} value2
        */
      lazy_compare (value1, value2) {
        value1 = value1.toString().toLowerCase().trim()
        value2 = value2.toString().toLowerCase().trim()

        return value1.indexOf(value2) !== -1
      },
      /**
        * Returns whether a query matches the row.
        *
        * @param {String} query
        * @param {Array} row
        *
        * @returns {Boolean} If it is a match.
        */
      query_matches_row (query, row) {
        let matched = false

        Object.values(row).forEach((cell) => {
          if (this.lazy_compare(cell, query) === true) {
            matched = true
          }
        })

        return matched
      },
      /**
        * Filter the table's data.
        *
        * @param {object} data
        */
      filter_data (searchQuery) {
        // Break up query into words, keep queries together if double quotes are used
        let queries = searchQuery.trim().match(/(?:[^\s"]+|"[^"]*")+/g)

        if (!queries) {
          return
        }

        let data = this.data.data

        queries.forEach((query) => {
          // Find any special queries, eg: column_name:expected_value
          let queryParts = /^([a-z_\-0-9]+):"?([^\n"]+)"?$/g.exec(query)

          if (queryParts && this.has_column(queryParts[1])) {
            data = _.filter(data, (row) => {
              return this.lazy_compare(row[queryParts[1]], queryParts[2])
            })
          } else {
            data = _.filter(data, (row) => {
              return this.query_matches_row(query, row)
            })
          }
        })

        this.parsedData.data = data
      },
      /**
        * Sort the table data.
        *
        * TODO/WARNING: This doesn't sort numbers at the ends of strings correctly, so
        *               "Ticket 23" would be before "Ticket 4" in descending order.
        *
        * @param {object} data
        */
      sort_data (sort) {
        this.parsedData.data = _.sortBy(this.parsedData.data, [(row) => {
          let cell = row[sort.key]

          if (cell === 'None') {
            return 0
          }

          cell = this.convert_file_sizes(cell)

          return cell
        }])

        if (!sort.desc) {
          this.parsedData.data.reverse()
        }
      },
      /**
       * Convert a column if it appears to be a file size.
       *
       * @param {String} value
       *
       * @returns {String} Either converted file sizes in bytes or original string if no match
       */
      convert_file_sizes (value) {
        let sizes = ['KB', 'MB', 'GB', 'TB']
        let regex = RegExp('^([0-9\\.]+)\\s?(' + sizes.join('|') + ')', 'ig')
        let matches = regex.exec(value)

        if (matches) {
          return Math.pow(1024, _.indexOf(sizes, matches[2])) * parseFloat(matches[1])
        } else {
          return value
        }
      },
      /**
       * 
       *
       * @param {String} value
       *
       * @returns {String} Either converted file sizes in bytes or original string if no match
       */
      parse_text(text, row) {
        return text.replace(
          /\{([a-z0-9\-]+)\}/ig, (match, capture) => {
            return row[capture]
          }
        )
      }
    }
  }
</script>
