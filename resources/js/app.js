// require('./bootstrap');

import Vue from 'vue'

window.moment = require('moment');
import moment from 'moment'

Vue.prototype.$moment = moment;
moment.locale('ru');

import axios from 'axios'
window.axios = require('axios');
//window.axios.defaults.withCredentials = true;

import App from './App.vue';
import VueRouter from 'vue-router';
import {routes} from './routes';

Vue.use(VueRouter);

Vue.filter('currency', function (value) {
    if (!value) return ''
    return parseInt(value).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") 
})
Vue.filter('formatDate', function (value) {
    if (!value) return ''
    return moment(value).utcOffset(180).format('DD.MM.YYYY')
})
Vue.filter('formatDateShort', function (value) {
    if (!value) return ''
    return moment(value).utcOffset(180).format('DD.MM')
})
Vue.filter('formatDateTimeOnly', function (value) {
    if (!value) return ''
    return moment(value).utcOffset(180).format('H:mm')
})
Vue.filter('formatDateLong', function (value) {
    if (!value) return ''
    return moment(value).utcOffset(180).format('dddd, D MMMM')
})

const router = new VueRouter({
    mode: 'history',
    routes: routes,
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
});

const app = new Vue({
    el: '#app',
    router: router,
    render: h => h(App),
});