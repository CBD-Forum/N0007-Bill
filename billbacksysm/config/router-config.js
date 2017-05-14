import Login from '../src/components/login/loginNew.vue'
import Navigation from '../src/components/common/navigation.vue'
export default [
        {
            path: '/',
            redirect:'/index',
        },
        {
            path: '/index',
            component: Login,
            auth: true
        },
   
        {
            path: '/user',
            component: Navigation,
            children:[
                { path: '', redirect: 'account' },
                {
                    path: 'home',
                    component: function(resolve){
                        require(['../src/components/user/home.vue'],resolve)
                    }
                },
                
                {
                    path: 'billReview',
                    component: function(resolve){
                        require(['../src/components/user/billReview.vue'],resolve)
                    }
                },
                {
                    path: 'financialEntry',
                    component: function(resolve){
                        require(['../src/components/user/financialEntry.vue'],resolve)
                    }
                },
                {
                    path: 'financialExamine',
                    component: function(resolve){
                        require(['../src/components/user/financialExamine.vue'],resolve)
                    }
                },
                {
                    path: 'process',
                    component: function(resolve){
                        require(['../src/components/user/process.vue'],resolve)
                    }
                },
                {
                    path: 'accountTokens',
                    component: function(resolve){
                        require(['../src/components/user/accountTokens.vue'],resolve)
                    }
                },
                {
                    path: 'fundTransfer',
                    component: function(resolve){
                        require(['../src/components/user/fundtransfer.vue'],resolve)
                    }
                },
                {
                    path: 'fundwithdrawal',
                    component: function(resolve){
                        require(['../src/components/user/fundwithdrawal.vue'],resolve)
                    }
                },
                {
                    path: 'enterInfo',
                    component: function(resolve){
                        require(['../src/components/user/enterInfo.vue'],resolve)
                    }
                },
                {
                    path: 'managerInfo',
                    component: function(resolve){
                        require(['../src/components/user/managerInfo.vue'],resolve)
                    }
                },
                // {
                //     path: 'rights',
                //     component: function(resolve){
                //         require(['../src/components/user/rights.vue'],resolve)
                //     }
                // },
                {
                    path: 'assets',
                    component: function(resolve){
                        require(['../src/components/user/assets.vue'],resolve)
                    }
                },
                {
                    path: 'thawCode',
                    component: function(resolve){
                        require(['../src/components/user/thawCode.vue'],resolve)
                    }
                },
                {
                    path: 'enterCode',
                    component: function(resolve){
                        require(['../src/components/user/enterCode.vue'],resolve)
                    }
                },
                {
                    path: 'systemConfig',
                    component: function(resolve){
                        require(['../src/components/user/systemConfig.vue'],resolve)
                    }
                },
            ]
        }

    ]
