<template>
  <div class="billRecord">
    <div class="pub__main heightAuth clearfix" v-if="tabpannel==1">
      <div class="title">
        <h2>票据录入</h2>
      </div>
      <el-row :gutter="40">
      <el-col :span="12">
        <el-form :model="addBillData" :rules="rules" ref="addBillData" label-width="129px" class="clearfix" style="width:460px;;float:right;padding-top:27px;">
          <el-col :span="24">
            <el-form-item  label="票据类型"  prop="type" placeholder="请选择票据类型">
              <el-select v-model="addBillData.type" @change="typeChange">
                <el-option
                  v-for="item in Billoption"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item  label="票据编号"  prop="bill_number">
              <el-input v-model="addBillData.bill_number" placeholder="请输入票据编号" :maxlength="30"></el-input>
            </el-form-item>
            <el-form-item label="出票人"  prop="drawer">
              <el-input v-model="addBillData.drawer" placeholder="请输入出票人"></el-input>
            </el-form-item>
            <el-form-item label="收票人"  prop="taker">
              <el-input v-model="addBillData.taker" placeholder="请输入收票人"></el-input>
            </el-form-item>
            <el-form-item label="承兑人"  prop="acceptor">
              <el-input v-model="addBillData.acceptor" placeholder="请输入承兑人"></el-input>
            </el-form-item>
            <el-form-item label="承兑人类型" placeholder="请选择承兑人类型"  prop="acceptor_type">
              <el-select v-model="addBillData.acceptor_type">
                <el-option
                  v-for="item in acceptor_type"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="出票日期" prop="issue_at">
              <el-date-picker
                v-model="addBillData.issue_at"
                type="date"
                :editable="false"
                placeholder="选择日期"
                @change = "dateChange2"
                :picker-options="pickerOptions0"
                class="recordData">
              </el-date-picker>
            </el-form-item>
            <el-form-item label="票据到期日" prop="acceptance_at">
              <el-date-picker
                v-model="addBillData.acceptance_at"
                type="date"
                :editable="false"
                placeholder="选择日期"
                @change = "dateChange"
                :picker-options="pickerOptions0"
                class="recordData">
              </el-date-picker>
            </el-form-item>
            <el-form-item label="票据金额" prop="face_amount">
              <el-input class="recordData" :controls="false" @keyup.native.prevent="fmoneyDH()"  v-model="addBillData.face_amount" placeholder="请输入贴现金额">
                <template slot="append">元</template>
              </el-input>
              
            </el-form-item>
          </el-col>
      </el-form>
      </el-col>
      <el-col :span="11" style="padding-left:100px;padding-top:32px;">
        <div class="spanbox">
          <span class="span1">上传票据（正面）</span><span class="span2">（只能上传jpeg/jpg/png文件）</span>
        </div>
        
        <el-upload :action= 'url+ "upload/image"' :thumbnail-mode="true" type="drag" name="image_file" :on-success="handleSuccess" :on-error="handleError"  :headers="headers" :data="{type:'10001'}">
            <i class="el-icon-upload"></i>
        </el-upload>

        <div class="spanbox" style="margin-top:14px;margin-bottom:10x;">
          <span class="span1">上传票据（背面）</span><span class="span2">（只能上传jpeg/jpg/png文件）</span>
        </div>
        <el-upload class="second" :action='url+"upload/image"' :thumbnail-mode="true" type="drag" drag name="image_file" :on-success="handleSuccess" :on-error="handleError"  :headers="headers" :data="{type:'10002'}">
            <i class="el-icon-upload"></i>
        </el-upload>  
      </el-col>
          
      </el-row>
      <div style="text-align:center;">
        <el-button @click="billRecord('addBillData')" type="primary" class="billRecord__submit" :loading="btnActive">确定提交</el-button>
      </div>
    </div>
    <div class="pub__main heightAuth clearfix" v-if="tabpannel==2">
      <div class="title">
        <h2>票据录入</h2>
      </div>
      <el-row :gutter="40">
      <el-col :span="12">
        <el-form :model="newBillData" :rules="rules" ref="newBillData" label-width="129px" class="clearfix" style="width:460px;;float:right;padding-top:27px;">
          <el-col :span="24">
            <el-form-item  label="票据类型"  prop="type" placeholder="请选择票据类型">
              <el-select :disabled="formshow[0]" v-model="newBillData.type" @change="typeChange">
                <el-option
                  v-for="item in Billoption"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item  label="票据编号" prop="bill_number">
              <el-input v-model="newBillData.bill_number" :disabled="formshow[1]" placeholder="请输入票据编号" :maxlength="30"></el-input>
              <!-- <el-col :span="2" v-if="iserror[1]==true">
                <span class="el-icon-circle-cross"></span>
              </el-col> -->
            </el-form-item>
            <el-form-item label="出票人" prop="drawer">
              <el-input v-model="newBillData.drawer" :disabled="formshow[2]" placeholder="请输入出票人"></el-input>
            </el-form-item>
            <el-form-item label="收票人" prop="taker">
              <el-input v-model="newBillData.taker" :disabled="formshow[3]" placeholder="请输入收票人"></el-input>
            </el-form-item>
            <el-form-item label="承兑人"  prop="acceptor">
              <el-input v-model="newBillData.acceptor" :disabled="formshow[4]" placeholder="请输入承兑人"></el-input>
            </el-form-item>
            <el-form-item label="承兑人类型" placeholder="请选择承兑人类型"  prop="acceptor_type">
              <el-select :disabled="formshow[5]" v-model="newBillData.acceptor_type">
                <el-option
                  v-for="item in acceptor_type"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="出票日期" prop="issue_at">
              <el-date-picker :disabled="formshow[6]"
                v-model="newBillData.issue_at"
                type="date"
                :editable="false"
                placeholder="选择日期"
                @change = "dateChange2"
                :picker-options="pickerOptions0"
                class="recordData">
              </el-date-picker>
            </el-form-item>
            <el-form-item label="票据到期日"  prop="acceptance_at">
              <el-date-picker :disabled="formshow[7]"
                v-model="newBillData.acceptance_at"
                type="date"
                :editable="false"
                placeholder="选择日期"
                @change = "dateChange"
                :picker-options="pickerOptions0"
                class="recordData">
              </el-date-picker>
            </el-form-item>
            <el-form-item label="票据金额" prop="face_amount">
              <el-input-number class="recordData" :disabled="formshow[8]" :controls="false"  v-model="newBillData.face_amount" :min="0" placeholder="请输入贴现金额">
                <template slot="append">元</template>
              </el-input-number>
            </el-form-item>
          </el-col>
      </el-form>
      </el-col>
      <el-col :span="11" style="padding-left:100px;padding-top:32px;">
        <div class="spanbox">
          <span v-if="passimg==true" class="span1">票据信息（正面）</span>
          <span v-if="passimg==false" class="span1">上传票据（正面）</span>
          <span class="span2">（只能上传jpeg/jpg/png文件）</span>
        </div>
        <img v-if="passimg==true" class="big" :src="newBillData.bill_front_path" alt="">
        <el-upload v-if="passimg==false" :action= 'url+ "upload/image"' :thumbnail-mode="true" type="drag" name="image_file" :on-success="handleSuccess" :on-error="handleError"  :headers="headers" :data="{type:'10001'}">
            <i class="el-icon-upload"></i>
        </el-upload>
        <div class="spanbox" style="margin-top:14px;margin-bottom:10x;">
          <span v-if="passimg==true" class="span1">票据信息（背面）</span>
          <span v-if="passimg==false" class="span1">上传票据（背面）</span>
          <span class="span2">（只能上传jpeg/jpg/png文件）</span>
        </div>
        <img v-if="passimg==true" class="big" :src="newBillData.bill_back_path" alt="">
        <el-upload v-if="passimg==false" class="second" :action='url+"upload/image"' :thumbnail-mode="true" type="drag" drag name="image_file" :on-success="handleSuccess" :on-error="handleError"  :headers="headers" :data="{type:'10002'}">
            <i class="el-icon-upload"></i>
        </el-upload>
      </el-col>   
      </el-row>
      <div v-if="passimg==false" style="text-align:center;">
        <el-button @click="billupdate" type="primary" class="billRecord__submit" :loading="btnActive">确定提交</el-button>
      </div>
       <div v-if="passimg==true" style="text-align:center;">
        <el-button @click="gocreatebill" type="primary" class="billRecord__submit" :loading="btnActive">继续录票</el-button>
      </div>
    </div>
    <div class="pub__main heightAuth clearfix tupian" v-if="tabpannel==3">
      <img src="../assets/img/luru2.png" alt="">
      <p>项目正在审核</p>
      <p class="lurubill" @click="gocreatebill">继续录入票据</p>
    </div>
    <div class="clearfix tablemain">
      <div class="title">
        <div class="clearfix">
          <h2>票据录入记录</h2>
          <div class="search-warp">
            <el-input class="search-warp__title" v-model="searchDate.query"  placeholder="编号/承兑人/金额"> </el-input>
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
      <div>
        <el-table :data="createdList.data" style="width: 100%;margin-top:10px;" stripe :default-sort = "{prop: 'date', order: 'descending'}" @row-click="selectbill" @sort-change="enterbillsort">
          <el-table-column
            prop="id"
            label="序号"
            width="80">
          </el-table-column>
          <el-table-column
            prop="bill_number"
            label="票据编号"
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
            prop="face_amount"
            label="票面金额（元）"
            sortable
            :formatter="formatter"
            min-width="160">
          </el-table-column>
          <el-table-column
            prop="apply_at"
            label="申请时间"
            sortable
            min-width="180">
          </el-table-column>
          <el-table-column
            prop="block_created_at"
            label="录入时间"
            sortable
            min-width="160">
          </el-table-column>
          <el-table-column v-if="enType!=4"
            prop="annualized_rate_suggest"
            label="审核利率（%）"
            min-width="140">
          </el-table-column>
          <el-table-column v-if="enType!=4"
            label="附件"
            width="100">
            <template scope="scope">
              <i class="iconfont icon-wenjian" @click="checkImg(scope.$index,scope.row)"></i>
            </template>
          </el-table-column>
          <el-table-column
            prop="status"
            label="票据状态"
            width="100">
          </el-table-column>
          <el-table-column
            label="区块链查询"
            width="120">
            <template scope="scope">
              <a v-if="createdList.data[scope.$index].instruction!=null" :href="blockUrladd+createdList.data[scope.$index].instruction" target="_blank">查看</a>
            </template>
        </el-table>
          <el-pagination class="table__page"
            layout="prev, pager, next"
            @current-change="changePage"
            :page-size="15"
            :total="createdList.count">
          </el-pagination>
      </div>
    </div>
    <show-img v-show="showImg" :swiperData="swiperSlides"  v-on:close= "closeShowImg"></show-img>
  </div>
</template>
<script>
import api from '../api'
import showImg from 'src/components/showImg'
import {temptime,fmoney,textfix} from '../../src/assets/js/common-js'
export default {
  props:['enter'],
  data() {
    var checkbillnumber = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('请输入票据编号'));
      }
      setTimeout(() => {
        var valen = value.length;
        value = parseInt(value);
        if (!Number.isInteger(value)) {
          callback(new Error('请输入数字值'));
        } else {
          if (valen != 30) {
            callback(new Error('请输入30位票据编号'));
          } else {
            callback();
          }
        }
      }, 1000);
    };
    var checkDate = (rule,value,callback) => {
      if (!value) {
        return callback(new Error('请选择到期日'));
      }else{
        callback();
      }
    }
    return {
      billid:'',
      url:api.config.api,
      blockUrladd:api.config.blockurl,
      btnActive: false,
      showImg:false,
      tabpannel:1,
      passimg:false,
      formshow: [false, false, false, false, false,false,false,false,false],
      iserror: [false, false, false, false, false,false,false,false,false],
      enType:sessionStorage.type,
      addBillData:{
        type:"",
        bill_number:"",
        drawer:"",
        taker:"",
        acceptor:"",
        acceptor_type:"",
        acceptance_at:"",
        face_amount:"",
        bill_front_path:"",
        bill_back_path:"",
        issue_at:''
      },
      newBillData:{
        type:"",
        bill_number:"",
        drawer:"",
        taker:"",
        acceptor:"",
        acceptor_type:"",
        acceptance_at:"",
        face_amount:"",
        bill_front_path:"",
        bill_back_path:"",
        issue_at:''
      },
      rules: {
        type: [
          { required: true, message: '请选择票据类型'},
        ],
        bill_number:[
          { required: true, validator: checkbillnumber, trigger: 'blur' }
        ],
        drawer:[
          { required: true, message: '请输入出票人'},
        ],
        taker:[
          { required: true, message: '请输入收票人'},
        ],
        acceptor:[
          { required: true, message: '请输入承兑人'},
        ],
        acceptor_type: [
          { required: true, message: '请选择承兑人类型'},
        ],
        acceptance_at:[
          { required: true, validator: checkDate, trigger: 'change' }
        ],
        issue_at:[
          { required: true, validator: checkDate, trigger: 'change' }
        ],
        face_amount:[
          { required: true, message: '请输入票面金额'},
        ],
      },
      createdList: [],
      Billoption: [{
        value: '1',
        label: '银行汇票'
      }, {
        value: '2',
        label: '商业汇票'
      }],
      acceptor_type:[],
      acceptor_typea:[{
        value: '1',
        label: '企业',
      }],
      acceptor_typeb:[{
        value: '2',
        label: '国股'
      },{
        value: '3',
        label: '城商'
      },{
        value: '4',
        label: '农商'
      },{
        value: '5',
        label: '外资'
      },{
        value: '6',
        label: '农信'
      },{
        value: '7',
        label: '财务公司'
      },{
        value: '8',
        label: '其他'
      }],
      searchDate:{
        page:1,
        pageSize:15,
        query:"",
        startTime:"",
        endTime:"",
        sort:'',
      },
      pickerOptions0: {
        disabledDate(time) {
          // return time.getTime() >Date.now() - 8.64e7;
        }
      },
      pickerOptions1: {
        disabledDate(time) {
          // return time.getTime() > Date.now() - 8.64e7;
        }
      },
      swiperSlides:[],
    }
  },
  mounted: function() {
    this.$nextTick(() => {
      api.auth(this);
      this.getBillList();
      this.billid=this.$route.query.id;
      
      setTimeout(()=>{
        console.log(this.billid);
         if(this.billid){
          this.getoneinfo();
        }
      }, 200)
     
    })
  },
  watch:{
    enter:function(val){
      this.gocreatebill();
    }
  },
  computed: {
    headers: function() {
      return {
        Authorization: "Bearer " + sessionStorage.getItem('token'),
        Accept: "application/json; charset=utf-8"
      }
    }
  },
  components:{
    showImg
  },
  methods: {
    async getoneinfo(){
      const data=await api.get(this, api.config.billInfo,{id:this.billid})
      if(data.code==200){
        this.selectbill(data);
      }
    },
    enterbillsort(column){
      let sortType=column.order=='ascending'? '' : '-' ;
      var sort='';
      if(column.order=='descending'){
        sort=sortType+column.prop;
      }else{
        sort=column.prop;
      }
      this.searchDate.sort=sort;
      this.getBillList();
    },
    fmoneyDH(){
      this.addBillData.face_amount=textfix(this.addBillData.face_amount);
      this.addBillData.face_amount=fmoney(this.addBillData.face_amount);
    },
    //继续录入票据
    gocreatebill(){
      this.tabpannel=1;
      this.addBillData.type="";
      this.addBillData.bill_number="";
      this.addBillData.drawer="";
      this.addBillData.taker="";
      this.addBillData.acceptor="";
      this.addBillData.acceptor_type="";
      this.addBillData.acceptance_at="";
      this.addBillData.face_amount="";
      this.addBillData.bill_front_path="";
      this.addBillData.bill_back_path="";
      this.addBillData.issue_at="";
    },
    //点击记录的一行的操作
    async selectbill(reponse){
      console.log(reponse);
      if(reponse.status!="待审核" && reponse.status!="审核失败" && reponse.status!=0 && reponse.status!=1){
        this.passimg=true;
      }
      if(reponse.status=="待审核"){
        this.tabpannel=3;
      }
      if(reponse.status!="待审核"){
        if(reponse.status=="审核失败" || reponse.status==1){
          this.passimg=false;
        }
        const data = await api.get(this, api.config.billInfo,{'id':reponse.id});
        if(data.code==200){
          this.tabpannel=2;
          for(let i=0;i<this.iserror.length;i++){
            this.$set(this.iserror, i, false);
          }
          for (let i = 0; i < this.formshow.length; i++) {
            this.$set(this.formshow, i, true);
          }
          if (data.type_status == 0) {
            this.$set(this.formshow, 0, false);
            // this.$set(this.iserror, 0, true);  
          }
          if (data.bill_number_status == 0) {
            this.$set(this.formshow, 1, false);
            // this.$set(this.iserror, 1, true);  
          }
          if (data.drawer_status == 0) {
            this.$set(this.formshow, 2, false);
            // this.$set(this.iserror, 2, true);  
          }
          if (data.taker_status == 0) {
            this.$set(this.formshow, 3, false);
            this.$set(this.iserror, 3, true);
            this.isupdate = true;
          }
          if (data.acceptor_status== 0) {
            this.$set(this.formshow, 4, false);
            // this.$set(this.iserror, 4, true);
            this.selectaccep=false;
            this.isupdate = true;
          }
          if (data.acceptor_type_status== 0) {
            if(data.type=='银行汇票'){
               this.acceptor_type = this.acceptor_typeb;
              }else{
                this.acceptor_type = this.acceptor_typea;
              }
            this.$set(this.formshow, 5, false);
            // this.$set(this.iserror, 5, true);
            this.selectaccep=false;
            this.isupdate = true;
          }
          if (data.issue_at_status == 0) {
            this.$set(this.formshow, 6, false);
            // this.$set(this.iserror, 6, true);
            this.isupdate = true;
          }
          if (data.acceptance_at_status == 0) {
            this.$set(this.formshow, 7, false);
            // this.$set(this.iserror, 7, true);
            this.isupdate = true;
          }
          if (data.face_amount_status == 0) {
            this.$set(this.formshow, 8, false);
            // this.$set(this.iserror, 8, true);
            this.isupdate = true;
          }
          this.newBillData=data;
        }
      }
      // const data = await api.get(this, api.config.billInfo,{'id':reponse.id});
      // console.log(data);
    },
    formatter(row, column) {
      return api.toThousands(row.face_amount);
    },
    dateChange(events){
      this.addBillData.acceptance_at = events;
    },
    dateChange2(events){
      this.addBillData.issue_at = events;
    },
    //更新票据
    async billupdate(){
      var stype='';
      var acceptype='';
      if(this.newBillData.type=='银行汇票'){
        stype=1
      }else if(this.newBillData.type=='商业汇票'){
        stype=2;
      }else{
        stype=this.newBillData.type;
      }
      for(let i=0; i<this.acceptor_typea.length;i++){
        if(this.newBillData.acceptor_type==this.acceptor_typea[i].label){
          acceptype=this.acceptor_typea[i].value;
       }
      }
      for(let i=0; i<this.acceptor_typeb.length;i++){
        if(this.newBillData.acceptor_type==this.acceptor_typeb[i].label){
          acceptype=this.acceptor_typeb[i].value;
       }
      }
      if(acceptype==''){
        acceptype=this.newBillData.acceptor_type;
      }
      console.log(acceptype);
      const data = await api.post(this, api.config.billUpdate,{
        id:this.newBillData.id,
        type:stype,
        bill_number:this.newBillData.bill_number,
        drawer:this.newBillData.drawer,
        taker:this.newBillData.taker,
        acceptor:this.newBillData.acceptor,
        acceptor_type:acceptype,
        acceptance_at:this.newBillData.acceptance_at,
        face_amount:this.newBillData.face_amount,
        bill_front_path:this.addBillData.bill_front_path,
        bill_back_path:this.addBillData.bill_back_path,
        issue_at:this.newBillData.issue_at,
      });
      if(data.code==200){
        this.getBillList();
        this.btnActive = false;
        this.tabpannel=3;
      }else{
        this.$notify({
          title:'提示',
          message:data.message,
          type:'error'
        })
      }
    },
    //录入票据
    billRecord(formName){
      this.btnActive = true;
      this.$refs[formName].validate((valid) => {
        if (!valid) {
          console.log('error submit!!');
          this.btnActive = false;
          return false;
        }else{
          if(this.addBillData.bill_front_path == ""){
            this.$notify.error({
              title: '错误',
              message: '请上传票据正面照片'
            });
            this.btnActive = false;
            return false
          }else if(this.addBillData.bill_back_path == ""){
            this.$notify.error({
              title: '错误',
              message: '请上传票据背面照片'
            });
            this.btnActive = false;
            return false
          }else{
            this.billCreate();
          }
        }
      });
    },

    //图片上传成功
    handleSuccess(response, file, fileList){
      if(response.code != 200){
        this.$notify({
          title:'提示',
          message:response.message,
          type:'warning'
        })
      }
      if(response.type == "10001"){
        this.addBillData.bill_front_path=response.path
      }
      if(response.type == "10002"){
        this.addBillData.bill_back_path=response.path
      }
    },
    handleError(){
      this.$notify.error({
        title: '错误',
        message: '图片上传失败'
      });
      return false
    },

    submitForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          alert('submit!');
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },

    //录入票据
    async billCreate(){
      this.addBillData.face_amount=this.addBillData.face_amount.replace(/,/g,"");
      const data = await api.post(this, api.config.billCreate,this.addBillData);
      if(data.code == 200){
        this.$notify({
          title: '成功',
          message: '票据创建成功',
          type: 'success'
        });
        this.getBillList();
        this.btnActive = false;
        this.tabpannel=3;
        // this.getBillList()
        // setTimeout(function(){
        //   location.reload();
        // }, 1000);
      }else{
        this.$notify({
          title: '提示',
          message: data.message,
          type: 'warning'
        });
        this.btnActive = false;
      }
    },
    //获取录入票据记录
    async getBillList(){
      this.searchDate.startTime=temptime(this.searchDate.startTime);
      this.searchDate.endTime=temptime(this.searchDate.endTime);
      const data = await api.get(this, api.config.billCreatedList,this.searchDate);
      if(data.data == undefined) return;
      for(let x of data.data){
        x.annualized_rate_suggest = (x.annualized_rate_suggest==null ||  x.annualized_rate_suggest ==  '' || x.annualized_rate_suggest ==  '0.00')? '--' : '≤' + x.annualized_rate_suggest;
        x.block_created_at == '' ? x.block_created_at = '--' : '';
        x.face_amount = Number(x.face_amount.replace(/,/g,''));
      }
      this.createdList = data;
    },

    changeStartTime(events){
      this.searchDate.startTime = events;
    },
    changeEndTime(events){
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
        this.getBillList(this.searchDate)
      }
    },
    typeChange(event){
      if(event == 1){
        this.acceptor_type = this.acceptor_typeb;
      }else{
        this.acceptor_type = this.acceptor_typea;
      }
    },
    checkImg(index, rows){
      // location.href = rows.bill_front_path;
      // location.href = rows.bill_back_path;
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
        this.searchDate.page=size;
        // var pageData ={
        //   page:size
        // };
        // Object.assign(pageData, this.searchDate);

        this.getBillList()
      }
    }
  }
}
</script>
<style>
.heightAuth{
  height: auto !important;
}
.tupian{
  width: 100%;
  position: relative;
  height: 320px!important;
  img{
    position: absolute;
    left:50%;
    width: 88px;
    height: 108px;
    margin-left: -44px;
    margin-top: 60px;
  }
  p{
    font-size: 18px;
    width: 100%;
    text-align: center;
    margin-top: 180px;
  }
  .lurubill{
    width: 240px;
    height: 36px;
    color: #fff;
    line-height: 36px;
    background: #00aaef;
    margin: 20px auto;
    border-radius: 4px;
  }
}
.span1{
  font-size: 14px;
  color: #333;
  margin-right: 20px;
}

.imglook{
  height: 143px;
}
.span2{
  font-size: 12px;
  color: #808080; 
}
.big{
  height: 140px;
  width: 300px;
}
.spanbox{
  margin: 0px 0 10px 0;
}
.el-dragger{
  width: 300px !important;
  height: 140px !important; 
  background: url(http://ojadcva8s.bkt.clouddn.com/bill1.jpg);
  background-size: cover;
}
.second .el-dragger{
  background: url(http://ojadcva8s.bkt.clouddn.com/bill2.jpg);
  background-size: cover;
}
.recordData{
  width:100%!important;
}
.billRecord__submit{
  width:430px;
  margin:27px 0px 24px 16px;
  padding:8px 15px;
}
.billRecord{
  .el-dragger__cover__interact{
    /*display: block!important;*/
    .el-draggeer__cover__btns{
      span:nth-child(1),span:nth-child(2){
        display:none;
      };
    }
  }
}


</style>
