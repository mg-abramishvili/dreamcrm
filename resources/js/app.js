// require('./bootstrap');

import Vue from 'vue'

window.moment = require('moment');
import moment from 'moment'

Vue.prototype.$moment = moment;
moment.locale('ru');

import parse from 'date-fns/parse'
import parseISO from 'date-fns/parseISO'
import format from 'date-fns/format'
import ru from 'date-fns/locale/ru'

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
Vue.filter('dateMini', function (value) {
    if (!value) return ''
    const date = parse(value, 'yyyy-MM-dd')
    return format(date, "dd.MM")
})
Vue.filter('date', function (value) {
    if (!value) return ''
    const date = parseISO(value)
    return format(date, "dd.MM.yyyy")
})
Vue.filter('time', function (value) {
    if (!value) return ''
    return moment.tz(value, 'Europe/Moscow').format('H:mm')
})
Vue.filter('formatDateShort', function (value) {
    if (!value) return ''
    return moment(value).format('DD.MM')
})
Vue.filter('formatDateLong', function (value) {
    if (!value) return ''
    return moment(value).format('dddd, D MMMM')
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