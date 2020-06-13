/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');
window.$ = require("jquery");
window.Vue = require('vue');

import vuetify from './vuetify.admin' // path to vuetify export
import router from "./router.admin";

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// import vue-panzoom
import panZoom from 'vue-panzoom'

// install plugin
Vue.use(panZoom);

const app = new Vue({
    vuetify,
    router,
    el: '#app',
    data: {
        drawer: true,
        bar: true,
        items: [
            {icon: 'mdi-flag', text: 'Countries', resourceName: 'countries'},
            {icon: 'mdi-map', text: 'Cities', resourceName: 'cities'},
            {icon: 'mdi-airport', text: 'Airports', resourceName: 'airports'},
            {icon: 'mdi-routes', text: 'Flight Routes', resourceName: 'flight-routes'},
            {icon: 'mdi-seat-passenger', text: 'Flight Classes', resourceName: 'flight-classes'},
            {icon: 'mdi-airplane', text: 'Flights', resourceName: 'flights'},
            {icon: 'mdi-account-multiple', text: 'Users', resourceName: 'users'},
        ],
        snackbar: {
            show: false,
            message: "",
            color: "red",
        },
        deleteDialog: {
            show: false,
            message: "Are you sure you want to delete this resource?",
            title: "Delete Confirmation",
            loading: false,
            resourceId: null,
            resourceName: null,
        },
        user: null
    },
    methods: {
        deleteResource() {
            this.deleteDialog.loading = true;
            axios.delete(`/api/admin/${this.deleteDialog.resourceName}/${this.deleteDialog.resourceId}`).then(response => {
                this.deleteDialog.loading = false;
                this.deleteDialog.show = false;
            })
            this.$emit('resource-deleted')
        }
    },
    created() {
        // Add a request interceptor
        axios.interceptors.request.use(function (config) {
            let token = localStorage.getItem("uot-airlines.api-token");
            // if (token) {
            //     this.$router.push({
            //         path: '/admin/login'
            //     })
            // }
            config.headers = {
                ...config.headers,
                'Authorization': `Bearer ${token}`
            };
            // Do something before request is sent
            return config;
        }, function (error) {
            // Do something with request error
            return Promise.reject(error);
        });


        // Add a response interceptor
        axios.interceptors.response.use((response) => {
            // Any status code that lie within the range of 2xx cause this function to trigger
            // Do something with response data
            return response;
        }, (error) => {
            console.log(error)
            if (error.response.status === 401) {
                this.$router.push({
                    path: '/admin/login'
                })
            }
            // Any status codes that falls outside the range of 2xx cause this function to trigger
            // Do something with response error
            return Promise.reject(error);
        });
        try {
            this.user = JSON.parse(localStorage.getItem("uot-airlines.user"));
        } catch (e) {

        }
    }
});
