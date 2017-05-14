<template>
  <div class="fcpIndex">
    <div class="clearfix">
      <div class="fcpi__box fl">
        <div class="fcpi__row clearfix">
          <div class="fcpi__rowL fl cellTitle">
            <div class="fcpi__cell" style="padding-left:20px;">
              人民币资产 <span class="spanblod">{{member.total}}元</span>
            </div>
          </div>
          <div class="fcpi__rowR fl cellTitle">
            <div class="fcpi__tiny fl">
              <div tag="div" class="goexamine tiny__box" @click="showdialogtype('chzhi')">充值</div>
            </div>
            <div class="fcpi__tiny fl">
              <div tag="div" class="goexamine tiny__box" @click="showdialogtype('tikuan')">提款</div>
            </div>
          </div>
        </div>
        <div class="fcpi__row clearfix">
          <div class="fcpi__rowL fl cellTitle">
            <i class="pub_iconOran iconfont icon-daishenhe"></i>
            <div class="fcpi__cell bgwihte">
              <span>{{member.waiting_check}}元</span>
              <span>可用资金</span>
            </div>
          </div>
          <div class="fcpi__rowR fl cellTitle">
            <i class="pub_iconOran icon_blue iconfont icon-yinpiao"></i>
            <div class="fcpi__cell bgwihte">
              <span class="blue">{{member.bank_bill}}</span>
              <span>冻结资金</span>
            </div>
          </div>
        </div>
        <div class="fcpi__row">
          <div class="fcpi__rowL fl cellTitle">
            <i class="pub_iconOran icon_green iconfont icon-jiaoyizhong"></i>
            <div class="fcpi__cell bgwihte">
              <span class="green">{{smember.invoice}}元</span>
              <span>累计收票</span>
            </div>
          </div>
          <div class="fcpi__rowR fl cellTitle">
            <i class="pub_iconOran icon_blue iconfont icon-shangpiao"></i>
            <div class="fcpi__cell bgwihte">
              <span class="blue">{{smember.expense}}</span>
              <span>收票支出</span>
            </div>
          </div>
        </div>
      </div>
      <div class="fcpi__box fl">
        <div class="fcpi__row clearfix">
          <div class="fcpi__rowL fl cellTitle">
            <div class="fcpi__cell" style="padding-left:20px;">
              票据资产 <span class="spanblod">{{smember.transaction}}元</span>
            </div>
          </div>
          <div class="fcpi__rowR fl cellTitle">
            <div class="fcpi__tiny fl">
              <router-link tag="div" class="goexamine tiny__box" to="/user/billRecord">录入票据</router-link>
            </div>
            <div class="fcpi__tiny fl">
              <router-link tag="div" class="goexamine tiny__box" to="/user/tradeRecord">交易中心</router-link>
            </div>
          </div>
        </div>
        <div class="fcpi__row clearfix">
          <div class="fcpi__rowL fl cellTitle">
            <i class="pub_iconOran iconfont icon-daishenhe"></i>
            <div class="fcpi__cell bgwihte">
              <span>{{smember.holding}}元</span>
              <span>可用票据</span>
            </div>
          </div>
          <div class="fcpi__rowR fl cellTitle">
            <i class="pub_iconOran icon_blue iconfont icon-yinpiao"></i>
            <div class="fcpi__cell bgwihte">
              <span class="blue">{{smember.frozen}}元</span>
              <span>冻结票据</span>
            </div>
          </div>
        </div>
        <div class="fcpi__row">
          <div class="fcpi__rowL fl cellTitle">
            <i class="pub_iconOran icon_green iconfont icon-jiaoyizhong"></i>
            <div class="fcpi__cell bgwihte">
              <span class="green">{{smember.assignment}}元</span>
              <span>累计转让</span>
            </div>
          </div>
          <div class="fcpi__rowR fl cellTitle">
            <i class="pub_iconOran icon_blue iconfont icon-shangpiao"></i>
            <div class="fcpi__cell bgwihte">
              <span class="blue">{{smember.earning}}元</span>
              <span>累计收入</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="clearfix tablemain">
      <div class="title">
        <div class="title-tabs clearfix">
          <h2>我的票据</h2>
          <div class="search-warp" >
            <el-input class="search-warp__title" placeholder="编号/承兑人/金额" v-model="param1.query"> </el-input>
            <el-date-picker class="search-warp__date" v-model="param1.startTime" type="date" placeholder="到期日-从" :picker-options="pickerOptions0" @change="changeStartTime1">
            </el-date-picker>
            <el-date-picker class="search-warp__date" v-model="param1.endTime" type="date" placeholder="到期日-到" :picker-options="pickerOptions0" @change="changeEndTime1">
            </el-date-picker>
            <el-button class="search-warp__submit" size="small" @click="search1">搜索</el-button>
          </div>
        </div>
      </div>
      <myTradeList :billData="billData" :tradeTotal="tradeTotal" v-on:reFlashBill="reFlashBill"></myTradeList>
    </div>
    <div class="clearfix tablemain">
      <div class="title">
        <div class="clearfix">
          <h2>资金流水</h2>
          <div class="search-warp">
            <el-select class="search-warp__type" v-model="param3.type" placeholder="请选择">
              <el-option v-for="item in moneyType" :label="item.label" :value="item.value">
              </el-option>
            </el-select>
            <!-- <el-input class="search-warp__title" placeholder="编号/承兑人/金额" v-model="param3.query"> </el-input> -->
            <el-date-picker class="search-warp__date" v-model="param3.startTime" type="date" :editable="false" placeholder="到期日-从" :picker-options="pickerOptions0" @change="changeStartTime3">
            </el-date-picker>
            <el-date-picker class="search-warp__date" v-model="param3.endTime" type="date" :editable="false" placeholder="到期日-到" :picker-options="pickerOptions0" @change="changeEndTime3">
            </el-date-picker>
            <el-button class="search-warp__submit" size="small" @click="getDaybookList">搜索</el-button>
          </div>
        </div>
      </div>
      <div>
        <el-table :data="billSubstituteData" style="width: 100%;margin-top:10px;" stripe :default-sort="{prop: 'date', order: 'descending'}">
          <el-table-column prop="id" label="流水号" width="80">
          </el-table-column>
          <el-table-column prop="created_at" label="流水日期" min-width="180" class-name="companyname">
          </el-table-column>
          <el-table-column prop="type" label="操作类型" width="180">
          </el-table-column>
          <el-table-column prop="amount" label="金额" min-width="120">
          </el-table-column>
          <el-table-column prop="status" label="当前状态" min-width="120">
          </el-table-column>
          <!-- <el-table-column prop="left_day" label="剩余天数" sortable min-width="120">
          </el-table-column>
          <el-table-column prop="face_amount" label="票面金额（元）" sortable min-width="180" :formatter="formatter">
          </el-table-column>
          <el-table-column prop="annualized_rate_suggest" label="审核利率（%）" min-width="100">
          </el-table-column> -->
         <!--  <el-table-column prop="state" label="附件" width="100">
            <template scope="scope">
              <i class="iconfont icon-wenjian" @click="checkImg(scope.$index,scope.row)"></i>
            </template>
          </el-table-column>
          <el-table-column label="操作" width="180">
            <template scope="scope">
              <el-button type="primary" size="small" class="examine__submit" @click="maintain(billSubstituteData[scope.$index])">维护</el-button>
              <el-button type="danger" size="small" class="examine__submit" @click.native.prevent="billDelete(scope.$index, billSubstituteData)">删除</el-button>
            </template>
          </el-table-column> -->
        </el-table>
        <el-pagination class="table__page" layout="prev, pager, next" :total="total3" :page-size="5" @current-change="changePage3">
        </el-pagination>
      </div>
    </div>
    <getRechange :dialogtype="dialogtype" :ischaneg='change'></getRechange>
  </div>
</template>
<script>
import api from '../../api'
import myTradeList from '../../components/myTradeList'
import getRechange from '../../components/getRechange'
import {temptime} from '../../../src/assets/js/common-js'
export default {
  data() {
      return {
        dialogtype:'',
        change:0,
        total1: 0,
        total2: 0,
        total3: 0,
        tradeTotal:0,
        billListPram:{
          page:1,
          pageSize:5,
        },
        param1: {
          page: 1,
          startTime: '',
          endTime: '',
          query: ''
        },
        param2: {
          page: 1,
          pageSize: 5,
          startTime: '',
          endTime: '',
          query: '',
          status:''
        },
        param3: {
          type:'',
          page: 1,
          pageSize: 5,
          startTime: '',
          endTime: '',
          query: ''
        },
        whShow: false,
        whShow2: false,
        whParentData: [],
        whParentData2: [],
        tabsShowOne: true,
        tabsShowTwo: true,
        options: [{
          value: '',
          label: '请选择'
        }, {
          value: '1',
          label: '已通过'
        }, {
          value: '2',
          label: '未通过'
        }],
        moneyType:[{
          value: '',
          label: '请选择'
        },{
          value: '1',
          label: '充值'
        },{
          value: '2',
          label: '提款'
        },{
          value: '3',
          label: '付款'
        },{
          value: '4',
          label: '退款'
        },{
          value: '5',
          label: '收到退款'
        },{
          value: '6',
          label: '收款'
        },],
        value: '',
        value1: '',
        pickerOptions0: {},
        value2: '',
        finance: {

        },
        member: {
          total:'0.00',
          bank_bill:'0.00',
          waiting_check:'0.00',
        },
        smember:{},
        billCheckData: [],
        recordData: [],
        subData: [],
        billSubstituteData: [],
        swiperData:[],
        showImg:false,
        swiperSlides:[],
        billData:[],
      }
    },
    mounted: function() {
      this.$nextTick(() => {
        this.getMyBillList();
        this.getDaybookList();
        this.getmoneyinfo();
        this.getInfoRealTime();
        window.satime=setInterval(()=>{
          this.getMyBillList();
          this.getmoneyinfo();
          this.getInfoRealTime();
        }, 5000);
      })
    },
    destroyed:function(){
      clearInterval(satime);
    },
    components: {
      myTradeList,
      getRechange
    },

    methods: {
      getInfoRealTime(){
        api.getrealTime((data)=>{
          this.smember=data;
        });
      },
      //获取用户余额信息
      getmoneyinfo(){
        api.getAssetsInfo((data)=>{
          this.member.total=data.total;
          this.member.bank_bill=data.frozen;
          this.member.waiting_check=data.available;
        });
      },
      showdialogtype(diatype){
        if(sessionStorage.enterprise_status!=8){
          this.$notify({
            title:"提示",
            message:"请先进行企业认证",
            type:'warning',
            duration:'2000'
          });
          setTimeout(()=>{
            this.$router.push({
              path: '/user/enterprise'
            });
          }, 2000);
        }else{
          this.change=this.change==1 ? 0 : 1;
          this.dialogtype=diatype;
        }
      },
      reFlashBill(data){
        // console.log('reflash');
        if(data.page){
          this.billListPram.page=data.page;
          this.billListPram.sort=data.sort;
          this.getMyBillList();
        }else{
          this.getmoneyinfo();
        }
        
      },
      async getMyBillList() {
        const data = await api.get(this, api.config.billMylist,this.billListPram);
        for(let x of data.data){
          x.status = api.transBillStatus(x.status, sessionStorage.uid, x.possessor, x.investor);
          x.annualized_rate=x.annualized_rate=='0.00'?'--':x.annualized_rate;
          x.wechselspesen=x.wechselspesen=='0.00'?'--':x.wechselspesen;
        }
        this.billData = data.data;
        this.tradeTotal=data.count;
      },
      
      async getDaybookList(){
        this.param3.startTime=temptime(this.param3.startTime);
        this.param3.endTime=temptime(this.param3.endTime);
        const data = await api.get(this, api.config.daybookList,this.param3);
        if(data.code==200){
          console.log(data.data);
          this.billSubstituteData=data.data;
          this.total3=data.count;
        }
      },
      formatter(row, column) {
        return api.toThousands(row.face_amount);
      },
      search1() {
      },
      search2() {
      },
      search3() {
      },
      changeStartTime1(events) {
        this.param1.startTime = events;
      },
      changeEndTime1(events) {
        this.param1.endTime = events;
      },
      changeStartTime2(events) {
        this.param2.startTime = events;
      },
      changeEndTime2(events) {
        this.param2.endTime = events;
      },
      changeStartTime3(events) {
        this.param3.startTime = events;
      },
      changeEndTime3(events) {
        this.param3.endTime = events;
      },
      // changePage1(val) {
      //   this.param1.page = val;
      //   this.getBillCheck();
      // },
      // changePage2(val) {
      //   this.param2.page = val;
      //   console.log(val)
      //   this.getRecordList();
      // },
      changePage3(val) {
        this.param3.page = val;
        this.getDaybookList();
      },
    }
}
</script>
<style scoped>
.goexamine{
  cursor: pointer;
  &:hover{
    background:#2fa4e7;
  };
  &:visited{
    background:#258fcc;
  };
}
.fcpi__box {
  width: 50%;
  font-size: 16px;
  padding-right: 5px;
  margin-bottom: 10px;
}

.fcpi__box:nth-child(2) {
  padding-left: 5px;
  padding-right: 0px;
}

@media (max-width: 1000px) {
  .fcpi__box {
    width: 100%;
  }
  .fcpi__box:nth-child(2) {
    padding-left: 0px;
    padding-right: 5px;
  }
}

.cellTitle {
  position: relative;
  height: 50px;
  color: #fff;
}

.fcpi__row {
  margin-bottom: 4px;
}

.fcpi__rowL {
  width: 70%;
  padding-right: 4px;
}

.fcpi__rowR {
  width: 30%;
}

.fcpi__cell {
  height: 100%;
  line-height: 50px;
  background: #207fb7;
}

.fcpi__cell.bgwihte {
  line-height: normal;
  padding-left: 50px;
  background: #fff !important;
}

.spanblod {
  display: inline !important;
  margin-left: 10px;
  font-weight: bold;
  color: #fff !important;
}

.fcpi__cell span {
  display: block;
}

.fcpi__cell span:nth-child(1) {
  padding-top: 5px;
  color: #e96345;
  font-weight: bold;
}

.fcpi__cell span:nth-child(1).blue {
  color: #207fb7;
}

.fcpi__cell span:nth-child(1).green {
  color: #18a918;
}

.fcpi__cell span:nth-child(2) {
  color: #808080;
  font-size: 12px;
}

.fcpi__tiny {
  width: 50%;
  /*background: red;*/
  padding-right: 2px;
}

.fcpi__tiny:nth-child(2) {
  padding: 0;
  padding-left: 2px;
}

.tiny__box {
  height: 50px;
  line-height: 50px;
  text-align: center;
  background: #207fb7;
}
</style>
