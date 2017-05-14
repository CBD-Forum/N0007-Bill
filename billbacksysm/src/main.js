import Vue from 'vue'
import VueRouter from 'vue-router'
import RouterConfig from "../config/router-config"
import ApiConfig from "../config/api-config"
import App from './App'
import AwesomeSwiper from 'vue-awesome-swiper'
import Element from 'element-ui'
import 'element-ui/lib/theme-default/index.css'

require('./assets/font/iconfont.css');
require('./assets/css/base.css');
// require('./assets/tenderjs/long.min.js');
// require('./assets/tenderjs/nacl.min.js');
// require('./assets/tenderjs/sha3.min.js');
// require('./assets/tenderjs/tendermint.js');
// require('./assets/tenderjs/browersign.js');
import filters from './assets/js/filters.js';
Object.keys(filters).forEach((k) => Vue.filter(k, filters[k]));

Vue.use(Element);
Vue.use(VueRouter);
Vue.use(AwesomeSwiper);

Vue.config.silent = true;

var router = new VueRouter({
    linkActiveClass: "active",
    routes : RouterConfig
})


new Vue({
  router,
  render: h => h(App)
}).$mount('#app')

// hot-serve
window.router = router

Vue.prototype.extendApi = ApiConfig;
// Vue.prototype.bus = new Vue();
