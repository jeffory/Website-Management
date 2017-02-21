<!-- Can't put the template in here because it uses outside variables -->

<script>
    export default {
        components: {
            
        },
        props: [
            'userId',
            'userName',
            'data'
        ],
        data() {
            return {
                attachments: [],
                ticket: this.data,
                newMessage: '',
                viewers: [],
                ticketPending : {}
            }
        },
        computed: {
            isPosting: function () {
                return 'uuid' in this.ticketPending;
            }
        },
        mounted() {
            this.getTicket();
            this.listen();
        },
        methods: {
            /**
             * Listen to Echo channels.
             */
            listen() {
                Echo.join('ticket.' + this.ticket.id)
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
                    this.$http.get('/tickets/' + this.ticket.id)
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
                    uuid: this.generateUUID(),
                    status: 0,
                    message: this.newMessage,
                    created_at: new Date(),
                    updated_at: new Date(),
                    file: this.attachments,
                    user: {
                        id: this.userID,
                        name: this.userName
                    }
                };

                this.addMessage(this.ticketPending);

                self = this;

                let postData = {
                    message: this.newMessage,
                    ticket_files: this.attachments
                };

                this.$http.post('/client-area/tickets/' + this.ticket.id + '/message', postData)
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
                this.$http.delete('/client-area/tickets/' + this.ticket.id)
                    .then(response => {
                        window.location = "/tickets";
                    });
            },

            /**
             * Delete a ticket.
             */
            deleteMessage(ticketMessageID) {
                this.$http.post('/client-area/tickets/' + this.ticket.id + '/message/' + ticketMessageID, {  _method: 'DELETE' });
                this.removeMessage(ticketMessageID);
            },

            generateUUID() {
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
            },

            addAttachment(attachment) {
                this.attachments.push({
                    name: attachment.name,
                    token: attachment.token
                });
            }
        },
        watch: {

        }
    }
</script>

