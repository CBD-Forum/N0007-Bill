<template>
  <div class="fcpIndex">
    <div class="clearfix">
      <div class="fcpi__box fl">
        <div class="fcpi__row clearfix">
          <div class="fcpi__rowL fl cellTitle" style="width:100%;padding-right:0px">
            <div class="fcpi__cell" style="padding-left:20px;">
              成员公司票据 <span class="spanblod">{{member.transaction}}</span>
            </div>
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
            <router-link tag="div" class="goexamine fcpi__cell text-center" to="/user/tradeRecord">交易记录</router-link>
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
    <!-- <div class="clearfix tablemain">
      <div class="title">
        <div class="title-tabs clearfix">
          <a :class="{active: tabsShowOne}" @click="tabsShowOne = true">录入审核</a>
          <a :class="{active: !tabsShowOne}" @click="tabsShowOne = !tabsShowOne">历史审核</a>
          <div class="search-warp" v-show="tabsShowOne">
            <el-input class="search-warp__title" placeholder="编号/承兑人/金额" v-model="param1.query"> </el-input>
            <el-date-picker class="search-warp__date" v-model="param1.startTime" type="date" placeholder="选择日期" :picker-options="pickerOptions0" @change="changeStartTime1">
            </el-date-picker>
            <el-date-picker class="search-warp__date" v-model="param1.endTime" type="date" placeholder="选择日期" :picker-options="pickerOptions0" @change="changeEndTime1">
            </el-date-picker>
            <el-button class="search-warp__submit" size="small" @click="search1">搜索</el-button>
          </div>
          <div class="search-warp" v-show="!tabsShowOne">
            <el-input class="search-warp__title" placeholder="编号/承兑人/金额" v-model="param2.query"> </el-input>
            <el-select class="search-warp__type" v-model="param2.status" placeholder="请选择">
              <el-option v-for="item in options" :label="item.label" :value="item.value">
              </el-option>
            </el-select>
            <el-date-picker class="search-warp__date" v-model="param2.startTime" type="date" placeholder="选择日期" :picker-options="pickerOptions0" @change="changeStartTime2">
            </el-date-picker>
            <el-date-picker class="search-warp__date" v-model="param2.endTime" type="date" placeholder="选择日期" :picker-options="pickerOptions0" @change="changeEndTime2">
            </el-date-picker>
            <el-button class="search-warp__submit" size="small" @click="search2">搜索</el-button>
          </div>
        </div>
      </div>
      <div class="tabs-content">
        <div v-show="tabsShowOne">
          <el-table :data="billCheckData" style="width: 100%;margin-top:10px;" stripe :default-sort="{prop: 'date', order: 'descending'}" @sort-change="billCheckDataSort">
            <el-table-column prop="id" label="序号" width="80">
            </el-table-column>
            <el-table-column prop="enterprise_name" label="申请企业" min-width="180" class-name="companyname">
            </el-table-column>
            <el-table-column prop="bill_number" label="票据编号" width="180">
            </el-table-column>
            <el-table-column prop="acceptor" label="承兑人" min-width="180">
            </el-table-column>
            <el-table-column prop="type" label="票据类型" sortable width="120">
            </el-table-column>
            <el-table-column prop="acceptance_at" label="到期日" sortable width="120">
            </el-table-column>
            <el-table-column prop="face_amount" label="票据金额（元）" sortable min-width="180" :formatter="formatter">
            </el-table-column>
            <el-table-column prop="apply_at" label="申请时间" sortable min-width="160">
            </el-table-column>
            <el-table-column prop="status" label="审核状态" width="100">
            </el-table-column>
            <el-table-column label="操作" min-width="100">
              <template scope="scope">
                <el-button type="primary" size="small" class="examine__submit" @click="showExamine(scope.$index,scope.row)">审核</el-button>
              </template>
            </el-table-column>
          </el-table>
          <el-pagination class="table__page" layout="prev, pager, next" :total="total1" :page-size="5" @current-change="changePage1">
          </el-pagination>
        </div>
        <div v-show="!tabsShowOne">
          <el-table :data="recordData" style="width: 100%;margin-top:10px;" stripe :default-sort="{prop: 'date', order: 'descending'}" @sort-change="recordDataSort">
            <el-table-column prop="id" label="序号" width="80">
            </el-table-column>
            <el-table-column prop="applicant" label="申请企业" min-width="180" class-name="companyname">
            </el-table-column>
            <el-table-column prop="bill_number" label="票据编号" width="180">
            </el-table-column>
            <el-table-column prop="acceptor" label="承兑人" min-width="180">
            </el-table-column>
            <el-table-column prop="bill_type" label="票据类型" sortable width="120">
            </el-table-column>
            <el-table-column prop="acceptance_at" label="到期日" sortable width="120">
            </el-table-column>
            <el-table-column prop="face_amount" label="票据金额（元）" sortable min-width="180" :formatter="formatter">
            </el-table-column>
            <el-table-column prop="apply_at" label="申请时间" sortable min-width="160">
            </el-table-column>
            <el-table-column prop="created_at" label="审核时间" sortable min-width="160">
            </el-table-column>
            <el-table-column prop="annualized_rate_suggest" label="审核利率（%）" min-width="130">
            </el-table-column>
            <el-table-column prop="status" label="审核状态" width="100">
            </el-table-column>
          </el-table>
          <el-pagination class="table__page" layout="prev, pager, next" :total="total2" :page-size="5" @current-change="changePage2">
          </el-pagination>
        </div>
      </div>
    </div>
    <div class="clearfix tablemain">
      <div class="title">
        <div class="clearfix">
          <h2>代财务公司维护票据</h2>
          <div class="search-warp">
            <el-input class="search-warp__title" placeholder="编号/承兑人/金额" v-model="param3.query"> </el-input>
            <el-date-picker class="search-warp__date" v-model="param3.startTime" type="date" :editable="false" placeholder="到期日-从" :picker-options="pickerOptions0" @change="changeEndTime3">
            </el-date-picker>
            <el-date-picker class="search-warp__date" v-model="param3.endTime" type="date" :editable="false" placeholder="到期日-到" :picker-options="pickerOptions0" @change="changeEndTime3">
            </el-date-picker>
            <el-button class="search-warp__submit" size="small" @click="search3">搜索</el-button>
          </div>
        </div>
      </div>
      <div>
        <el-table :data="billSubstituteData" style="width: 100%;margin-top:10px;" stripe :default-sort="{prop: 'date', order: 'descending'}" @sort-change="billSubstituteDataSort">
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
          <el-table-column prop="left_day" label="剩余天数" sortable min-width="120">
          </el-table-column>
          <el-table-column prop="face_amount" label="票面金额（元）" sortable min-width="180" :formatter="formatter">
          </el-table-column>
          <el-table-column prop="annualized_rate_suggest" label="审核利率（%）" min-width="100">
          </el-table-column>
          <el-table-column prop="state" label="附件" width="100">
            <template scope="scope">
              <i class="iconfont icon-wenjian" @click="checkImg(scope.$index,scope.row)"></i>
            </template>
          </el-table-column>
          <el-table-column label="操作" width="180">
            <template scope="scope">
              <el-button type="primary" size="small" class="examine__submit" @click="maintain(billSubstituteData[scope.$index])">维护</el-button>
              <el-button type="danger" size="small" class="examine__submit" @click.native.prevent="billDelete(scope.$index, billSubstituteData)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination class="table__page" layout="prev, pager, next" :total="total3" :page-size="5" @current-change="changePage3">
        </el-pagination>
      </div>
    </div> -->
    <examine :logintype="fuhe"></examine>
    <examineDialog :whopen="whShow" :whDate="whParentData" v-on:close="closeWhDialog"></examineDialog>
    <whDialog :whopen="whShow2" :whDate="whParentData2" v-on:close="closeWhDialog2"></whDialog>
    <show-img v-show="showImg" :swiperData="swiperSlides" v-on:close="closeShowImg"></show-img>
  </div>
</template>
<script>
import examineDialog from 'components/examineDialog'
import whDialog from 'components/wh.vue'
import api from '../../api'
import showImg from 'src/components/showImg'
import examine from '../examine/examine.vue'
export default {
  data() {
      return {
        fuhe:'fuhe',
        total1: 0,
        total2: 0,
        total3: 0,
        param1: {
          page: 1,
          startTime: '',
          endTime: '',
          query: '',
          sort:'',
        },
        param2: {
          page: 1,
          pageSize: 5,
          startTime: '',
          endTime: '',
          query: '',
          status:'',
          sort:'',
        },
        param3: {
          page: 1,
          pageSize: 5,
          startTime: '',
          endTime: '',
          query: '',
          sort:'',
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
        value: '',
        value1: '',
        pickerOptions0: {},
        value2: '',
        finance: {

        },
        member: {

        },
        billCheckData: [],
        recordData: [],
        subData: [],
        billSubstituteData: [],
        swiperData:[],
        showImg:false,
        swiperSlides:[],
      }
    },
    mounted: function() {
      this.$nextTick(() => {
        api.auth(this);
        // this.getBillStatistics();
        this.getBillCheck();
        this.getRecordList();
        this.getBillSubstitute();
        this.getInfoRealTime();
      })
    },
    components: {
      examineDialog,
      whDialog,
      showImg,
      examine
    },
    methods: {
      getInfoRealTime(){
        api.getrealTime((data)=>{
          this.member=data.member;
          this.finance=data.finance;
        });
      },
      billCheckDataSort(column){
        let sortType=column.order=='ascending'? '' : '-' ;
        var sort='';
        if(column.order=='descending'){
          sort=sortType+column.prop;
        }else{
          sort=column.prop;
        }
        this.param1.sort=sort;
        this.getBillCheck();
      },
      recordDataSort(column){
        let sortType=column.order=='ascending'? '' : '-' ;
        var sort='';
        if(column.order=='descending'){
          sort=sortType+column.prop;
        }else{
          sort=column.prop;
        }
        this.param2.sort=sort;
        this.getRecordList();
      },
      billSubstituteDataSort(column){
        let sortType=column.order=='ascending'? '' : '-' ;
        var sort='';
        if(column.order=='descending'){
          sort=sortType+column.prop;
        }else{
          sort=column.prop;
        }
        this.param3.sort=sort;
        this.getBillSubstitute();
      },
      formatter(row, column) {
        return api.toThousands(row.face_amount);
      },
       closeShowImg(){
        this.showImg = false;
      },
      checkImg(index, rows) {
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
      search1() {
        this.getBillCheck();
      },
      search2() {
        this.getRecordList();
      },
      search3() {
        this.getBillSubstitute();
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
      changePage1(val) {
        this.param1.page = val;
        this.getBillCheck();
      },
      changePage2(val) {
        this.param2.page = val;
        // console.log(val)
        this.getRecordList();
      },
      changePage3(val) {
        this.param3.page = val;
        this.getBillSubstitute();
      },
      maintain(data) {
        // console.log(data);
        this.whShow2 = true;
        this.whParentData2 = data;
      },
      // 删除票据
      async billDelete(index, table) {
        api.billDelete(this, table[index].possessor_address,table[index].id,table[index].bill_number,  this, this.getBillSubstitute);
      },
      async getBillSubstitute() {
        // console.log('sss '+api.config.billSubstitute);
        const data = await api.get(this, api.config.billSubstitute, this.param3);
        // console.log('t' + data);
        // console.log(data);
        if (data.code == 200) {
          for(let x of data.data){
            x.annualized_rate_suggest =  x.annualized_rate_suggest ==  null ? '--' : '≤' + x.annualized_rate_suggest;
            x.face_amount = Number(x.face_amount.replace(/,/g,''));
          }
          this.total3 = data.count;
          this.billSubstituteData = data.data;
        } else {
          console.log(data.message);
        }
      },
      closeWhDialog() {
        this.getBillStatistics();
        this.getBillCheck();
        this.getRecordList();
        this.getBillSubstitute();
        this.whShow = false;
      },
      closeWhDialog2() {
        // console.log('eee2');
        this.whShow2 = false;
      },
      async showExamine(index, row) {
        this.whShow = true;
        const data = await api.get(this, api.config.billInfo, {
          id: row.id
        });
        // console.log(data);
        this.whParentData = data;
      },

      async getRecordList() {
        // console.log(api.config.checkRecordList);
        const data = await api.get(this, api.config.checkRecordList, this.param2);
        // console.log(data);
        if (data.code == 200) {
          for(let x of data.data){
            x.annualized_rate_suggest =  x.annualized_rate_suggest ==  null ? '--' : '≤' + x.annualized_rate_suggest;
            x.face_amount = Number(x.face_amount.replace(/,/g,''));
          }
          this.total2 = data.count;
          this.recordData = data.data;
        } else {
          console.log(data.message);
        }
      },
      async getBillCheck() {
        // console.log('zzz '+api.config.billCheck);
        const data = await api.get(this, api.config.billChecked, this.param1);
        // console.log(data);
        if (data.code == 200) {
          for(let x of data.data){
            x.face_amount = Number(x.face_amount.replace(/,/g,''));
          }
          this.total1 = data.count;
          this.billCheckData = data.data;
        } else {
          console.log(data.message);
        }
      },
      async getBillStatistics() {
        const data = await api.get(this, api.config.billStatistics);
        if (data.code == 200) {
          this.finance = data.finance;
          this.member = data.member;
        } else {
          console.log(data.message);
        }
      }

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
