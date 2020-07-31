/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
import moment from 'moment';

Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);

Vue.component('schedule-notifications-component', require('./components/ScheduleNotificationsComponent.vue').default);
Vue.component('guests-component', require('./components/GuestsComponent.vue').default);
Vue.component('event-details-component', require('./components/EventDetailsComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.filter('formatDate', function (value) {
    if (value) {
        return moment(String(value)).format('MM/DD/YYYY hh:mm')
    }
});

const app = new Vue({
    el: '#app',
});
