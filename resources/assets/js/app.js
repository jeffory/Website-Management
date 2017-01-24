
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// var Turbolinks = require("turbolinks");
// Turbolinks.start();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));

function generateUUID(){
    var d = new Date().getTime();
    if(window.performance && typeof window.performance.now === "function"){
        d += performance.now(); //use high-precision timer if available
    }
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x3|0x8)).toString(16);
    });
    return uuid;
}


Vue.component('ticket-details', {
    props: [
        'userId',
        'userName',
        'ticketId'
    ],
    data() {
        return {
            newMessage: '',
            viewers: [],
            ticket: {},
            ticketPending : {}
        }
    },
    computed: {
        isPosting: function () {
            return 'uuid' in this.ticketPending;
        }
    },
    mounted() {
        this.listen();
        this.getTicket();

        let ticket_index = this.findMessageIndex('id', 37);

        if (typeof(ticket_index) !== 'undefined') {
            this.ticket.messages[ticket_index].message = 'Hello world';
        } else {
            console.log('Cannot find message!');
        }
    },
    methods: {
        /**
         * Listen to Echo channels.
         */
        listen() {
            Echo.join('ticket.' + this.ticketId)
                .here((users) => {
                    this.viewers = users;
                })
                .joining((user) => {
                    this.viewers.push(user);
                })
                .leaving((user) => {
                    this.viewers = _.reject(this.viewers, function(viewer) {
                        return viewer.id == user.id;
                    });
                })
                .listen('TicketAddMessage', (e) => {
                    this.addMessage(e.ticketMessage);
                })
                .listen('TicketDeleteMessage', (e) => {
                    this.removeMessage(e.ticketMessage.id);
                });
        },

        /**
         * Get the ticket data.
         */
        getTicket() {
            if (typeof(ticket) !== 'undefined') {
                this.ticket = ticket;
            } else {
                // No data in inline-JS, get via Ajax.
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
            // One at a time, prevent spam and it should be unncessary.
            if ('uuid' in this.ticketPending) {
                return;
            }

            this.ticketPending = {
                uuid: generateUUID(),
                status: 0,
                message: this.newMessage,
                created_at: new Date(),
                updated_at: new Date(),
                user: {
                    id: this.userID,
                    name: this.userName
                }
            };

            this.addMessage(this.ticketPending);

            self = this;

            this.$http.post('/tickets/' + this.ticketId + '/message', { message: this.newMessage })
                .then(response => {
                    let ticket_id = self.findMessageIndex('uuid', self.ticketPending.uuid);

                    if (typeof(ticket_id) !== 'undefined') {
                        self.ticket.messages[ticket_id] = response.data
                    } else {
                        console.error('Cannot find pending ticket!!');
                    }

                    this.newMessage = '';
                    this.ticketPending = {};
                    return;
                });
        },

        addMessage(message) {
            this.ticket.messages.push(message);
        },

        findMessageIndex(field, value=null) {
            return _.findIndex(this.ticket.messages, function(message) {
                if (value == null) {
                    return field in message;
                }

                return message[field] == value;
            });
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
