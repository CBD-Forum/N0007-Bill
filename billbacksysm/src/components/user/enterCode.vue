<template>
  <div class="tradelist" v-loading.body="mainLoading">
    <el-row>
      <el-col :span="24" class="bill-list" >
        <div class="top-title" style="">
          <img src="../../assets/images/faxing.png" alt="">认证管理</div>
        <div class="table1">
          <table>
            <thead>
              <tr class="ttop">
                <th>企业名称</th>
                <th>用户名</th>
                <th>企业信息</th>
                <th>工商信息</th>
                <th>法人信息</th>
                <th>ECDS信息</th>
                <th>提款银行</th>
                <!-- <th>授权人</th>
              -->
              <th>审查</th>
            </tr>
          </thead>
          <tbody v-loading.body="authMana">
            <tr class="ttbody" v-for = "e in tableData1">
              <td>{{e.enterprise_name}}</td>
              <td>{{e.user_name}}</td>
              <td>
                <a style="cursor:pointer;color:#3c8dbe;" @click="searchEnterpriseInfo(e.enterprise_id)">查看</a>
              </td>
              <td>
                <a style="cursor:pointer;color:#3c8dbe;" @click="searchBussinessInfo(e.enterprise_id)">查看</a>
              </td>
              <td>
                <a style="cursor:pointer;color:#3c8dbe;" @click="searchLegalPerson(e.enterprise_id)">查看</a>
              </td>
              <td>
                <a style="cursor:pointer;color:#3c8dbe;" @click="searchECDS(e.enterprise_id)">查看</a>
              </td>
              <td>
                <a style="cursor:pointer;color:#3c8dbe;" @click="searchBankInfo(e.enterprise_id)">查看</a>
              </td>
              <!-- <td></td>
            -->
            <td>
              <a style="cursor:pointer;color:#3c8dbe;" @click="auditConfirm(e.enterprise_id)">通过</a>
              <a style="cursor:pointer;color:#3c8dbe;" @click="auditFailed(e.enterprise_id)">不通过</a>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="notable-data" v-if="billShow0">暂无数据</div>
    </div>
    <div class="block">
      <el-pagination
                      :current-page="1"
                      :page-size="5"
                      layout="total, prev, pager, next"
                      :total="remitParam0.total"
                      @current-change="handleCurrentChange0"></el-pagination>
    </div>
  </el-col>
</el-row>
<el-row>
  <el-col :span="24" class="bill-list" >
    <div class="top-title" style="">
      <img src="../../assets/images/faxing.png" alt="">自动开户失败账号</div>
    <div class="table1">
      <table>
        <thead>
          <tr class="ttop">
            <th>企业名称</th>
            <th>用户名</th>
            <th>状态</th>
            <!-- <th>授权人</th>
          -->
          <th>操作</th>
        </tr>
      </thead>
      <tbody v-loading.body="authMana">
        <tr class="ttbody" v-for="e in activeHistoryList">
          <td>{{e.enterprse_name}}</td>
          <td>{{e.user_name}}</td>
          <td>{{e.status}}</td>
          <!-- <td></td>
        -->
        <td>
          <button class="buttonsss" @click="goactiveMember(e.user_id,e.public_key)">激活</button>
        </td>
      </tr>
    </tbody>
  </table>
  <!-- <div class="notable-data" v-if="billShow0">暂无数据</div>
-->
</div>
<div class="block">
<el-pagination
  :current-page="1"
  :page-size="5"
  layout="total, prev, pager, next"
  :total="remitParam0.total"
  @current-change="handleCurrentChange0"></el-pagination>
</div>
</el-col>
</el-row>
<el-row>
<el-col :span="24" class="bill-list" style="margin-top:40px;">
<div class="top-title" style="">
<img src="../../assets/images/faxing.png" alt="">验证码</div>
<div class="table1">
<table>
  <thead>
    <tr class="ttop">
      <th>企业名称</th>
      <th>用户名</th>
      <th>银行账号</th>
      <th>开户行</th>
      <th>验证款</th>
      <th>操作</th>
    </tr>
  </thead>
  <tbody v-loading.body="authCode">
    <tr class="ttbody" v-for="e in remitData">
      <td>{{e.enterprise_name}}</td>
      <td>{{e.user_name}}</td>
      <td>{{e.account}}</td>
      <td>{{e.bankCode + e.sBankCode}}</td>
      <td>{{e.amount}}</td>
      <td>
        <a style="cursor:pointer;color:#3c8dbe;" @click="remitConfirm(e.enterprise_id)">打款</a>
        <a style="cursor:pointer;color:#3c8dbe;" @click="remitFailed(e.enterprise_id)">打款失败</a>
      </td>
    </tr>
  </tbody>
</table>
<div class="notable-data" v-if="billShow1">暂无数据</div>
</div>
<div class="block">
<el-pagination
  :current-page="1"
  :page-size="5"
  layout="total, prev, pager, next"
  :total="remitParam.total"
  @current-change="handleCurrentChange1"></el-pagination>
</div>
</el-col>
</el-row>

<el-row style="margin-top:40px;">
<el-col :span="24" class="bill-list" >
<div class="top-title" style="">
<img src="../../assets/images/faxing.png" alt="">认证记录</div>
<div class="table1">
<table v-loading.body="authHistory">
  <thead>
    <tr class="ttop">
      <th>企业名称</th>
      <th>用户名</th>
      <th>法人</th>
      <!-- <th>授权人</th>
    -->
    <th>银行账号</th>
    <th>开户行</th>
    <th>验证款</th>
    <th>状态</th>
  </tr>
</thead>
<tbody>
  <tr class="ttbody" v-for="e in historyData">
    <td>{{e.enterprise_name}}</td>
    <td>{{e.user_name}}</td>
    <td>{{e.legal_person}}</td>
    <!-- <td></td>
  -->
  <td>{{e.bank_card_number}}</td>
  <td>{{e.bank}}</td>
  <td>{{e.amount | filterAmount}}</td>
  <td>{{e.type}}</td>
</tr>
</tbody>
</table>
<div class="notable-data" v-if="billShow2">暂无数据</div>
</div>
<div class="block">
<el-pagination
                      :current-page="1"
                      :page-size="5"
                      layout="total, prev, pager, next"
                      :total="historyParam.total"
                      @current-change="handleCurrentChange2"></el-pagination>
</div>
</el-col>
</el-row>

<el-dialog title="企业信息" v-model="dialog1">
<el-form :model="form">
<el-row>
<el-col :push="16" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>企业名称</el-col>
<el-col :span='8'>{{dialogData1.name}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>营业执照</el-col>
<el-col :span='8'>{{dialogData1.licence}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>区域</el-col>
<el-col :span='8'>{{dialogData1.area}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>地址</el-col>
<el-col :span='8'>{{dialogData1.address}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>社会信用代码</el-col>
<el-col :span='8'>{{dialogData1.socialCreditCode}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<swiper :options="swiperOption">
<swiper-slide v-for="slide in swiperSlides2">
<div>
<img :src="slide.img" alt="" style="max-width:100%;max-height:600px;margin-top:20px;"></div>
</swiper-slide>
<div class="swiper-button-prev" slot="button-prev"></div>
<div class="swiper-button-next" slot="button-next"></div>
</swiper>
</el-form>
<div slot="footer" class="dialog-footer"></div>
</el-dialog>

<el-dialog title="工商信息" v-model="dialog2">
<el-form :model="form">
<el-row>
<el-col :push="16" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>注册日期</el-col>
<el-col :span='8'>{{dialogData2.reg_date}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>注册资金</el-col>
<el-col :span='8'>{{dialogData2.reg_capital}}万元</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>开户币种</el-col>
<el-col :span='8'>{{dialogData2.currency}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<img :src="dialogData2.licence_for_opening_accounts" alt="" style="max-width:100%;max-height:600px;margin-top:20px;"></el-form>
<div slot="footer" class="dialog-footer"></div>
</el-dialog>

<el-dialog title="法人信息" v-model="dialog3">
<el-form :model="form">
<el-row>
<el-col :push="16" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>法人姓名</el-col>
<el-col :span='8'>{{dialogData3.legalPersonNm}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>法人邮箱</el-col>
<el-col :span='8'>{{dialogData3.mailAddress}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>联系人</el-col>
<el-col :span='8'>{{dialogData3.contacts}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>联系人手机</el-col>
<el-col :span='8'>{{dialogData3.contact_number}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<swiper :options="swiperOption">
<swiper-slide v-for="slide in swiperSlides">
<div>
<img :src="slide.img" alt="" style="max-width:100%;max-height:600px;margin-top:20px;"></div>
</swiper-slide>
<div class="swiper-button-prev" slot="button-prev"></div>
<div class="swiper-button-next" slot="button-next"></div>
</swiper>
</el-form>
<div slot="footer" class="dialog-footer"></div>
</el-dialog>

<el-dialog title="ECDS信息" v-model="dialog4">
<el-form :model="form">
<el-row>
<el-col :push="16" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>持卡人姓名</el-col>
<el-col :span='8'>{{dialogData4.name}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>银行卡号</el-col>
<el-col :span='8'>{{dialogData4.bank_card_number}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>银行行号</el-col>
<el-col :span='8'>{{dialogData4.bank_code}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
</el-form>
<div slot="footer" class="dialog-footer"></div>
</el-dialog>

<el-dialog title="提款银行" v-model="dialog5">
<el-form :model="form">
<el-row>
<el-col :push="16" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>持卡人</el-col>
<el-col :span='8'>{{dialogData5.userName}}</el-col>
<el-col :push="4" :span='8'>
<!-- <el-checkbox v-model="checked2.checked1" disabled></el-checkbox>
-->
</el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>银行账号</el-col>
<el-col :span='8'>{{dialogData5.account}}</el-col>
<el-col :push="4" :span='8'>
<!-- <el-checkbox v-model="checked2.checked2" disabled></el-checkbox>
-->
</el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>开户行</el-col>
<el-col :span='8'>{{dialogData5.bankName}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<el-row class="rowb">
<el-col :span='8'>支行</el-col>
<el-col :span='8'>{{dialogData5.sBankCode}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
<!-- <el-row class="rowb">
<el-col :span='8'>地区</el-col>
<el-col :span='8'>{{dialogData5.province}}</el-col>
<el-col :push="4" :span='8'></el-col>
</el-row>
-->
<!--   <swiper :options="swiperOption">
<swiper-slide v-for="slide in swiperSlides">
<div>
<img :src="slide.text" alt="" style="max-width:100%;max-height:600px;margin-top:20px;"></div>
</swiper-slide>
<div class="swiper-button-prev" slot="button-prev"></div>
<div class="swiper-button-next" slot="button-next"></div>
</swiper>
-->
</el-form>
<div slot="footer" class="dialog-footer"></div>
</el-dialog>
<el-dialog title="操作中" v-model="dialogLoading" size="tiny">
<span>请耐心等待...</span>
</el-dialog>
</div>
</template>
<script>
import { fetchRemitList, fetchRemitCode,fetchRemitHistory,postUrl,fetch,activeHistory,userkey,billNoteSigns } from '../../../src/assets/js/api'
export default {
    data() {
          return {
              swiperOption:{
                  pagination: '.swiper-pagination',
                  nextButton: '.swiper-button-next',
                  prevButton: '.swiper-button-prev',
                  slidesPerView: 1,
                  slidesPerColumn: 1,
                  paginationClickable: true,
              },
              swiperSlides: [],
              swiperSlides2: [],
              tableData1:[],            // 认证管理
              remitData:[],             // 验证码
              historyData:[],           // 认证记录
              remitParam0:{            // 认证管理
                  total:0,
                  page:1,
                  pageSize:5,
              },
              activeList:{            // 认证管理
                  total:0,
                  page:1,
                  pageSize:5,
              },
              remitParam:{             // 验证码
                  total:0,
                  page:1,
                  pageSize:5,
              },
              billNoteSigns:'',
              historyParam:{           // 认证记录
                  total:0,
                  page:1,
                  pageSize:5,
              },
              blockBody:{
                user_id:'',
                signature:'',
                instruction_id:'',
              },
              activeHistoryList:[],
              billShow0:false,
              billShow1:false,
              billShow2:false,
              dialog1:false,
              dialog2:false,
              dialog3:false,
              dialog4:false,
              dialog5:false,
              dialogLoading:false,
              dialogData1:{},
              dialogData2:{},
              dialogData3:{},
              dialogData4:{},
              dialogData5:{},
              authMana:false,
              authCode:false,
              authHistory:false,
              // mainLoading:true,
              mainLoading:false,
          }
        },
        mounted: function() {
            this.$nextTick(() => {
                // this.billNoteSigns=new billSign();
                this.main();
                this.getactivelist();
            })
        },
        methods: {
           goactiveMember(userid,publickey){
            let result= billNoteSigns().signUserRegister(userkey(),publickey,userid,1);
            this.blockBody.signature=result.signdata;
            this.blockBody.instruction_id=result.sid;
            this.blockBody.user_id=userid;
            postUrl('/user/activate-external',this.blockBody).then(data=>{
              if(data.code==200){
                this.$notify({
                  title:'提示',
                  message:'操作成功',
                  type:'success'
                });
                this.getactivelist();
              }else{
                this.$notify({
                  title:'提示',
                  message:data.message,
                  type:'error'
              });
              }
            })
           },
            main() {
                // 为了在最慢的接口调用完成后再消失动画，用了promise.all方法并行执行ajax
                Promise.all([fetchRemitList(), fetchRemitCode(), fetchRemitHistory()]).then(data => {
                    for(let x in data){
                        this.handleCallbackDate(Number(x), data[x]);
                    }
                    this.mainLoading = false;
                });
                // this.remitList();
                // this.remitCode();
            },
            /**
             * [获取认证管理列表]
             */
            remitList(){
                this.authMana = true;
                fetchRemitList(this.remitParam0.page, this.remitParam0.pageSize).then(data => {
                    this.handleCallbackDate(0, data);
                });
            },
            //获取激活失败的用户
            getactivelist(){
               activeHistory(this.activeList.page,this.activeList.pageSize).then(data=>{
                this.activeHistoryList=data.data;

               });
            },
            /**
             * [获取验证码列表]
             */
            remitCode(){
                this.authCode = true;
                fetchRemitCode(this.remitParam.page, this.remitParam.pageSize).then(data => {
                    this.handleCallbackDate(1, data);
                });
            },
            /**
             * [获取认证记录列表]
             */
            remitHistory() {
                this.authHistory = true;
                fetchRemitHistory(this.historyParam.page, this.historyParam.pageSize).then(data => {
                    this.handleCallbackDate(2, data);
                });
            },
            /**
             * [查看提款银行信息]
             */
            searchBankInfo(e) {
                this.dialog5 = true;
                fetch(`/enterprise/bank-info?companyId=${e}`).then(data => {
                    this.dialogData5 = data;
                });
            },
            /**
             * [查看企业信息]
             */
            searchEnterpriseInfo(e) {
                this.dialog1 = true;
                this.swiperSlides2 = [{img:'http://ofn881vu0.bkt.clouddn.com/imgload.gif'},{img:'http://ofn881vu0.bkt.clouddn.com/imgload.gif'}];
                fetch(`/enterprise/enterprise-info?enterprise_id=${e}`).then(data => {
                    this.dialogData1 = data;
                    this.swiperSlides2 = [{img:data.business_license_path},{img:data.organization_code_certificate_path}];
                });
            },
            /**
             * [查看工商信息]
             */
            searchBussinessInfo(e) {
                this.dialog2 = true;
                fetch(`/enterprise/business-info?enterprise_id=${e}`).then(data => {
                    this.dialogData2 = data;
                })
            },
            /**
             * [查看ECDS信息]
             */
            searchECDS(e) {
                this.dialog4 = true;
                fetch(`/enterprise/ecds-info?enterprise_id=${e}`).then(data => {
                    this.dialogData4 = data;
                })
            },
            /**
             * [查看法人信息]
             */
            searchLegalPerson(e) {
                this.dialog3 = true;
                this.swiperSlides = [{img:'http://ofn881vu0.bkt.clouddn.com/imgload.gif'},{img:'http://ofn881vu0.bkt.clouddn.com/imgload.gif'}];
                fetch(`/enterprise/legal-person-info?enterprise_id=${e}`).then(data => {
                    this.dialogData3 = data;
                    this.swiperSlides = [{img:data.corporate_ID_card_path},{img:data.power_of_attorney_path}];
                    this.dialog3 = true;
                });
            },
            /**
             * [验证码-打款]
             */
            remitConfirm(e) {
                this.operatePost(`/property/auth-remit-confirm`, e);
            },
            /**
             * [验证码-打款失败]
             */
            remitFailed(e) {
                this.operatePost(`/property/auth-remit-failed`, e);
            },
            /**
             * [认证管理-审查通过]
             */
            auditConfirm(e) {
                this.operatePost(`/check/enterprise-confirm`, e);
            },
            /**
             * [认证管理-审查不通过]
             */
            auditFailed(e) {
                this.operatePost(`/check/enterprise-failed`, e);
            },
            /**
             * [提交上面四个操作]
             */
            operatePost(url, e){
                this.dialogLoading = true;
                postUrl(url, {enterprise_id : e}).then(data => {
                    this.operate(data);
                });
            },
            /**
             * [处理操作返回内容]
             */
            operate(data) {
                if(data.code == 200){
                    this.dialogLoading = false;
                    this.$notify({
                        title:'提示',
                        message:'操作成功',
                        type:'success'
                    });
                    // 0.5s以后再刷新列表，视觉效果比较好一点
                    setTimeout(()=>{
                        this.remitList();
                        this.remitCode();
                        this.remitHistory();
                    },500);
                }else {
                    this.dialogLoading = false;
                    this.$notify({
                        title:'提示',
                        message:data.message,
                        type:'warning'
                    });
                }
            },
            /**
             * [三张列表信息返回处理]
             */
            handleCallbackDate(type, data){
                switch(type){
                    case 0:
                        this.billShow0 = data.count == 0 ? true : false;
                        this.authMana = false;
                        this.remitParam0.total = data.count
                        this.tableData1 = data.data;
                        break;
                    case 1:
                        this.billShow1 = data.count == 0 ? true : false;
                        this.authCode = false;
                        this.remitParam.total = data.count;
                        this.remitData = data.data;
                    break;
                    case 2:
                        this.billShow2 = data.count == 0 ? true : false;
                        this.historyParam.total = data.count;
                        this.historyData = data.data;
                        this.authHistory = false;
                    break;
                }

            },
            /**
             * [认证管理分页处理]
             */
            handleCurrentChange0(currentPage) {
                this.remitParam0.page = currentPage;
                this.remitList();
            },
            /**
             * [验证码分页处理]
             */
            handleCurrentChange1(currentPage) {
                this.remitParam.page = currentPage;
                this.remitCode();
            },
            /**
             * [认证记录分页处理]
             */
            handleCurrentChange2(currentPage) {
                this.historyParam.page = currentPage;
                this.remitHistory();
            },
        }
};
</script>
<style>
.tradelist {
    .rowb{
        padding: 20px 0;
    border-bottom: 1px solid #ececec;
    }
    .buttonsss{
        width: 70px;
        height: 36px;
        border-radius: 4px;
        border:0px;
        cursor: pointer;
        background: #3c8dbc;
        color: #fff;
    }
    width: 100%;
    height: auto;
    overflow: hidden;
    .el-dialog__body {
        text-align: center;
        .el-form-item__label{
            width: 90px;
        }
        .el-checkbox__label {
            color: #e01c1c !important;
        }
        .el-checkbox__inner.is-checked {
            border-color: #1fbae6 !important;
            background-color: #1fbae6 !important;
        }
    }
    .el-dialog--tiny {
        width: 500px;
    }
    .el-dialog__header {
        text-align: center;
    }
    .el-dialog__title {
        font-weight: normal;
    }
    .el-dialog__footer {
        text-align: center;
        button {
            width: 48%;
        }
        .sure-btn {
            background-color: #1fbae6;
            border-color: #1fbae6;
            color: #fff;
        }
        .cancel-btn {
            background-color: transparent;
            color: #333;
            border-color: #C0CCDA;
        }
        .cancel-btn2 {
            background-color: #f25537;
            border-color: #f25537;
            color: #fff;
        }
    }
    .block {
        padding-top: 40px;
        padding-bottom: 20px;
    }
    .el-pagination {
        text-align: right;
    }
    .el-date-editor__trigger.el-icon {
        color: #1fbae6;
    }
    .block {
        padding-top: 55px;
        padding-bottom: 20px;
    }
    .el-pagination {
        text-align: right;
    }
    .el-pager li.active {
        border-color: #22b8e5;
        background: #22b8e5;
    }
    .recode {
        padding: 30px 20px;
        background: #fff;
    }
    .mylist {
        padding: 30px 20px;
        margin-top: 30px;
        background: #fff;
    }   
}
</style>