// import router from './routes';

require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import {routes} from './routes';
import StoreData from './store';
import MainApp from './components/MainApp.vue';
import {initialize} from './helpers/general';
import * as VueGoogleMaps from 'vue2-google-maps';

import BootstrapVue from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue);

Vue.use(VueRouter);
Vue.use(Vuex);

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyAIAkiam90N9R-_Nh72fL6MpGJpKUBDWgQ',
    libraries: 'places,drawing,visualization',
  },
});


// Vue.component('GRecaptcha', require('./components/GRecaptcha'));
Vue.component('flash', require('./components/flash'));

// Vue.component('BaseMap', require('./components/BaseMap'));
// Vue.component('GoogleMap', require('./components/GoogleMap'));

Vue.component('DatePicker', require('./components/DatePicker'));
Vue.component('TimePicker', require('./components/TimePicker'));


const store = new Vuex.Store(StoreData);

const router = new VueRouter({
    routes,
    mode: 'history'
});

initialize(store, router);

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        MainApp
    }
});

