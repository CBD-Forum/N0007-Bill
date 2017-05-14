import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import Hello from 'components/Hello'
import Index from 'views/index'
// include header and navigation
import Main from 'components/main'

export default new Router({
  routes: [
    {
      path: '/',
      redirect:'/index',
    },
    {
      path: '/index',
      component:Index
    },
    {
      path: '/user',
      component: Main,
      children: [
        {
          path:'tradePassword',
          component: function(resolve){
            require(['../views/tradePassword.vue'], resolve)
          }
        },
        {
          path:'fcpIndex',
          component: function(resolve){
            require(['../views/fcp/fcpIndex.vue'], resolve)
          }
        },
        {
          path:'enterprise',
          component: function(resolve){
            // require(['../views/enterAuth.vue'], resolve)
            require(['../views/enterprise.vue'], resolve)
          }
        },
        {
          path:'market',
          component: function(resolve){
            require(['../views/market.vue'], resolve)
          }
        },
        {
          path:'MannageMoney',
          component: function(resolve){
            require(['../views/MannageMoney.vue'], resolve)
          }
        },
        {
          path:'auth',
          component: function(resolve){
            require(['../views/auth.vue'], resolve)
          }
        },
        {
          path:'mcrIndex',
          component: function(resolve){
            require(['../views/mcr/mcrIndex.vue'], resolve)
          }
        },
        {
          path:'fcrIndex',
          component: function(resolve){
            require(['../views/fcr/fcrIndex.vue'], resolve)
          }
        },
        {
          path:'fcrIndex2',
          component: function(resolve){
            require(['../views/fcr/fcrIndex2.vue'], resolve)
          }
        },
        {
          path:'examine',
          component:function(resolve){
            require(['../views/examine/examine.vue'], resolve)
          }
        },
        {
          path:'examine',
          component:function(resolve){
            require(['../views/examine/examine.vue'], resolve)
          }
        },
        {
          path:'billRecord',
          component:function(resolve){
            require(['../views/billRecord.vue'], resolve)
          }
        },
        {
          path:'maintainRecord',
          component:function(resolve){
            require(['../views/maintainRecord.vue'], resolve)
          }
        },
        {
          path:'tradeRecord',
          component:function(resolve){
            require(['../views/tradeRecord.vue'], resolve)
          }
        },
        {
          path:'fcpAccount',
          component:function(resolve){
            require(['../views/fcp/fcpAccount.vue'], resolve)
          }
        },
        {
          path:'password',
          component:function(resolve){
            require(['../views/password.vue'], resolve)
          }
        }
      ]
    }
  ]
})
