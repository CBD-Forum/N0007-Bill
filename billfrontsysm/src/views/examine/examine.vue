<template>
  <div class="examine">
    <div class="clearfix">
      <div class="title">
        <div class="title-tabs clearfix">
          <a v-if="logintype=='fuhe'" :class = "{active: tabsShowOne}"  @click= "changeTabs('one')">财务公司票据审核</a>
          <a v-else :class = "{active: tabsShowOne}"  @click= "changeTabs('one')">成员公司票据审核</a>
          <a :class = "{active: !tabsShowOne}" @click= "changeTabs('two')">历史审核</a>
          <div class="search-warp">
            <el-input class="search-warp__title" v-model="searchDate.query"  placeholder="编号/承兑人/金额"> </el-input>
            <el-select v-show="examineType" class="search-warp__type" v-model="searchDate.status" placeholder="请选择">
              <el-option
                v-for="item in options"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
            <el-date-picker
              class="search-warp__date"
              v-model="searchDate.startTime"
              type="date"
              :editable="false"
              placeholder="到期日-从"
              @change = "changeStartTime"
              :picker-options="pickerOptions1">
            </el-date-picker>
            <el-date-picker
              class="search-warp__date"
              v-model="searchDate.endTime"
              type="date"
              :editable="false"
              placeholder="到期日-到"
              @change = "changeEndTime"
              :picker-options="pickerOptions1">
            </el-date-picker>
            <el-button class="search-warp__submit" @click="serachSubmit" size="small">搜索</el-button>
          </div>
        </div>
      </div>
      <div class="tabs-content">
        <div v-show="tabsShowOne">
          <el-table
          :data="billWaitCheckData.data"
          style="width: 100%;margin-top:10px;"
          stripe
          :default-sort = "{prop: 'date', order: 'descending'}" @sort-change="billWaitCheckDataSort"
          >
            <el-table-column
              prop="id"
              label="序号"
              width="80">
            </el-table-column>
            <el-table-column
              prop="enterprise_name"
              label="申请企业"
              min-width="180"
              class-name="companyname">
            </el-table-column>
            <el-table-column
              prop="bill_number"
              label="票据编号"
              width="180">
            </el-table-column>
            <el-table-column
              prop="acceptor"
              label="承兑人"
              min-width="180">
            </el-table-column>
            <el-table-column
              prop="type"
              label="票据类型"
              sortable
              width="120">
            </el-table-column>
            <el-table-column
              prop="acceptance_at"
              label="到期日"
              sortable
              width="120">
            </el-table-column>
            <el-table-column
              prop="face_amount"
              label="票据金额（元）"
              sortable
              min-width="180"
              :formatter="formatter">
            </el-table-column>
            <el-table-column
              prop="apply_at"
              label="申请时间"
              sortable
              min-width="160">
            </el-table-column>
            <el-table-column
              label="审核状态"
              width="100">
              <template scope="scope">
                待审核
              </template>
            </el-table-column>
            <el-table-column
              label="操作"
              min-width="100">
              <template scope="scope">
                <el-button type="primary" size="small" @click="showExamine(scope.$index,scope.row)" class="examine__submit2">审核</el-button>
              </template>
            </el-table-column>
          </el-table>
          <el-pagination class="table__page"
            layout="prev, pager, next"
            @current-change="changePage"
            :page-size = "5"
            :total='billWaitCheckData.count'>
          </el-pagination>
        </div>
        <div v-show="!tabsShowOne">
          <el-table
          :data="billCheckedData.data"
          style="width: 100%;margin-top:10px;"
          stripe
          :default-sort = "{prop: 'date', order: 'descending'}"
          @sort-change="billCheckedDataSort">
            <el-table-column
              prop="id"
              label="序号"
              width="80">
            </el-table-column>
            <el-table-column
              prop="applicant"
              label="申请企业"
              min-width="180"
              class-name="companyname">
            </el-table-column>
            <el-table-column
              prop="bill_number"
              label="票据编号"
              width="180">
            </el-table-column>
            <el-table-column
              prop="acceptor"
              label="承兑人"
              min-width="180">
            </el-table-column>
            <el-table-column
              prop="bill_type"
              label="票据类型"
              sortable
              width="120">
            </el-table-column>
            <el-table-column
              prop="acceptance_at"
              label="到期日"
              sortable
              width="120">
            </el-table-column>
            <el-table-column
              prop="face_amount"
              label="票据金额（元）"
              sortable
              min-width="180"
              :formatter="formatter">
            </el-table-column>
            <el-table-column
              prop="apply_at"
              label="申请时间"
              sortable
              min-width="160">
            </el-table-column>
            <el-table-column
              prop="created_at"
              label="审核时间"
              sortable
              min-width="160">
            </el-table-column>
            <el-table-column
              prop="annualized_rate_suggest"
              label="审核利率（%）"
              min-width="140">
            </el-table-column>
            <el-table-column
              prop="status"
              label="审核状态"
              width="100">
            </el-table-column>
            <el-table-column
              label="票据审核"
              width="100">
              <template scope="scope">
                <el-button type="primary" size="small" @click="showExamine2(scope.$index,scope.row)" class="examine__submit2">查看</el-button>
              </template>
            </el-table-column>
          </el-table>
          <el-pagination class="table__page"
            layout="prev, pager, next"
            @current-change="changePage"
            :total='billCheckedData.count'
            :page-size="20">
          </el-pagination>
        </div>
      </div>
    </div>

    <div class="clearfix">
      <div class="title">
        <div class="clearfix">
          <h2 v-if="companyType==1">代成员公司维护票据</h2>
          <h2 v-if="companyType==2">代财务公司维护票据</h2>
          <div class="search-warp"> <!--  -->
            <el-input class="search-warp__title" v-model="searchAgentDate.query"  placeholder="编号/承兑人/金额"> </el-input>
            <el-date-picker
              class="search-warp__date"
              v-model="searchAgentDate.startTime"
              type="date"
              :editable="false"
              placeholder="到期日-从"
              @change = "changeAgentStartTime"
              :picker-options="pickerOptions1">
            </el-date-picker>
            <el-date-picker
              class="search-warp__date"
              v-model="searchAgentDate.endTime"
              type="date"
              :editable="false"
              placeholder="到期日-到"
              @change = "changeAgentEndTime"
              :picker-options="pickerOptions1">
            </el-date-picker>
            <el-button class="search-warp__submit" @click="substituteSubmit" size="small">搜索</el-button>
          </div>
        </div>
      </div>
      <div>
        <el-table
          :data="maintenanceData.data"
          style="width: 100%;margin-top:10px;"
          stripe
          :default-sort = "{prop: 'date', order: 'descending'}" @sort-change="maintenanceDataSort"
          >
            <el-table-column
              prop="id"
              label="序号"
              width="80">
            </el-table-column>
            <el-table-column
              prop="enterprise_name"
              label="申请企业"
              min-width="180"
              class-name="companyname">
            </el-table-column>
            <el-table-column
              prop="acceptor"
              label="承兑人"
              width="180">
            </el-table-column>
            <el-table-column
              prop="type"
              label="票据类型"
              sortable
              min-width="120">
            </el-table-column>
            <el-table-column
              prop="acceptance_at"
              label="到期日"
              sortable
              min-width="120">
            </el-table-column>
            <el-table-column
              prop="left_day"
              label="剩余天数"
              sortable
              min-width="120">
            </el-table-column>
            <el-table-column
              prop="face_amount"
              label="票据金额（元）"
              sortable
              min-width="180"
              :formatter="formatter">
            </el-table-column>
            <el-table-column
              prop="annualized_rate_suggest"
              label="审核利率（%）"
              min-width="110">
            </el-table-column>
            <el-table-column
              prop="state"
              label="附件"
              width="100">
              <template scope="scope">
                <i class="iconfont icon-wenjian" @click="checkImg(scope.$index,scope.row)"></i>
              </template>
            </el-table-column>
            <el-table-column
              label="操作"
              width="180">
              <template scope="scope">
                <el-button type="primary" size="small" @click="maintain(maintenanceData.data[scope.$index])" class="examine__submit">维护</el-button>
                <el-button v-if="maintenanceData.data[scope.$index].is_traded==0" type="danger" size="small" class="examine__submit" @click.native.prevent="billDelete(scope.$index, maintenanceData)">删除</el-button>
              </template>
            </el-table-column>
          </el-table>
          <el-pagination class="table__page"
            layout="prev, pager, next"
            :page-size = "5"
            :total='maintenanceData.count'
            @current-change="maintenanceDataPage"
            >
          </el-pagination>
      </div>
    </div>
    <wh-dialog :whopen="whShow" :whDate="whParentData" v-on:close= "closeWhDialog"></wh-dialog>
    <show-img v-show="showImg" :swiperData="swiperSlides"  v-on:close= "closeShowImg"></show-img>
    <examineDialog :whopen="whShow2" :whDate="whParentData2" v-on:close="closeWhDialog2"></examineDialog>
    <examineDialog3 :whopen="whShow3" :whDate="whParentData3" v-on:close="closeWhDialog3"></examineDialog3>
  </div>
</template>
<script>
import examineDialog from 'components/examineDialog'
import examineDialog3 from 'components/examineDialog2'
import whDialog from "../../components/wh.vue"
import api from '../../api'
import showImg from 'src/components/showImg'
import {temptime} from '../../../src/assets/js/common-js'
export default {
  props:['logintype'],
  data() {
    return {
      showImg:false,
      examineType:false,
      whShow:false,
      whShow2:false,
      whShow3:false,
      companyType:sessionStorage.type,
      tabsShowOne: true,
      tabsShowTwo: true,
      whParentData: [],
      whParentData2: [],
      whParentData3:[],
      billWaitCheckData: [],
      billCheckedData: [],
      maintenanceData: [],
      annualized_rate_suggest:"",
      searchDate:{
        query:"",
        startTime:"",
        endTime:"",
        status:"",
        sort:'',
      },
      searchAgentDate:{
        page:1,
        pageSize:5,
        query:"",
        startTime:"",
        endTime:"",
        sort:'',
      },
      options: [{
        value: '',
        label: '请选择'
      },{
        value: '1',
        label: '已通过'
      }, {
        value: '2',
        label: '未通过'
      }],
      pickerOptions0: {

      },
      pickerOptions1: {

      },
      examineInfo:{},
      swiperSlides:[],
    }
  },
  mounted: function() {
    this.$nextTick(() => {
      api.auth(this);
      this.getBillcheck();
      this.getBillAgent();
    })
  },
  components:{
    whDialog,
    showImg,
    examineDialog,
    examineDialog3
  },
  methods: {
    billWaitCheckDataSort(column){
      let sortType=column.order=='ascending'? '' : '-' ;
        var sort='';
        if(column.order=='descending'){
          sort=sortType+column.prop;
        }else{
          sort=column.prop;
        }
        this.searchDate.sort=sort;
        this.getBillcheck(this.searchDate);
    },
    billCheckedDataSort(column){
      let sortType=column.order=='ascending'? '' : '-' ;
      var sort='';
      if(column.order=='descending'){
        sort=sortType+column.prop;
      }else{
        sort=column.prop;
      }
      this.searchDate.sort=sort;
      this.getBillchecked(this.searchDate);
    },
    maintenanceDataSort(column){
      let sortType=column.order=='ascending'? '' : '-' ;
      var sort='';
      if(column.prop=='left_day'){
        column.prop='acceptance_at';
      }
      if(column.order=='descending'){
        sort=sortType+column.prop;
      }else{
        sort=column.prop;
      }
      this.searchAgentDate.sort=sort;
      this.getBillAgent();
    },
    formatter(row, column) {
      return api.toThousands(row.face_amount);
    },
    // 删除票据
    async billDelete(index, table) {
      // console.log(table.data[index]);
      // return;
      console.log(table);
      api.billDelete(this, table.data[index].possessor_address,table.data[index].id,table.data[index].bill_number, this, this.getBillAgent);
    },
    maintain(data) {
      // console.log(data);
      this.whShow = true;
      this.whParentData = data;
    },
    closeWhDialog(){
      this.getBillAgent();
      this.whShow = false;
    },
    closeWhDialog2(){
      this.getBillcheck();
      this.getBillAgent();
      this.whShow2 = false;
    },
    closeWhDialog3(){
      this.getBillcheck();
      this.getBillAgent();
      this.whShow3 = false;
    },
    async getBillcheck(options) {
      const data = await api.get(this, api.config.checkWaitingList,options);
      for(let x of data.data){
        x.face_amount = Number(x.face_amount.replace(/,/g,''));
      }
      this.billWaitCheckData.count = Number(data.count);
      this.billWaitCheckData = data;
    },
    async getBillchecked(options) {
      const data = await api.get(this, api.config.billChecked,options);
      for(let x of data.data){
        x.face_amount = Number(x.face_amount.replace(/,/g,''));
        x.annualized_rate_suggest = x.annualized_rate_suggest ==  null ||  x.annualized_rate_suggest==0 ? '--' : '≤' + x.annualized_rate_suggest;
      }
      this.billCheckedData = data;
    },
    async getBillAgent() {
      const data = await api.get(this, api.config.billSubstitute,this.searchAgentDate);
      // console.log(data);
      for(let x of data.data){
        x.left_day = Number(x.left_day);
        x.face_amount = Number(x.face_amount.replace(/,/g,''));
        x.annualized_rate_suggest =  x.annualized_rate_suggest ==  null ||  x.annualized_rate_suggest==0? '--' : '≤' + x.annualized_rate_suggest;
      }
      this.maintenanceData = data;
    },
    maintenanceDataPage(val){
      this.searchAgentDate.page=val;
      this.getBillAgent();
    },
    changeTabs(type){
      if(type == "one"){
        this.examineType = false;
        this.tabsShowOne=true;
        this.searchDate={
          query:"",
          startTime:"",
          endTime:"",
          status:""
        };
        this.getBillcheck();
      }else{
        this.tabsShowOne=false;
        this.examineType = true;
        this.searchDate={
          query:"",
          startTime:"",
          endTime:"",
          status:"",
        };
        this.getBillchecked(this.searchDate);
      }
    },
    changeStartTime(events){
      // console.log(events);
      this.searchDate.startTime = events;
    },
    changeAgentStartTime(events){
      this.searchAgentDate.startTime = events;
    },
    changeAgentEndTime(events){
      this.searchAgentDate.endTime = events;
    },
    changeEndTime(events){
      // console.log(events);
      this.searchDate.endTime = events;
    },
    serachSubmit(){
      if(new Date(this.searchDate.startTime).getTime() > new Date(this.searchDate.endTime).getTime()){
        this.$notify.error({
          title: '错误',
          message: '开始时间必须小于结束时间'
        });
        return false;
      }else{
        this.searchDate.startTime=temptime(this.searchDate.startTime);
        this.searchDate.endTime=temptime(this.searchDate.endTime);
        if(!this.examineType){
          this.getBillcheck(this.searchDate);
        }else{
          this.getBillchecked(this.searchDate);
        }
      }
    },
    substituteSubmit(){
      if(new Date(this.searchAgentDate.startTime).getTime() > new Date(this.searchAgentDate.endTime).getTime()){
        this.$notify.error({
          title: '错误',
          message: '开始时间必须小于结束时间'
        });
        return false;
      }else{
        this.searchAgentDate.startTime=temptime(this.searchAgentDate.startTime);
        this.searchAgentDate.endTime=temptime(this.searchAgentDate.endTime);
        this.getBillAgent();
      }
    },
    async showExamine(index,row){
      // this.examineShow = true;
      this.whShow2 = true;
      const data = await api.get(this, api.config.billInfo,{id:row.id});
      // console.log(data);
      this.whParentData2 = data;
    },
    async showExamine2(index,row){
      // this.examineShow = true;
      const data = await api.get(this, api.config.histroyBillInfo,{id:row.id});
      // console.log(data);
      this.whParentData3 = data;
      this.whShow3 = true;
    },
    async examineSubmit(id){
      let checkdata = {};
      for(var checkI in this.checkList){
        if(this.checkList[checkI]){
          checkdata[checkI] = 1
        }else{
          checkdata[checkI] = 0
        }
      }
      const data = await api.post(this, api.config.billExamine,{
        id:id,
        annualized_rate_suggest:this.annualized_rate_suggest,
        field:checkdata
      });
      if(data.code == 200){
        this.$notify({
          title: '成功',
          message: '票据审核成功',
          type: 'success'
        });
        this.examineShow = false;
        this.getBillcheck();
      }else{
        this.$notify({
          title: '提示',
          message: data.message,
          type: 'warning'
        });
        this.getBillcheck();
      }
    },
    checkImg(index, rows){
      // console.log(rows.bill_front_path);
      var swiperData = [{
        title:"票据正面",
        src:rows.bill_front_path
      },{
        title:"票据背面",
        src:rows.bill_back_path
      }]
      this.swiperSlides=swiperData;
      this.showImg = true;
    },
    closeShowImg(){
      this.showImg = false;
    },
    changePage(size){
      if(new Date(this.searchDate.startTime).getTime() > new Date(this.searchDate.endTime).getTime()){
        this.$notify.error({
          title: '错误',
          message: '开始时间必须小于结束时间'
        });
        return false;
      }else{
        var pageData ={
          page:size
        };
        Object.assign(pageData, this.searchDate);
        // console.log(pageData);
        if(!this.examineType){
          this.getBillcheck(pageData);
        }else{
          this.getBillchecked(pageData);
        }
      }
    }
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
  .examine{
    &>div:nth-child(1),&>div:nth-child(2){
      bg:#fff;
      bdt:#2fa4e7 3px solid;
      p:0px 20px;
      mb:10px;
    }
    &__submit{
      /*width:60px;*/
    }
    .table__page{
      margin: 14px auto;
      text-align:center;
    }
    .examineDialog{
      .el-dialog{
        height:550px;
      }
      .whcontent__img{
        &>img{
          &:last-child{
            margin-bottom: 0px;
          }
        }
      }
    }
    .examineInfo{
      float: left;
      font-size:14px;
      color:#2fa4e7;
    }
  }
</style>
