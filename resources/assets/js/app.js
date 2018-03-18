
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import VueTimeago from 'vue-timeago'
import VeeValidate, { Validator } from 'vee-validate'

window.Pikaday = require('pikaday');

Vue.use(VueTimeago, {
    name: 'timeago', // component name, `timeago` by default
    locale: 'en-US',
    locales: {
        'en-US': [
            'just now',
            ['%s second ago', '%s seconds ago'],
            ['%s minute ago', '%s minutes ago'],
            ['%s hour ago', '%s hours ago'],
            ['%s day ago', '%s days ago'],
            ['%s week ago', '%s weeks ago'],
            ['%s month ago', '%s months ago'],
            ['%s year ago', '%s years ago'],
        ],
    },
});

Vue.use(VeeValidate);

Validator.extend('cpanel_verify', {
    getMessage: field => `Password is not complex enough.`,
    validate: value => {
        const url = '/client-area/management/email/geckode.com.au/password-check';

        if (value === '') {
            return false
        }

        return window.axios.post(url, { password: value })
            .then(response => {
                let password_strength = parseInt(response.data.strength);
                window.app.password_strength = parseInt(response.data.strength);

                return Promise.resolve({
                    valid: password_strength >= 50
                })
            }).catch(error => {
                return Promise.reject()
            })
    }
});

window.axios = require('axios');

require('./directives/inline-submit.js');

Vue.component('dropdown-menu', require('./components/DropdownMenu.vue'));
Vue.component('ticket-details', require('./components/TicketDetails.vue'));
Vue.component('message-attachments', require('./components/MessageAttachments.vue'));
Vue.component('tab', require('./components/Tab.vue'));
Vue.component('tabs', require('./components/Tabs.vue'));
Vue.component('data-table', require('./components/DataTable.vue'));
Vue.component('modal', require('./components/Modal.vue'));
Vue.component('flash-message', require('./components/FlashMessage.vue'));
Vue.component('invoice', require('./components/Invoice.vue'));

Vue.component('ticket-user-select', require('./components/TicketUserSelect.vue'));

window.onload = function() {
    let notifications = document.getElementsByClassName("notification");

    for (let i = 0; i < notifications.length; i++) {
        let notification = notifications[i];
        let timeout = notification.getAttribute('data-timeout');

        notification.classList.add('is-active');

        if (timeout !== null) {
            timeout = parseInt(timeout);

            setTimeout(() => {
                notification.classList.remove('is-active')
            }, timeout)
        }

        notification.getElementsByClassName('delete')[0].onclick = function (event) {
            event.target.parentNode.classList.remove('is-active')
        }
    }
};

// For "page-wide" events and data, ie. Modals
window.eventbus = new Vue({
    data: {
        modal: {}
    }
});

window.app = new Vue({
    data: {
        accounts: null,
        // Needs to be mapped. Template calls are within a function scope, "fns.apply".
        eventbus: window.eventbus,
        ticket: null,
        tickets: null,
        table_data: null,
        password_strength: 0,

        table_query: '',

        // Vee-validate scopes...
        email_creation_form: {},
        password_change_form: {},
        password_verify_form: {},
        loaded: false,
        users: null
    },
    el: '#app',
    created() {
        if (window.tickets !== undefined) {
            this.tickets = window.tickets
        }

        if (window.ticket !== undefined) {
            this.ticket = window.ticket
        }

        if (window.accounts !== undefined) {
            this.accounts = window.accounts
        }

        if (window.table_data !== undefined) {
            this.table_data = window.table_data
        }

        this.eventbus.$on('delete-ticket', function (data) {
            window.eventbus.$emit('show-modal', {id: 'delete-ticket-modal', ticket: data.id})
        });

        this.eventbus.$on('delete-email', function (data) {
            window.eventbus.$emit('show-modal', {id: 'delete-email-modal', email: data.email})
        });

        this.eventbus.$on('email-options', function (data) {
            window.eventbus.$emit('show-modal', {id: 'email-options-modal', email: data.email})
        });

        this.eventbus.$on('delete-user', function (data) {
            window.eventbus.$emit('show-modal', {
                id: 'delete-user-modal',
                user_id: data.id,
                user_name: data.name
            })
        })
    },
    mounted() {
        this.loopUntilLoaded()
    },
    methods: {
        loopUntilLoaded(timeout_limit = 30, timeout_count = 0) {
            this.loaded = this.isFullyLoaded();
            if (!this.loaded && timeout_count < timeout_limit) {
                setTimeout(() => {
                    this.loopUntilLoaded(timeout_limit, timeout_count)
                }, 250);

                timeout_count++
            } else {
                console.log('Vue and components loaded.')
            }
        },

        isFullyLoaded() {
            // Synchronous loop - could be replaced with a promise
            for (let i = this.$children.length - 1; i >= 0; i--) {
                let component = this.$children[i];

                if ('loaded' in component.$data && !component.$data.loaded) {
                    return false
                }

                if (!component._isMounted) {
                    return false
                }
            }

            return true
        }
    }
})
