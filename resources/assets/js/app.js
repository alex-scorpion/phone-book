window._     = require('lodash');
window.axios = require('axios');

import Vue from 'vue';
import VueNotifications from './plugins/VueNotifiations/main';
import Vuetify from 'vuetify';
import VueRouter from 'vue-router';
import {routes} from './routes';
import App from './layouts/App.vue';

window.helpers = {
    newHash: function() {
        return Math.random().toString(36).substr(2, 9);
    },
    isEmptyObject: function(obj) {
        return !Object.keys(obj).length;
    },
    cloneObject: function(obj) {
        if (obj instanceof Object || obj instanceof Array) {
            return JSON.parse(JSON.stringify(obj));
        }
        return obj;
    }
};

Vue.config.debug = false;
Vue.use(VueNotifications);
Vue.component('VueNotifications', VueNotifications);
Vue.use(Vuetify);
Vue.use(VueRouter);
Vue.prototype.$eventBus = new Vue();

const router = new VueRouter({
    base: '/',
    mode: 'history',
    routes
});

window.axios = window.axios.create({
    baseURL: "/api",
    headers: {'X-Requested-With': 'XMLHttpRequest'},
    maxContentLength: 2000,
});

new Vue({
    el: '#app',
    router,
    render: h => h(App)
});
