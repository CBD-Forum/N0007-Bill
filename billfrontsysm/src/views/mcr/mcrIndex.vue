<template>
  <div class="mcrIndex">
    <div>
      <div class="clearfix">
        <div class="fcpi__box fl box-7">
          <div class="fcpi__row clearfix">
            <div class="fcpi__rowL fcpi__row100 fl cellTitle" style="padding-right:0;">
              <div class="fcpi__cell" style="padding-left:20px;">
                累计交易票据 <span class="spanblod">{{member.transaction}}元</span>
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
              <i class="pub_iconOran icon_green iconfont icon-jiaoyizhong"></i>
              <div class="fcpi__cell bgwihte">
                <span class="green">{{member.holding}}元</span>
                <span>持有中票据</span>
              </div>
            </div>
          </div>
        </div>
        <div class="fcpi__box fl box-3">
          <div class="fcpi__row clearfix">
            <div class="fcpi__rowL fl cellTitle">
              <router-link to="/user/billRecord">
                <div class="tiny__box">
                  录入票据
                </div>
              </router-link>
            </div>
            <div class="fcpi__rowR fl cellTitle">
              <router-link to="/user/tradeRecord">
                <div class="tiny__box">
                  交易记录
                </div>
              </router-link>
            </div>
          </div>
          <div class="fcpi__row clearfix">
            <div class="fcpi__rowL fl cellTitle">
              <i class="pub_iconOran icon_blue iconfont icon-yinpiao"></i>
              <div class="fcpi__cell bgwihte">
                <span class="blue">{{member.bank_entering}}</span>
                <span>银票数量</span>
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
      </div>
      <div class="clearfix tablemain">
        <div class="title">
          <div class="clearfix">
            <h2>待审核票据</h2>
            <div class="search-warp">
              <el-input class="search-warp__title" v-model="param1.query" placeholder="编号/承兑人/金额"> </el-input>
              <el-date-picker class="search-warp__date" v-model="param1.startTime" type="date" placeholder="到期日-从" :picker-options="pickerOptions0" @change="changeStartTime1">
              </el-date-picker>
              <el-date-picker class="search-warp__date" v-model="param1.endTime" type="date" placeholder="到期日-到" :picker-options="pickerOptions1" @change="changeEndTime1">
              </el-date-picker>
              <el-button class="search-warp__submit" size="small" @click="search1">搜索</el-button>
            </div>
          </div>
        </div>
        <div>
          <el-table :data="checkData" style="width: 100%;margin-top:10px;" stripe :default-sort="{prop: 'date', order: 'descending'}" @sort-change="sortchange1">
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
                <el-button v-show="checkData[scope.$index].status != '待审核'" type="primary" size="small" class="examine__submit" @click.native.prevent="billModify(scope.$index, checkData)">修改</el-button>
                <el-button v-if="checkData[scope.$index].is_traded==0" type="danger" size="small" class="examine__submit" @click.native.prevent="billDelete(scope.$index, checkData, 1)">删除</el-button>
              </template>
            </el-table-column>
          </el-table>
          <el-pagination class="table__page" layout="prev, pager, next" :total="total2" :page-size="5" @current-change="changePage1">
          </el-pagination>
        </div>
      </div>
      <div class="clearfix tablemain">
        <div class="title">
          <div class="clearfix">
            <h2>待维护票据</h2>
            <div class="search-warp">
              <el-input class="search-warp__title" v-model="param2.query" placeholder="编号/承兑人/金额"> </el-input>
              <el-date-picker class="search-warp__date" v-model="param2.startTime" type="date" placeholder="到期日-从" :picker-options="pickerOptions0">
              </el-date-picker>
              <el-date-picker class="search-warp__date" v-model="param2.endTime" type="date" placeholder="到期日-到" :picker-options="pickerOptions1">
              </el-date-picker>
              <el-button class="search-warp__submit" size="small" @click="getWaitingMainList()">搜索</el-button>
            </div>
          </div>
        </div>
        <div>
          <el-table :data="waitData" style="width: 100%;margin-top:10px;" stripe :default-sort="{prop: 'date', order: 'descending'}" @sort-change="sortchange2">
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
            <el-table-column prop="annualized_rate_suggest" label="审核利率（%）" min-width="110">
            </el-table-column>
            <el-table-column prop="state" label="附件" width="100">
              <template scope="scope">
                <i class="iconfont icon-wenjian" @click="checkImg(scope.$index,scope.row)"></i>
              </template>
            </el-table-column>
            <el-table-column label="操作" width="200">
              <template scope="scope">
                <el-button type="primary" size="small" class="examine__submit" @click="billWh(scope.$index, waitData)">维护</el-button>
                <el-button type="primary" size="small" class="examine__submit" @click="goguapai(scope.$index, waitData)">挂牌</el-button>
                <el-button v-if="waitData[scope.$index].is_traded==0" type="danger" size="small" class="examine__submit" @click.native.prevent="billDelete(scope.$index, waitData, 2)">删除</el-button>
              </template>
            </el-table-column>
          </el-table>
          <el-pagination class="table__page" layout="prev, pager, next" :total="total1" :page-size="5" @current-change="changePage2">
          </el-pagination>
        </div>
      </div>
    </div>
    <el-dialog class="tradedialog" title="提示" v-model="delateDialog" size="tiny">
      <span>是否确认确认删除?</span>
      <span slot="footer" class="dialog-footer" align="center">
          <el-button class='cancel-btn2' type="primary" @click="createTycd()">确认</el-button>
          <el-button class="cancel-btn" @click="delateDialog = false">考虑一下</el-button>
      </span>
    </el-dialog>
    <guaPai :dialogData="guapaiData" :isshow="isshowguapai" @reFlashBill="reFlashBill"></guaPai>
    <wh-dialog :whopen="whShow" :whDate="whParentData" :isSelf="true" v-on:close="closeWhDialog"></wh-dialog>
    <show-img v-show="showImg" :swiperData="swiperSlides" v-on:close="closeShowImg"></show-img>
    <bill-modify :whopen="bmopen" :id="ModifyId" v-on:close="closeModify"></bill-modify>
  </div>
</template>
<script>
import api from '../../api'
import whDialog from "../../components/wh.vue"
import guaPai from "../../components/guapaiDialog.vue"
import showImg from 'src/components/showImg'
import billModify from "../../components/billModify.vue"
import {temptime} from '../../../src/assets/js/common-js'
export default {
  data() {
      return {
        delateDialog:false,
        btnActive: false,
        ModifyId: '',
        bmopen: false,
        total1: 0,
        total2: 0,
        param1: {
          page: 1,
          pageSize:5,
          startTime: '',
          endTime: '',
          query: '',
          sort:'',
        },
        param2: {
          page: 1,
          startTime: '',
          pageSize:5,
          endTime: '',
          query: '',
          sort:'',
        },
        guapaiData:{},
        isshowguapai:0,
        whShow: false,
        whParentData: [],
        value: '',
        value1: '',
        pickerOptions0: {},
        value2: '',
        pickerOptions1: {},
        isAuth: sessionStorage.isAuth,
        member: {
          waiting_check:'',
          holding:'',
          bank_entering:'',
          commercial_entering:'',
          transaction:'',
        },
        btnText: '获取验证码',
        btnDis: false,
        checkData: [],
        waitData: [],
        fileList: [],
        dialogData: [],
        whopen: false,
        auth: {
          name: '',
          mobile: '',
          id_number: '',
          bank_card_number: '',
          captcha: ''
        },
        deleateInfo:{
          possessor_address:'',
          bill_number:'',
          sstype:'',
          id:'',
        },
        Billoption: [{
          value: '1',
          label: '银行承兑汇票'
        }, {
          value: '2',
          label: '商业承兑汇票'
        }],
        acceptor_type: [],
        acceptor_typea: [{
          value: '1',
          label: '企业',
        }],
        acceptor_typeb: [{
          value: '2',
          label: '国股'
        }, {
          value: '3',
          label: '城商'
        }, {
          value: '4',
          label: '农商'
        }, {
          value: '5',
          label: '外资'
        }, {
          value: '6',
          label: '农信'
        }, {
          value: '7',
          label: '财务公司'
        }],
        showImg: false,
        swiperSlides: [],
        restaurants: []
      }
    },
    mounted: function() {
      this.$nextTick(() => {
        // api.auth(this);
        this.getAgent();
        this.getInfoRealTime();
        if(api.auth(this)){
          this.getWaitingMainList();
          this.getCheckList();
        }
      })
    },
    computed: {
      headers: function() {
        return {
          Authorization: "Bearer " + sessionStorage.getItem('token'),
          Accept: "application/json; charset=utf-8"
        }
      }
    },
    components: {
      whDialog,
      showImg,
      billModify,
      guaPai
    },
    methods: {
      reFlashBill(){
        this.getWaitingMainList();
      },
      getInfoRealTime(){
        api.getrealTime((data)=>{
          this.member=data;
        });
      },
      sortchange1(column){
        let sortType=column.order=='ascending'? '' : '-' ;
        var sort='';
        if(column.order=='descending'){
          sort=sortType+column.prop;
        }else{
          sort=column.prop;
        }
        this.param1.sort=sort;
        this.getCheckList();
      },
      sortchange2(column){
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
        this.param2.sort=sort;
        this.getWaitingMainList();
        // let sort=column.order
      },
      formatter(row, column) {
        return api.toThousands(row.face_amount);
      },
      /* 选择融资经办人 */
      handleSelect: function(item) {
        this.auth = item;
      },
      querySearch(queryString, cb) {
        var restaurants = this.restaurants;
        var results = queryString ? restaurants.filter(this.createFilter(queryString)) : restaurants;
        // 调用 callback 返回建议列表的数据
        cb(results);
      },
      createFilter(queryString) {
        return (restaurant) => {
          return (restaurant.value.indexOf(queryString.toLowerCase()) === 0);
        };
      },
      closeModify() {
        this.bmopen = false;
        this.getCheckList();
      },
      async getAgent() {
        const data = await api.post(this, api.config.agentList);
        this.restaurants = data.data.map((e) => ({
          value: e.name,
          name: e.name,
          mobile: e.mobile,
          id_number: e.id_number,
          bank_card_number: e.bank_card_number
        }));
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
      closeShowImg() {
        this.showImg = false;
      },
      search1() {
        this.getCheckList();
      },
      search2() {
        this.getWaitingMainList();
      },
      changeStartTime1(e) {
        this.param1.startTime = e;
      },
      changeEndTime1(e) {
        this.param1.endTime = e;
      },
      changeStartTime2(e) {
        this.param2.startTime = e;
      },
      changeEndTime2(e) {
        this.param2.endTime = e;
      },
      changePage1(val) {
        this.param1.page = val;
        this.getCheckList();
      },
      changePage2(val) {
        this.param2.page = val;
        this.getWaitingMainList();
      },
      billWh(index, data) {
        this.whShow = true;
        console.log(data[index])
        this.whParentData = data[index];
      },
      closeWhDialog() {
        this.whShow = false;
        this.getWaitingMainList();
      },
      async modifyBill() {
        let temp = this.dialogData;
        temp.type = api.transBillType(temp.type);
        temp.acceptor_type = api.transAcceType(temp.acceptor_type);
        const data = await api.post(this, api.config.billUpdate, temp);
        if (data.code == 200) {
          this.$notify({
            title: '提示',
            message: data.message,
            type: 'success'
          });
        } else {
          this.$notify({
            title: '提示',
            message: data.message,
            type: 'warning'
          });
        }
      },
      typeChange(event) {
        // console.log(event);
        if (event == 1) {
          this.acceptor_type = this.acceptor_typeb;
        } else {
          this.acceptor_type = this.acceptor_typea;
        }
      },
      handleSuccess(response, file, fileList) {
        console.log(response);
        if (response.type == "10001") {
          this.dialogData.bill_front_path = response.path
        }
        if (response.type == "10002") {
          this.dialogData.bill_back_path = response.path
        }
      },
      handleError() {
        this.$notify.error({
          title: '错误',
          message: '图片上传失败'
        });
        return false
      },
      async billModify(index, table) {
        this.ModifyId = table[index].id;
        this.bmopen = true;
        return;

        this.whopen = true;
        const data = await api.get(this, api.config.billInfo, {
          id: table[index].id
        });
        if (data.code == 200) {
          this.dialogData = data;
        } else {
          this.$notify({
            title: '提示',
            message: data.message,
            type: 'warning'
          });
        }
        console.log(data);
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
      // 删除票据
      async billDelete(index, table, type) {
        this.delateDialog=true;
        this.deleateInfo.possessor_address=table[index].possessor_address;
        this.deleateInfo.bill_number=table[index].bill_number;
        console.log(this.deleateInfo.bill_number);
        this.deleateInfo.sstype=type;
        this.deleateInfo.id=table[index].id;
      },
      createTycd(){
        this.delateDialog=false;
        api.billDelete(this,this.deleateInfo.possessor_address,this.deleateInfo.id,this.deleateInfo.bill_number,this,(data)=>{
          this.getWaitingMainList();
          this.getCheckList();
        });
      },
      // 待维护票据
      async getWaitingMainList() {
        this.param2.startTime=temptime(this.param2.startTime);
        this.param2.endTime=temptime(this.param2.endTime);
        const data = await api.get(this, api.config.waitingMainList, this.param2);
        if (data.code == 200) {
          for (let x of data.data) {
            x.annualized_rate_suggest = x.annualized_rate_suggest == null ? '--' : '≤' + x.annualized_rate_suggest;
            x.face_amount = Number(x.face_amount.replace(/,/g, ''));
          }
          this.total1 = data.count;
          this.waitData = data.data;
        } else {
          console.log(data.message);
        }
      },
      // 待审核票据
      async getCheckList() {
        this.param1.startTime=temptime(this.param1.startTime);
        this.param1.endTime=temptime(this.param1.endTime);
        const data = await api.get(this, api.config.waitingCheckList, this.param1);
        if (data.code == 200) {
          for (let x of data.data) {
            x.face_amount = Number(x.face_amount.replace(/,/g, ''));
          }
          this.total2 = data.count;
          this.checkData = data.data;
        } else {
          console.log(data.message);
        }
      },
      // 操作员认证
      async authCer() {
        this.btnActive = true;
        const data = await api.post(this, api.config.authCer, this.auth);
        this.btnActive = false;
        if (data.code == 200) {
          this.$notify({
            title: '提示',
            message: '验证成功',
            type: 'success'
          });
          // this.isAuth = 1;
          sessionStorage.isAuth = '1';
          // this.getBillStatistics();
          // this.getCheckList();
          // this.getWaitingMainList();
        } else {
          this.$notify({
            title: '提示',
            message: data.message,
            type: 'warning'
          });
        }
      },
      // 票据累计信息
      async getBillStatistics() {
        const data = await api.get(this, api.config.billStatistics);
        if (data.code == 200) {
          // console.log(data)
          this.member = data.member;
        } else {
          console.log(data.message);
        }
      },
      limitSend() {
        this.btnDis = true;
        let nexttime = 60;
        this.btnText = nexttime + 's重新发送';
        let ltime = setInterval(() => {
          nexttime = nexttime - 1;
          this.btnText = nexttime + 's重新发送';
          if (nexttime == 0) {
            clearInterval(ltime);
            this.btnDis = false;
            this.btnText = '获取验证码';
          }
        }, 1000);
      },
      // 发送验证码
      async sendCaptcha() {
        if (this.auth.mobile == '') {
          this.$notify({
            title: '提示',
            message: '手机号不能为空',
            type: 'warning'
          });
          return;
        }
        const data = await api.post(this, api.config.sendCaptcha, {
          mobile: this.auth.mobile
        });
        if (data.code == 200) {
          this.$notify({
            title: '提示',
            message: '发送成功',
            type: 'success'
          });
          this.limitSend();
        } else {
          this.$notify({
            title: '提示',
            message: data.message,
            type: 'warning'
          });
        }
      }
    }
}
</script>
<style>
.mcrIndex{
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
.el-autocomplete {
  width: 100%;
}

.pub__main {
  height: 870px;
}

.codeBtn {
  float: right;
  width: 92%;
  height: 32px;
  margin-top: 2px;
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
  width: 50%;
  padding-right: 4px;
}

.fcpi__row100 {
  width: 100%;
}

.fcpi__rowR {
  width: 50%;
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
  padding-right: 2px;
}

.fcpi__tiny:nth-child(2) {
  padding: 0;
  padding-left: 2px;
}

.tiny__box {
  color: #fff;
  height: 50px;
  line-height: 50px;
  text-align: center;
  background: #207fb7;
}

.box-7 {
  width: 70%;
}

.box-3 {
  width: 30%;
}

</style>
