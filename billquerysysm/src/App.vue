<template>
    <div id="app">
        <div class="head">
            <div class="head-content">
                <router-link to="/index">
                    <img src="./assets/images/logo1.png" alt="">
                </router-link>
                <input type="text" class="search" v-model="str" v-on:keyup.enter="submit" placeholder="请输入交易ID、地址、区块高度">
            </div>
        </div>
        <div class="content">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import { postTradeList, postTradeDetail, postblockDetail} from './assets/js/api';
export default {
    name: 'app',
    data() {
        return {
            str: '',
        }
    },
    components: {},
    methods: {
        submit: function() {
            let str = this.str;
            if (str == '') {
                this.$notify({
                    title: '提示',
                    message: '请输入交易ID、地址、区块高度',
                    type: 'warning'
                });
                return;
            }
            this.getHeight();
        },
        getTradeHash: function() {
            postTradeDetail({instructionid: this.str}).then(data => {
                if(data.code == 200) {
                    this.$router.push({
                        name: 'trade',
                        params: {
                            'tid': this.str
                        }
                    });
                }else {
                    this.$notify({
                        title: '提示',
                        message: '请输入正确信息',
                        type: 'warning'
                    });
                }
            });
        },
        getAddress: function() {
            postTradeList({address: this.str}).then(data => {
                if(data.code == 200){
                    this.$router.push({
                        name: 'address',
                        params: {
                            'aid': this.str
                        }
                    });
                }else{
                    this.getTradeHash();
                }
            });
        },
        getHeight: function() {
            postblockDetail({height : this.str}).then(data => {
                if(data.code == 200){
                    this.$router.push({
                        name: 'block',
                        params: {
                            'bid': this.str
                        }
                    });
                }else{
                    this.getTradeHash();
                    // this.getAddress();
                }
            });
        }
    }
}
</script>
<style>
#app {}

.el-pager li.active {
    border-color: #2fa5e6 !important;
    background-color: #2fa5e6 !important;
    color: #fff;
    cursor: default;
}

.notable-data {
    width: 100%;
    text-align: center;
    padding: 40px 0;
    border: 1px solid #eee;
    border-top: 0;
    font-size: 14px;
}

.block {
    margin-top: 20px;
}

a {
    color: #39aae7 !important;
}

.head {
    height: 50px;
    line-height: 50px;
    position: relative;
    min-width: 1200px;
    background: -moz-linear-gradient(top, #7dc6ef 0%, #2fa4e7 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #7dc6ef), color-stop(100%, #2fa4e7));
    background: -webkit-linear-gradient(top, #7dc6ef 0%, #2fa4e7 100%);
    background: -o-linear-gradient(top, #7dc6ef 0%, #2fa4e7 100%);
    background: -ms-linear-gradient(top, #7dc6ef 0%, #2fa4e7 100%);
    background: linear-gradient(to bottom, #7dc6ef 0%, #2fa4e7 100%);
}

.head img {
    width: 150px;
    height: 40px;
    margin-top: 5px;
}

.content {
    width: 1200px;
    margin: 0 auto;
    padding-top: 20px;
    padding-bottom: 20px;
}

.head-content {
    height: 100%;
    width: 1200px;
    margin: 0 auto;
    position: relative;
}

input.search {
    height: 30px;
    width: 420px;
    border: 1px solid #fff;
    position: absolute;
    top: 50%;
    right: 0;
    margin-top: -15px;
    border-radius: 30px;
    background: #55b5eb;
    padding-left: 30px;
    color: #fff !important;
    background: url(./assets/images/icon.png) no-repeat 380px 50%;
}

::-webkit-input-placeholder {
    /* WebKit browsers */
    color: #fff;
}

:-moz-placeholder {
    /* Mozilla Firefox 4 to 18 */
    color: #fff;
}

::-moz-placeholder {
    /* Mozilla Firefox 19+ */
    color: #fff;
}

:-ms-input-placeholder {
    /* Internet Explorer 10+ */
    color: #fff;
}

.arrow {
    width: 20px;
    /*text-align: center;*/
    background: url(./assets/images/arrow.png) no-repeat 50% 50%;
}


/*.arrow span{
    display: block;
    height: 100%;
    width: 100%;
   background: url(./assets/images/arrow.png) no-repeat 50% 50%; 
}*/
</style>
