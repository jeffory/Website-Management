
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// var Turbolinks = require("turbolinks");
// Turbolinks.start();

import Dropzone from 'vue2-dropzone';
import VueTimeago from 'vue-timeago';

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

Vue.component('ticket-details', require('./components/TicketDetails.vue'));

Vue.component('tabs', require('./components/Tabs.vue'));
Vue.component('tab', require('./components/Tab.vue'));


var app = new Vue({
    data: {
        ticket: ''
    },
    el: '#app',
});

