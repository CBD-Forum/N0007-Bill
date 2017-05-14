import Home from '../src/components/home.vue'
import block from '../src/components/blockDetail.vue'
import address from '../src/components/address.vue'
import tradeHash from '../src/components/tradeHash.vue'

export default [
	{
        path: '/',
        redirect: '/index'
    }, 
    { 
    	path: '/index', 
    	component: Home 
    },
    { 
    	path: '/tradeHash/:tid',
        name:'trade',
    	component: tradeHash
    },
    {
    	path:'/blockDetail/:bid',
        name: 'block',
    	component:block
    },
    {
    	path:'/addressDetail/:aid',
        name:'address',
    	component:address
    }
]
