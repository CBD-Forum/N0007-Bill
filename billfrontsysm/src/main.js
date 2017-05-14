// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import 'babel-polyfill'
import Vue from 'vue'
import App from './App'
import router from './router'
import ElementUI from 'element-ui'
import AwesomeSwiper from 'vue-awesome-swiper'
import '../theme/index.css'

import('./assets/css/base.css');
import('./assets/font/iconfont.css');

Vue.use(ElementUI)
Vue.use(AwesomeSwiper);
/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App }
})
