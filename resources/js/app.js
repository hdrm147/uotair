/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');

import vuetify from './vuetify' // path to vuetify export
import router from "./router";

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    vuetify,
    router,
    el: '#app',
    data: {
        barButtons: [
            {
                title: "Home",
                link: "/",
                disabled: false,
            },
            {
                title: "Check Tickets",
                link: "/check",
                disabled: false,
            },
            {
                title: "About",
                link: "/about",
                disabled: true,
            },
            {
                title: "Contact",
                link: "/contact",
                disabled: true,
            }
        ]
    }
});
