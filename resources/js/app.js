/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import { Form, HasError, AlertError} from 'vform';
import {ServerTable, ClientTable, Event} from 'vue-tables-2';
Vue.use(ServerTable, {}, false, 'bootstrap3');
Vue.use(ClientTable, {}, false, 'bootstrap3');
Vue.use(Event);

import VueProgressBar from 'vue-progressbar';
Vue.use(VueProgressBar, {
    color: '#bffaf3',
    failedColor: 'red',
    thickness: '5px',
    transition: {
      speed: '0.2s',
      opacity: '0.6s',
      termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
});

window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

import swal from 'sweetalert2';
window.swal = swal;
const toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});
window.toast = toast;

const moment = require('moment')
require('moment/locale/id')
Vue.filter('myDate', function(created){
	return moment(created).format('dddd, Do MMMM YYYY');
  // return moment().to(data);
});

import money from 'v-money'
Vue.use(money, {precision:0 })
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Router
import VueRouter from 'vue-router'
window.Fire = new Vue();
Vue.use(VueRouter)
let routes = [

    { path: '/home', component: require('./components/Home.vue').default},

    { path: '/master-data/user', component: require('./components/master/User.vue').default},
    { path: '/master-data/member', component: require('./components/master/Member.vue').default},
    { path: '/master-data/buku', component: require('./components/master/Book.vue').default},

    { path: '/transaksi/pinjam-buku', component: require('./components/trans/Pinjam_buku.vue').default},
    { path: '/transaksi/pengembalian-buku', component: require('./components/trans/Kembalikan_buku.vue').default},
    { path: '/transaksi/laporan-pinjam', component: require('./components/trans/Lap_pinjam.vue').default},

    { path: '/laporan/buku', component: require('./components/Laporan_buku.vue').default}


]

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('validation-errors', require('./components/Validation.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 const router = new VueRouter({
   	mode: 'history',
   	routes
   })

   const app = new Vue({
      el: '#app',
      router
  });
