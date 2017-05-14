<template>
  <div>
    <el-breadcrumb separator="/" style="margin-bottom:20px;">
      <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>交易详情</el-breadcrumb-item>
    </el-breadcrumb>
    <div class="tradeHash">
      <div class="title">
        <span>交易哈希</span>
        <p>{{tradehass}}</p>
      </div>
      <div class="content">
        <div class="row">
          <label for="">区块高度</label>
          <span>
            <router-link :to="{ name: 'block', params: { 'bid': tranDetail.height }}">{{tranDetail.height}}</router-link>
          </span>
        </div>
        <div class="row">
          <label for="">区块哈希</label>
          <span><router-link :to="{ name: 'block', params: { 'bid': tranDetail.height }}">{{tranDetail.instructionid}}</router-link></span>
        </div>
        <!-- <div class="row">
        <label for="">来源</label>
        <span class="blue">
          <router-link :to="{ name: 'address', params: { aid: tranDetail.tfrom }}">{{tranDetail.tfrom}}</router-link>
        </span>
      </div>
      <div class="row">
        <label for="">去至</label>
        <span class="blue">
          <router-link :to="{ name: 'address', params: { aid: tranDetail.towhere }}">{{tranDetail.towhere}}</router-link>
        </span>
      </div>
      -->
      <div class="row" v-for="e in input">
        <label v-if="e.name == '类型'" for="">录入信息</label>
        <label v-else for=""></label>
        <span class="wspan">{{e.name}}</span>
        <span>{{e.value}}</span>
      </div>
      <div class="row" v-for="e in logInfo">
        <label v-if="e.name == '类型'" for="">时间记录</label>
        <label v-else for=""></label>
        <span class="wspan">{{e.name}}</span>
        <span>{{e.value}}</span>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import {postTradeDetail} from '../assets/js/api';
export default {
    name: 'tradeHash',
    data() {
        return {
            tranDetail:{
                tfrom:0,
                towhere:0,
                blocknumber:0 // --- 为了刷新页面的时候还没有tranDetail.tfrom报错，所以提前设置
            },
            input:{

            },
            logInfo:{},
            log:-1,
            tradeHash:this.$route.params.tid,
            tradehass:'',
        }
    },
    mounted: function() {
        this.$nextTick(function() {
            this.getTradeDetail();
        })
    },
    watch: {
        '$route' (to, from) {
            this.tradeHash = this.$route.params.tid;
            this.getTradeDetail();
        }
    },
    methods:{
        getTradeDetail:function() {
            postTradeDetail({instructionid: this.$route.params.tid}).then(data => {
                this.tranDetail =  data;
                this.input = data.input;
                this.logInfo = data.logs;
                this.tradehass=data.hash;
                data.logs.错误信息 != undefined ? this.log = 0 : this.log = 1;
            });
        },
    }
}
</script>

<style>
.tradeHash{
    background: #fff;
    color: #333;
}
    .row .wspan{
        display: inline-block;
        width: 200px;
    }
    .title{
        text-align: center;
        font-size: 20px;
    }
    .title span{
        line-height: 55px;
    }
    .tradeHash .content{
        padding-left:200px;
        font-size: 14px;
        padding-bottom: 80px;
    }
    .tradeHash .content .row{
        margin: 10px 0;
    }
    .tradeHash .content label{
        color:#9e9e9e;
        display: inline-block;
        width: 136px;
    }
    span.blue{
        color:#2ba7e4;
    }
</style>