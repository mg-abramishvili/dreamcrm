// require('./bootstrap');

import Vue from 'vue'

window.moment = require('moment');
import moment from 'moment'

Vue.prototype.$moment = moment;
moment.locale('ru');

// import parse from 'date-fns/parse'
// import parseISO from 'date-fns/parseISO'
// import format from 'date-fns/format'
// import ru from 'date-fns/locale/ru'

import axios from 'axios'
window.axios = require('axios');
//window.axios.defaults.withCredentials = true;

import App from './App.vue';
import VueRouter from 'vue-router';
import {routes} from './routes';

Vue.use(VueRouter);

Vue.filter('currency', function (value) {
    if (!value) return '0'
    return parseInt(value).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") 
})
Vue.filter('truncate', function (value) {
    if (!value) return ''
    if (value.length > 100) {
        return value.substring(0, 100) + '...'
    }
    return value
})
Vue.filter('date', function (value) {
    if (!value) return ''
    return moment.utc(value).utcOffset(3).format('DD.MM.YYYY')
})
Vue.filter('dateMini', function (value) {
    if (!value) return ''
    return moment.utc(value).utcOffset(3).format('DD.MM')
})
Vue.filter('dateFull', function (value) {
    if (!value) return ''
    return moment.utc(value).utcOffset(3).format('dddd, D MMMM')
})
Vue.filter('time', function (value) {
    if (!value) return ''
    return moment.utc(value).utcOffset(3).format('H:mm')
})

const router = new VueRouter({
    mode: 'history',
    routes: routes,
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
});

import Loader from './components/Loader'
Vue.component('loader', Loader)

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

const app = new Vue({
    el: '#app',
    router: router,
    render: h => h(App),
});