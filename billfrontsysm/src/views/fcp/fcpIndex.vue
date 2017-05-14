<template>
  <div class="fcpIndex">
    <div class="clearfix">
      <div class="fcpi__box fl">
        <div class="fcpi__row clearfix">
          <div class="fcpi__rowL fl cellTitle">
            <div class="fcpi__cell" style="padding-left:20px;">
              成员公司票据 <span class="spanblod">{{member.transaction}}元</span>
            </div>
          </div>
          <div class="fcpi__rowR fl cellTitle">
            <router-link tag="div" class="goexamine fcpi__cell text-center" to="/user/examine">审核票据</router-link>
          </div>
        </div>
        <div class="fcpi__row clearfix">
          <div class="fcpi__rowL fl cellTitle">
            <i class="pub_iconOran iconfont icon-daishenhe"></i>
            <div class="fcpi__cell bgwihte">
              <span>{{member.waiting_check}}元</span>
              <span>待审核票据</span>
            </div>
          </div>
          <div class="fcpi__rowR fl cellTitle">
            <i class="pub_iconOran icon_blue iconfont icon-yinpiao"></i>
            <div class="fcpi__cell bgwihte">
              <span class="blue">{{member.bank_entering}}</span>
              <span>银票数量</span>
            </div>
          </div>
        </div>
        <div class="fcpi__row">
          <div class="fcpi__rowL fl cellTitle">
            <i class="pub_iconOran icon_green iconfont icon-jiaoyizhong"></i>
            <div class="fcpi__cell bgwihte">
              <span class="green">{{member.holding}}元</span>
              <span>持有中票据</span>
            </div>
          </div>
          <div class="fcpi__rowR fl cellTitle">
            <i class="pub_iconOran icon_blue iconfont icon-shangpiao"></i>
            <div class="fcpi__cell bgwihte">
              <span class="blue">{{member.commercial_entering}}</span>
              <span>商票数量</span>
            </div>
          </div>
        </div>
      </div>
      <div class="fcpi__box fl">
        <div class="fcpi__row clearfix">
          <div class="fcpi__rowL fl cellTitle">
            <div class="fcpi__cell" style="padding-left:20px;">
              财务公司票据 <span class="spanblod">{{finance.transaction}}元</span>
            </div>
          </div>
          <div class="fcpi__rowR fl cellTitle">
            <div class="fcpi__tiny fl">
              <router-link tag="div" class="goexamine tiny__box" to="/user/billRecord">录入票据</router-link>
            </div>
            <div class="fcpi__tiny fl">
              <router-link tag="div" class="goexamine tiny__box" to="/user/tradeRecord">交易记录</router-link>
            </div>
          </div>
        </div>
        <div class="fcpi__row clearfix">
          <div class="fcpi__rowL fl cellTitle">
            <i class="pub_iconOran iconfont icon-daishenhe"></i>
            <div class="fcpi__cell bgwihte">
              <span>{{finance.waiting_check}}元</span>
              <span>待审核票据</span>
            </div>
          </div>
          <div class="fcpi__rowR fl cellTitle">
            <i class="pub_iconOran icon_blue iconfont icon-yinpiao"></i>
            <div class="fcpi__cell bgwihte">
              <span class="blue">{{finance.bank_entering}}</span>
              <span>银票数量</span>
            </div>
          </div>
        </div>
        <div class="fcpi__row">
          <div class="fcpi__rowL fl cellTitle">
            <i class="pub_iconOran icon_green iconfont icon-jiaoyizhong"></i>
            <div class="fcpi__cell bgwihte">
              <span class="green">{{finance.holding}}元</span>
              <span>持有中票据</span>
            </div>
          </div>
          <div class="fcpi__rowR fl cellTitle">
            <i class="pub_iconOran icon_blue iconfont icon-shangpiao"></i>
            <div class="fcpi__cell bgwihte">
              <span class="blue">{{finance.commercial_entering}}</span>
              <span>商票数量</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="clearfix tablemain">
      <div class="title">
        <div class="clearfix">
          <h2>财务公司待复核票据</h2>
          <div class="search-warp">
            <el-input class="search-warp__title" v-model="searchDate.query" placeholder="编号/承兑人/金额"> </el-input>
            <el-date-picker class="search-warp__date" v-model="searchDate.startTime" type="date" :editable="false" placeholder="到期日-从" @change="changeStartTime" :picker-options="pickerOptions1">
            </el-date-picker>
            <el-date-picker class="search-warp__date" v-model="searchDate.endTime" type="date" :editable="false" placeholder="到期日-到" @change="changeEndTime" :picker-options="pickerOptions1">
            </el-date-picker>
            <el-button class="search-warp__submit" @click="serachSubmit" size="small">搜索</el-button>
          </div>
        </div>
      </div>
      <div>
        <el-table :data="billCheckData.data" style="width: 100%;margin-top:10px;" stripe :default-sort="{prop: 'date', order: 'descending'}" :highlight-current-row="false" @sort-change="billCheckDataSort">
          <el-table-column prop="id" label="序号" width="80">
          </el-table-column>
          <el-table-column prop="bill_number" label="票据编号" min-width="180" class-name="companyname">
          </el-table-column>
          <el-table-column prop="acceptor" label="承兑人" width="180">
          </el-table-column>
          <el-table-column prop="type" label="票据类型" sortable min-width="120">
          </el-table-column>
          <el-table-column prop="acceptance_at" label="到期日" sortable min-width="120">
          </el-table-column>
          <el-table-column prop="face_amount" label="票面金额（元）" sortable min-width="180" :formatter="formatter">
          </el-table-column>
          <el-table-column prop="apply_at" label="申请时间" sortable min-width="120">
          </el-table-column>
          <el-table-column prop="status" label="审核状态" min-width="100">
          </el-table-column>
          <el-table-column label="操作" width="180">
            <template scope="scope">
              <el-button type="primary" size="small" class="examine__submit" v-show="scope.row.status!= '待审核'" @click="billModify(scope.$index,scope.row)">修改</el-button>
              <el-button v-if="billCheckData.data[scope.$index].is_traded==0" type="danger" size="small" class="examine__submit" @click.native.prevent="billDelete(scope.$index, billCheckData.data, 1)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination :page-size="5" @current-change="changePage" class="table__page" layout="prev, pager, next" :total="billCheckData.count">
        </el-pagination>
      </div>
    </div>
    <div class="clearfix tablemain">
      <div class="title">
        <div class="clearfix">
          <h2>财务公司待维护票据</h2>
          <div class="search-warp">
            <el-input class="search-warp__title" v-model="searchAgentDate.query" placeholder="编号/承兑人/金额"> </el-input>
            <el-date-picker class="search-warp__date" v-model="searchAgentDate.startTime" type="date" :editable="false" placeholder="到期日-从" @change="changeAgentStartTime" :picker-options="pickerOptions1">
            </el-date-picker>
            <el-date-picker class="search-warp__date" v-model="searchAgentDate.endTime" type="date" :editable="false" placeholder="到期日-到" @change="changeAgentEndTime" :picker-options="pickerOptions0">
            </el-date-picker>
            <el-button class="search-warp__submit" @click="substituteSubmit" size="small">搜索</el-button>
          </div>
        </div>
      </div>
      <div>
        <el-table :data="billSubstituteData.data" style="width: 100%;margin-top:10px;" stripe :default-sort="{prop: 'date', order: 'descending'} " :show-header="true" @sort-change="billSubstituteDataSort">
          <el-table-column prop="id" label="序号" width="80">
          </el-table-column>
          <el-table-column prop="drawer" label="出票人" min-width="180" class-name="companyname">
          </el-table-column>
          <el-table-column prop="acceptor" label="承兑人" width="180">
          </el-table-column>
          <el-table-column prop="type" label="票据类型" sortable min-width="120">
          </el-table-column>
          <el-table-column prop="acceptance_at" label="到期日" sortable min-width="120">
          </el-table-column>
          <el-table-column prop="left_day" label="剩余天数" min-width="120">
          </el-table-column>
          <el-table-column prop="face_amount" label="票面金额（元）" sortable min-width="180" :formatter="formatter">
          </el-table-column>
          <el-table-column prop="annualized_rate_suggest" label="审核利率（%）" min-width="110">
          </el-table-column>
          <el-table-column prop="state" label="附件" width="100">
            <template scope="scope">
              <i class="iconfont icon-wenjian" @click="checkImg(scope.$index,scope.row)"></i>
            </template>
          </el-table-column>
          <el-table-column label="操作" width="200">
            <template scope="scope">
              <el-button type="primary" size="small" class="examine__submit" @click="billWh(scope.$index, scope.row)">维护</el-button>
              <el-button type="primary" size="small" class="examine__submit" @click="goguapai(scope.$index, billSubstituteData.data)">挂牌</el-button>
              <el-button v-if="billSubstituteData.data[scope.$index].is_traded==0" type="danger" size="small" class="examine__submit" @click.native.prevent="billDelete(scope.$index, billSubstituteData.data, 2)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination :page-size="10" @current-change="billSubstitutePage" class="table__page" layout="prev, pager, next" :total="billSubstituteData.count">
        </el-pagination>
      </div>
    </div>
    <wh-dialog :whopen="whShow" :whDate="whParentData" :isSelf="true" v-on:close="closeWhDialog"></wh-dialog>
    <bill-modify :whopen="whopen" :id="ModifyId" v-on:close="closeModify"></bill-modify>
    <show-img v-show="showImg" :swiperData="swiperSlides" v-on:close="closeShowImg"></show-img>
    <el-dialog class="tradedialog" title="提示" v-model="delateDialog" size="tiny">
      <span>是否确认删除?</span>
      <span slot="footer" class="dialog-footer" align="center">
          <el-button class='cancel-btn2' type="primary" @click="createTycd()">确认</el-button>
          <el-button class="cancel-btn" @click="delateDialog = false">考虑一下</el-button>
      </span>
    </el-dialog>
    <guaPai :dialogData="guapaiData" :isshow="isshowguapai" @reFlashBill="reFlashBill"></guaPai>
  </div>
  </div>
</template>
<script>
import api from '../../api'
import guaPai from "../../components/guapaiDialog.vue"
import whDialog from "../../components/wh.vue"
import billModify from "../../components/billModify.vue"
import showImg from 'src/components/showImg'
export default {
  data() {
      return {
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
        guapaiData:{},
        isshowguapai:0,
        delateDialog:false,
        searchDate: {
          page:1,
          pageSize:5,
          query: "",
          startTime: "",
          endTime: "",
          sort:'',
        },
        searchAgentDate: {
          page:1,
          pageSize:10,
          query: "",
          startTime: "",
          endTime: "",
          sort:'',
        },
        deleateInfo:{
          possessor_address:'',
          bill_number:'',
          sstype:'',
          id:'',
        },
        pickerOptions0: {},
        value2: '',
        pickerOptions1: {},
        billSubstituteData: [],
        billCheckData: [],
        finance: {},
        member: {},
        whShow: false,
        whParentData: [],
        whopen: false,
        ModifyId: "",
        showImg:false,
        swiperSlides:[],
      }
    },
    mounted: function() {
      this.$nextTick(() => {
        api.auth(this);
        this.getBillSubstitute();
        this.getBillCheck();
        this.getInfoRealTime();
        // let result = api.billNoteSigns().signSetAdmin(api.userkey(),'f7b158e807b6a7f7706f12561b14d9c694ac8dc8860c353f1cc7e5a88e2a99b8',3);
        // console.log(result);
      })
    },
    components: {
      whDialog,
      billModify,
      showImg,
      guaPai
    },
    methods: {
      reFlashBill(){
        this.getBillSubstitute();
      },
      getInfoRealTime(){
        api.getrealTime((data)=>{
          this.member=data.member;
          this.finance=data.finance;
        });
      },
      goguapai(index,tableData){
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
          this.guapaiData=tableData[index];
          this.isshowguapai=this.isshowguapai==0?1:0;
        }
      },
      billCheckDataSort(column){
        let sortType=column.order=='ascending'? '' : '-' ;
        var sort='';
        if(column.order=='descending'){
          sort=sortType+column.prop;
        }else{
          sort=column.prop;
        }
        this.searchDate.sort=sort;
        this.getBillCheck();
      },
      billSubstituteDataSort(column){
        let sortType=column.order=='ascending'? '' : '-' ;
        var sort='';
        if(column.order=='descending'){
          sort=sortType+column.prop;
        }else{
          sort=column.prop;
        }
        this.searchAgentDate.sort=sort;
        this.getBillSubstitute();
      },
      formatter(row, column) {
        return api.toThousands(row.face_amount);
      },
      closeShowImg(){
        this.showImg = false;
      },
      checkImg(index, rows) {
        // console.log(rows.bill_front_path);
        var swiperData = [{
          title: "票据正面",
          src: rows.bill_front_path
        }, {
          title: "票据背面",
          src: rows.bill_back_path
        }]
        this.swiperSlides = swiperData;
        this.showImg = true;
      },
      billWh(index, data) {
        this.whShow = true;
        // console.log("data", data);
        this.whParentData = data;
      },
      closeWhDialog() {
        this.whShow = false;
        this.getBillSubstitute();
      },
      billDelete(index, table, type) {
        this.delateDialog=true;
        this.deleateInfo.possessor_address=table[index].possessor_address;
        this.deleateInfo.bill_number=table[index].bill_number;
        // this.deleateInfo.sstype=this.type == 1 ? this.getBillCheck : this.getBillSubstitute;
        this.deleateInfo.id=table[index].id;
        // api.billDelete(this, table[index].possessor_address, table[index].id,table[index].bill_number, this, type == 1 ? this.getBillCheck : this.getBillSubstitute);
      },
      async createTycd(){
        const data = await api.billDelete(this,this.deleateInfo.possessor_address,this.deleateInfo.id,this.deleateInfo.bill_number,this,()=>{
          this.delateDialog=false;
          this.getBillCheck();
          this.getBillSubstitute();
        });
      },
      async getBillSubstitute() {
        const data = await api.get(this, api.config.waitingMainList, this.searchAgentDate);
        // console.log(data);
        if (data.code == 200) {
          for (let x of data.data) {
            x.face_amount = Number(x.face_amount.replace(/,/g,''));
            x.annualized_rate_suggest =(x.annualized_rate_suggest==null ||  x.annualized_rate_suggest ==  '' || x.annualized_rate_suggest ==  '0.00') ? '--' : '≤' + x.annualized_rate_suggest;
          }
          this.billSubstituteData = data;
        } else {
          console.log(data.message);
        }
      },
      async getBillCheck() {
        const data = await api.get(this,api.config.waitingCheckList, this.searchDate);
        if (data.code == 200) {
          // console.log(data);
          for(let x of data.data){
            x.face_amount = Number(x.face_amount.replace(/,/g,''));
          }
          this.billCheckData = data;
        } else {
          console.log(data.message);
        }
      },
      async getBillStatistics() {
        const data = await api.get(this,api.config.billStatistics);
        if (data.code == 200) {
          this.finance = data.finance;
          this.member = data.member;
        } else {
          console.log(data.message);
        }
      },
      changeStartTime(events) {
        // console.log(events);
        this.searchDate.startTime = events;
      },
      changeEndTime(events) {
        this.searchDate.endTime = events;
      },
      changeAgentStartTime(events) {
        this.searchAgentDate.startTime = events;
      },
      changeAgentEndTime(events) {
        this.searchAgentDate.endTime = events;
      },
      serachSubmit() {
        if (new Date(this.searchDate.startTime).getTime() > new Date(this.searchDate.endTime).getTime()) {
          this.$notify.error({
            title: '错误',
            message: '开始时间必须小于结束时间'
          });
          return false;
        } else {
          this.getBillCheck();
        }
      },
      substituteSubmit() {
        if (new Date(this.searchAgentDate.startTime).getTime() > new Date(this.searchAgentDate.endTime).getTime()) {
          this.$notify.error({
            title: '错误',
            message: '开始时间必须小于结束时间'
          });
          return false;
        } else {
          this.getBillSubstitute();
        }
      },
      changePage(size) {
        if (new Date(this.searchDate.startTime).getTime() > new Date(this.searchDate.endTime).getTime()) {
          this.$notify.error({
            title: '错误',
            message: '开始时间必须小于结束时间'
          });
          return false;
        } else {
          this.searchDate.page=size;
          this.getBillCheck();
        }
      },
      billSubstitutePage(size) {
        if (new Date(this.searchAgentDate.startTime).getTime() > new Date(this.searchAgentDate.endTime).getTime()) {
          this.$notify.error({
            title: '错误',
            message: '开始时间必须小于结束时间'
          });
          return false;
        } else {
          this.searchAgentDate.page=size;
          this.getBillSubstitute();
        }
      },
      billModify(index, row) {
        this.whopen = true;
        this.ModifyId = row.id;
      },
      closeModify() {
        this.whopen = false;
        this.serachSubmit();
      }
    }
}
</script>
<style scoped>
.fcpIndex{
  .tradedialog {
 
    .info{
      text-align: left;
    }
    .el-dialog {
      width: 660px;
      border-radius: 6px;
      &__header {
        text-align: center;
      }
      &__title {
        font-weight: 400;
        text-align: center;
      }
    }
    .el-dialog__footer{
      text-align: center!important;
    }
    .el-dialog__body{
      text-align: center!important;
    }
    .el-dialog__title{
      font-size: 18px!important;
      color:#2fa4e7!important;
    }
    
    .sure-btn{
      width: 100%;
    }
  }
}
.goexamine {
  cursor: pointer;
  &:hover {
    background: #2fa4e7;
  }
  ;
  &:visited {
    background: #258fcc;
  }
  ;
}

.fcpi__box {
  width: 50%;
  font-size: 16px;
  padding-right: 5px;
  margin-bottom: 10px;
}

.tablemain {
  background: #fff;
  border-top: #2fa4e7 3px solid;
  padding: 0 20px;
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
  padding-left: 54px;
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
