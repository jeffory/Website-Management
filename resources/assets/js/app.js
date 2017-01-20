
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

var Turbolinks = require("turbolinks");
Turbolinks.start();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));


Vue.component('ticket-details', {
    props: [
        'userId',
        'ticketId'
    ],
    data() {
        return {
            newMessage: '',
            viewers: [],
            ticket: {}
        }
    },
    mounted() {
        // this.listen();
        this.getTicket();
    },
    methods: {
        /**
         * Listen to Echo channels.
         */
        listen() {
            // Echo.join('ticket.' + this.ticketId)
            //     .here(viewers => {
            //         this.viewers = viewers;
            //         console.log(this.viewers);
            //     });
        },

        /**
         * Get the ticket data.
         */
        getTicket() {
            if (typeof(ticket) !== 'undefined') {
                this.ticket = ticket;
            } else {
                this.$http.get('/tickets/' + this.ticketId)
                    .then(response => {
                        this.ticket = response.data.ticket;
                    });
            }
            
        },

        /**
         * Delete a ticket.
         */
        storeMessage() {
            this.$http.post('/tickets/' + this.ticketId + '/message', { message: this.newMessage })
                .then(response => {
                    // If we fetch the message from the response we get the username...
                    this.addMessage(response.data)
                    this.newMessage = '';
                });
        },

        addMessage(message) {
            this.ticket.messages.push(message);
        },

        removeMessage(id) {
            this.ticket.messages = _.reject(this.ticket.messages, function(message) {
                return message.id == id;
            });
        },

        /**
         * Delete the ticket.
         */
        deleteTicket() {
            this.$http.delete('/tickets/' + this.ticketId)
                .then(response => {
                    window.location = "/tickets";
                });
        },

        /**
         * Delete a ticket.
         */
        deleteMessage(ticketMessageID) {
            this.$http.post('/tickets/' + this.ticketId + '/message/' + ticketMessageID, {  _method: 'DELETE' });
            this.removeMessage(ticketMessageID);
        }
    }
});

import VueTimeago from 'vue-timeago'

Vue.use(VueTimeago, {
    name: 'timeago', // component name, `timeago` by default
    locale: 'en-US',
    locales: {
        'en-US': [
            "just now",
            ["%s second ago", "%s seconds ago"],
            ["%s minute ago", "%s minutes ago"],
            ["%s hour ago", "%s hours ago"],
            ["%s day ago", "%s days ago"],
            ["%s week ago", "%s weeks ago"],
            ["%s month ago", "%s months ago"],
            ["%s year ago", "%s years ago"]
        ]
    }
})

const app = new Vue({
    el: '#app',

    filters: {
      timestamp: function(date) {
        return Date.parse(date);
      }
    }
});
