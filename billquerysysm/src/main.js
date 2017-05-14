// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import VueRouter from 'vue-router'
import RouterConfig from "../config/router-config"
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
require('./assets/css/base.css');

Vue.use(VueRouter);
Vue.use(ElementUI);

// 注册全局过滤器
import filters from './assets/js/filters.js';
Object.keys(filters).forEach((k) => Vue.filter(k, filters[k]));

var router = new VueRouter({
    routes:RouterConfig,
})

/* eslint-disable no-new */
new Vue({
    router: router,
    el: '#app',
    template: '<App/>',
    components: { App }
})

// hot-serve
window.router = router
