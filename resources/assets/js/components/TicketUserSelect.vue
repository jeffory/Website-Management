<template>
    <div class="field">
        <div class="control">
            <label class="checkbox is-medium">
                <input type="checkbox" v-model="active">
                Create ticket on behalf of another user
            </label>
        </div>

        <div class="control has-icons-right" v-if="active">
            <input type="text" class="input" placeholder="Username" v-model="query" @input="onChange"
                   v-if="selectedUserID === null" ref="searchInput" @keydown.esc.prevent="reset"
                   @keydown.enter.prevent="selectionChoose(selectionIndex)"
                   @keydown.up.prevent="selectionUp"
                   @keydown.down.prevent="selectionDown"
                   autocomplete="none">

            <div v-else>
                <strong v-text="query" style="line-height: 2em; margin-right: 1em"></strong>

                <button class="button is-link" @click.prevent="reset">Change user</button>
            </div>

            <div class="autocomplete" v-if="query !== '' && selectedUserID === null">
                <div class="results">
                    <ul v-if="results.length > 0">
                        <li v-for="result, index in results" @click="selectionChoose(index)"
                            v-text="result.name" :class="{active: selectionIndex === index }"
                            @mouseover="selectionIndex = index"></li>
                    </ul>

                    <p v-else-if="searching">
                        Loading...
                    </p>

                    <p v-else>
                        No results found.
                    </p>
                </div>
            </div>
        </div>

        <input type="hidden" name="ticket_user_id" v-if="active" :value="selectedUserID">
    </div>
</template>

<script>
    export default {
        props: ['endpoint'],

        data() {
            return {
                active: false,
                results: [],
                query: '',
                selectedUserID: null,
                changeTimer: null,
                searching: false,
                selectionIndex: -1
            }
        },

        methods: {
            onChange() {
                if (this.query === '') {
                    this.results = [];
                    return;
                }

                this.searching = true;
                this.selectionIndex = 0;

                clearTimeout(this.changeTimer);

                this.changeTimer = setTimeout(() => {
                    this.search(this.query);
                }, 300)
            },

            search(query) {
                this.selectedUserID = null;

                this.$http.get(this.endpoint, {params: {query: query, limit: 10}})
                    .then(response => {
                        this.results = response.body;
                        this.searching = false;
                    });
            },

            reset() {
                this.results = [];
                this.query = '';
                this.selectedUserID = null;
                this.selectionIndex = -1;

                this.$nextTick(() => {
                    this.$refs.searchInput.focus();
                });
            },

            selectionUp() {
                if (this.selectionIndex <= 0) return;
                this.selectionIndex--;
            },

            selectionDown() {
                if (this.selectionIndex === this.results.length - 1) return;
                this.selectionIndex++;
            },

            selectionChoose(index) {
                this.selectedUserID = this.results[index].id;
                this.query = this.results[index].name;
            }
        }
    }
</script>

<style scoped>
    .autocomplete {
        position: relative;
    }

    .autocomplete .results {
        background-color: #fff;
        border: 1px solid #ddd;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 9001;
    }

    .autocomplete .results li {
        cursor: pointer;
    }

    .autocomplete .results li,
    .autocomplete .results p {
        margin-bottom: 0;
        padding: .5em 1em;
    }

    .autocomplete .results li.active {
        background-color: #6052af;
        color: #fff;
    }
</style>