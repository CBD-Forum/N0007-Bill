<template>
  <div class='address-box'>
    <el-breadcrumb separator="/" style="margin-bottom:20px;">
      <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>地址</el-breadcrumb-item>
    </el-breadcrumb>
    <div class='block-msg'>
      <h1>
        <span>地址</span>
        <span class='block-height'>{{address}}</span>
      </h1>
      <div class='money-box money-box1'>
        <span>人民币余额</span>
        <span>{{rdata.rmb / 100}}元</span>
      </div>
      <div class='money-box money-box2'>
        <span>票据金额</span>
        <span>{{rdata.bill / 100}}元</span>
      </div>
    </div>
    <div class='block-business'>
      <h2>
        <span>交易</span>
        <span class='tip1'>此地址下的交易</span>
      </h2>
      <div class='btn-box'>
        <button :class="{active:tab1==0}" @click="btnswitch(0)">全部</button>
        <button :class="{active:tab1==1}" @click="btnswitch(1)">录入</button>
        <button :class="{active:tab1==2}" @click="btnswitch(2)">挂牌</button>
        <button :class="{active:tab1==3}" @click="btnswitch(3)">摘牌</button>
        <button :class="{active:tab1==4}" @click="btnswitch(4)">撤单</button>
        <button :class="{active:tab1==5}" @click="btnswitch(5)">确认转让</button>
        <button :class="{active:tab1==6}" @click="btnswitch(6)">确认收票</button>
      </div>
      <div class='address-table'>
        <table class=''>
          <thead>
            <tr class='ttop'>
              <th>交易哈希</th>
              <th>所属区块</th>
              <th>交易类型</th>
              <th>来源</th>
              <th></th>
              <th>去至</th>
              <th>时间</th>
            </tr>
          </thead>
          <tbody>
            <tr class='ttbody' v-for="e in tradeList">
              <td class='link1'>
                <router-link :to="{ name: 'trade', params: { tid: e.hash }}">{{e.hash|filterHash}}</router-link>
              </td>
              <td>{{e.blocknumber}}</td>
              <td>{{e.func}}</td>
              <td class='link1 '>
                <p>
                  <router-link :to="{ name: 'address', params: { aid: e.tfrom }}">{{e.tfrom_name}}</router-link>
                </p>
                <p>
                  <router-link :to="{ name: 'address', params: { aid: e.tfrom }}">{{e.tfrom | filterHash}}</router-link>
                </p>
              </td>
              <td class='arrow'></td>
              <td class='link1 '>
                <p>
                  <router-link :to="{ name: 'address', params: { aid: e.towhere }}">{{e.towhere_name}}</router-link>
                </p>
                <p>
                  <router-link :to="{ name: 'address', params: { aid: e.towhere }}">{{e.towhere|filterHash}}</router-link>
                </p>
              </td>
              <td>{{e.time | filterTime('Y-M-D h:m:s')}}</td>
            </tr>
          </tbody>
        </table>
        <div class="notable-data" v-if="noDateShow">暂无数据</div>
      </div>
      <div class="block" v-show="blockShow">
        <el-pagination
          :current-page="page.page"
          :page-size="page.pagesize"
          layout="total, prev, pager, next"
          :total="page.total"
           @current-change="handleCurrentChange"></el-pagination>
      </div>
    </div>
  </div>
</template>

<script>
import {postTradeList} from '../assets/js/api';
export  default{
  data(){
    return{
      tab1:0,
            tradeList:[],
            page:{
                page:1,
                pagesize:10,
                total:0
            },
            blockShow:false,
            type:'all',
            noDateShow:false,
            address:this.$route.params.aid,
            rdata:{
                rmb:'--',
                bill:'--'
            }
    }
  },
    watch: {
        '$route' (to, from) {
            this.getTradeList();
            this.address = this.$route.params.aid;
        }
    },
    mounted: function() {
        this.$nextTick(function() {
            this.getTradeList();
        })
    },
  methods:{
        handleCurrentChange:function(val) {
            this.page.page = val;
            this.getTradeList();
        },
    btnswitch:function(val){
      this.tab1=val;
            let map = {
                '0' : 'all',
                '1' : 'createBill',
                '2' : 'sellBill',
                '3' : 'investBill',
                '4' : 'cancelSelling',
                '5' : 'confirmSending',
                '6' : 'confirmReceiving',
            }
            this.type = map[val];
            this.page.page = 1;
            this.getTradeList();
    },
        getTradeList:function(){
            let body = {
                type:this.type,
                address: this.$route.params.aid,
                page: this.page.page,
                pagesize:this.page.pagesize
            };
            postTradeList(body).then(data => {
                data.count > 10 ? this.blockShow = true : this.blockShow = false;
                data.count == 0 ? this.noDateShow = true : this.noDateShow = false;
                this.page.total = Number(data.count);
                this.tradeList = data.data;
                this.rdata = data.rdata;
            });
        }
  }
}
</script>
<style>
.address-box{
  .block-msg{
    padding:0 20px 20px;
    background-color: #fff; 
    overflow: hidden;
  }
  .block-msg h1{
    font-size: 24px;
    height: 60px;
    line-height: 60px;    
  }
  .block-height{
    margin-left: 20px;
  }
  .word1{
    width: 96px;
    display: inline-block;
    text-align: right;
  }
  .block-msg p{
    margin-top: 10px;
    font-size: 16px;
  }
  .block-msg p span:nth-child(1){ 
    color: #808080;
  }
  .block-msg p span:nth-child(2){
    
    color: #333;
    margin-left: 20px;  
  }
  .block-business{
    margin-top: 20px;
    background-color: #fff;
    padding:0 20px 20px ;
  }
  .block-business h2{
    font-size: 24px;
    float: left;
    height: 60px;
    line-height: 60px;
  }
  .btn-box{
    float: right;
    height: 60px;
    line-height: 60px;

    button{
      border:1px solid #2fa5e6;
      background-color: transparent;
      font-size: 12px;
      color: #2fa5e6;
      padding: 2px 0;
      width: 80px;
      text-align: center;
      border-radius: 4px;
      cursor: pointer;
    }
    .active{
      background-color: #2fa5e6;
      color: #fff;
    }
  }
  
  .tip1{
    font-size: 16px;
    color: #808080;
    margin-left: 20px;
  }

  .link1{
    color: #39aae7;
    cursor: pointer;
  }
  .arrow{
    color: #39aae7;
  }
  .money-box{
    width: 570px;
    padding: 10px 40px;
    color: #fff;
    font-size: 16px;
    span:nth-child(2){
      float:right;
    };
  }
  .money-box1{
    background-color: #1ab2ae;
    float: left;
  }
  .money-box2{
    background-color:#2fa5e6;
    float:right;
  }
}

.address-table {
        clear: both;
        width: 100%;
        overflow-x: auto;
        table {
            width: 100%;
            thead {
                width: 100%;
                .ttop {
                    width: 100%;
                    th {
                        height: 40px;
                        text-align: center;
                        line-height: 40px;
                        color:#4392ac;
                        font-size: 14px;
                        background: #d1effa;
                        
                    }
                    .canclick{
                        cursor: pointer;
                    }
                    th:nth-child(3) {
                    }
                }
            }
            tbody {
                width: 100%;
                .ttbody {
                    width: 100%;
                    &:hover {
                        background: #f5f5f5;
                    }
                    ;
                    td {
                        padding: 0!important;
                        height: 55px;
                        text-align: center;
                        font-size: 14px;
                    }
                    td:nth-child(3) {
                    }
                }
            }
        }
    }
</style>