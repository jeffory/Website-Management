<!-- Can't put the template in here because it uses outside variables -->

<script>
    export default {
        components: {
            
        },
        props: [
            'userId',
            'username',
            'data'
        ],
        data() {
            return {
                attachments: [],
                eventbus: this.$parent.eventbus,
                // Laravel echo instance
                echo: null,
                loaded: false,
                new_message: '',
                new_status: null,
                ticket: this.data,
                ticket_pending : {},
                users_typing: [],
                user_typing_timer: null,
                viewers: [],
            }
        },
        computed: {
            isPosting: function () {
                return 'uuid' in this.ticket_pending;
            }
        },
        mounted() {
            this.getTicket();
            this.listen();
            
            this.loaded = true;
        },
        methods: {
            /**
             * Listen to Echo channels.
             */
            listen() {
                this.echo = Echo.join('ticket.' + this.ticket.id)
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
                    })
                    .listenForWhisper('TicketUserTyping', _.throttle((e) => {
                        let typing_timeout = 12;
                        let user_typing = _.find(this.users_typing, function(user) {
                            return user.name == e.user;
                        });

                        if (user_typing) {
                            user_typing.expires = new Date(new Date().getTime() + (typing_timeout * 1000));
                        } else {
                            this.users_typing.push({
                                name: e.user,
                                expires: new Date(new Date().getTime() + (typing_timeout * 1000))
                            });

                            // Only try start polling timer on a new user typing...
                            this.pollUserTyping();
                        }
                    }));
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
                // One at a time, prevent spamming and it should be unncessary.
                if ('uuid' in this.ticket_pending) {
                    return;
                }

                console.log(this.new_status);

                this.ticket_pending = {
                    uuid: this.generateUUID(),
                    status_change: this.new_status,
                    message: this.new_message,
                    created_at: new Date(),
                    updated_at: new Date(),
                    file: [],
                    user: {
                        id: this.userID,
                        name: this.username
                    }
                };

                this.addMessage(this.ticket_pending);

                self = this;

                let postData = {
                    message: this.new_message,
                    status_change: this.new_status,
                    ticket_files: this.attachments
                };

                this.$http.post('/client-area/tickets/' + this.ticket.id + '/message', postData)
                    .then(response => {
                        let ticket_id = self.findMessageIndex('uuid', self.ticket_pending.uuid);

                        if (typeof(ticket_id) !== 'undefined') {
                            self.ticket.messages[ticket_id] = response.data;

                            if (response.data.status_change !== null) {
                                this.ticket.status = response.data.status_change;
                            }
                        } else {
                            console.error('Cannot find pending ticket!!');
                        }

                        self.new_message = '';
                        self.new_status = null;
                        self.ticket_pending = {};

                        eventbus.$emit('clearAttachments');
                        return;
                    });
            },

            addMessage(message) {
                this.ticket.messages.push(message);

                if (message.status_change !== null) {
                    this.ticket.status = message.status_change;
                }
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
             * Delete a ticket message.
             */
            deleteMessage(ticketMessageID) {
                this.$http.post('/client-area/tickets/' + this.ticket.id + '/message/' + ticketMessageID, {  _method: 'DELETE' });
                this.removeMessage(ticketMessageID);
            },

            generateUUID() {
                var d = new Date().getTime();
                if (window.performance && typeof window.performance.now === "function") {
                    d += performance.now(); //use high-precision timer if available
                }
                var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = (d + Math.random() * 16) % 16 | 0;
                    d = Math.floor(d/16);
                    return (c == 'x' ? r : (r&0x3|0x8)).toString(16);
                });
                return uuid;
            },

            isUserTyping(name) {
                return _.find(this.users_typing, (user) => {
                    return name == user.name;
                });
            },

            pollUserTyping() {
                this.user_typing_timer = setInterval(() => {
                    // Kill the timer if no one's typing
                    if (this.users_typing.length == 0) {
                        clearInterval(this.user_typing_timer);
                    }

                    this.users_typing = _.reject(this.users_typing, function(user_typing) {
                        return (new Date()).getTime() >= user_typing.expires.getTime();
                    });
                }, 500);
            },

            setUserTyping() {
                this.echo.whisper('TicketUserTyping', {
                    user: this.username
                });
            },

            addAttachment(attachment) {
                this.attachments.push({
                    name: attachment.name,
                    token: attachment.token
                });
            },

            removeAttachment(attachment) {
                
            },
        }
    }
</script>
