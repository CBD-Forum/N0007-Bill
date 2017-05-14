<template>
  <div id="enterprise"  v-loading="renzhen">
    <!-- <div class="title" v-if="buzhou!=4">企业认证</div> -->
    <div class="buzhoutop" v-if="buzhou!=8">
      <div class="proess">
        <div class="comle cricle cricle1 cactive" @click='stepback(0)'></div>
        <div class="comle line line1" :class="{'cactive':buzhou>=1}"></div>
        <div class="comle cricle cricle2" :class="{'cactive':buzhou>=1}" @click='stepback(1)'></div>
        <div class="comle line line2" :class="{'cactive':buzhou>=2}"></div>
        <div class="comle cricle cricle3" :class="{'cactive':buzhou>=2}" @click='stepback(2)'></div>
        <div class="comle line line3" :class="{'cactive':(buzhou>=7 && buzhou!=9)}"></div>
        <div class="comle cricle cricle4" :class="{'cactive':(buzhou>=7 && buzhou!=9)}"></div>
        <div class="comle line line5" :class="{'cactive':(buzhou>=8 && buzhou!=9)}"></div>
        <div class="comle cricle cricle5" :class="{'cactive':(buzhou>=8 && buzhou!=9)}"></div>
      </div>
      <div class="info">
        <p class="p1 active">银行信息</p>
        <p class="p2" :class="{'active':buzhou>=1}">企业信息</p>
        <p class="p4" :class="{'active':buzhou>=2}">账户认证</p>
        <p class="p5" :class="{'active':(buzhou>=7 && buzhou!=9)}">等待确认</p>
        <p class="p6" :class="{'active':(buzhou>=8 && buzhou!=9)}">认证完成</p>
      </div>
    </div>
    <!-- 银行信息 -->
    <div class="tstemp stempone" v-if="buzhou==0">
      <el-form ref="bankfrom" :model="bankfrom" label-width="80px" :rules="bankrelus">
        <el-row>
          <el-col :span="11" :offset="1">
            <div class="onetitle">出入金账户</div>
              <el-form-item label="账户名">
                <el-input v-model="bankfrom.userName" placeholder="请输入账户名" ></el-input>
              </el-form-item>
              <el-form-item label="银行账号">
                <el-input v-model="bankfrom.account" placeholder="请输入银行账号" @keyup.native.prevent="onenum(1,bankfrom.account)"></el-input>
              </el-form-item>
              <el-form-item label="所属地区">
                <el-row>
                  <el-col :span="8">
                    <el-select v-model="bankfrom.province" placeholder="省" @change="getbcity()" >
                      <el-option
                        v-for="item in bprovince"
                        :label="item.label"
                        :value="item.value"
                        
                        >
                      </el-option>
                    </el-select>
                  </el-col>
                  <el-col :span="8">
                    <el-select v-model="bankfrom.city" :disabled="cityselect" placeholder="市"   @change="getbcountry()" >
                      <el-option
                        v-for="item in bcity"
                        :label="item.label"
                        :value="item.value">
                      </el-option>
                    </el-select>
                  </el-col>
                  <el-col :span="8">
                    <el-select v-model="bankfrom.country" :disabled="countryselect" placeholder="区">
                      <el-option
                        v-for="item in bcountry"
                        :label="item.label"
                        :value="item.value">
                      </el-option>
                    </el-select>
                  </el-col>
                </el-row>
              </el-form-item>
              <el-form-item label="所属银行">
                <el-select v-model="bankfrom.bankCode" placeholder="请选择所属银行" @change="getbankbranch() ">
                  <el-option
                    v-for="item in bank"
                    :label="item.label"
                    :value="item.value"
                    >
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="开户支行">
                 <el-select v-model="bankfrom.sBankCode" :disabled="sBankCode" placeholder="请选择银行支行" @change="setbankbranch()">
                  <el-option
                    v-for="item in bankbranch"
                    :label="item.label"
                    :value="item.value">
                  </el-option>
                </el-select>
              </el-form-item>
          </el-col>
          <el-col :span="11" :offset="1">
            <div class="onetitle">ECDS账户</div>
            <el-checkbox v-model="checked" @change="identical()">同提款银行</el-checkbox>
            <el-form-item label="账户名" prop="name">
              <el-input v-model="bankfrom.name" placeholder="请输入账户名"></el-input>
            </el-form-item>
            <el-form-item label="银行账号"  prop="bank_card_number">
              <el-input v-model="bankfrom.bank_card_number" placeholder="请输入银行账号" @keyup.native.prevent="onenum(2,bankfrom.bank_card_number)"></el-input>
            </el-form-item>
            <el-form-item label="银行行号" prop="bank_code">
              <el-input v-model="bankfrom.bank_code" placeholder="请输入银行行号" @keyup.native.prevent="onenum(3,bankfrom.bank_code)"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="10" :offset="7">
            <el-button type="primary" @click="gonext(0)">确定提交</el-button>
          </el-col>
        </el-row>
      </el-form>
    </div>
    <!-- 企业信息 -->
    <div class="tstemp stemptwo" v-if="buzhou==1">
      <el-form ref="companyfrom" :model="companyfrom" label-width="135px" :rules="companyrules">
        <el-row>
          <el-col :span="11">
            <div class="onetitle">客户基本信息</div>
              <el-form-item label="企业名称" prop="name">
                <el-input v-model="companyfrom.name" placeholder="请输入企业名称"></el-input>
              </el-form-item>
              <el-form-item label="企业性质" prop="enterprise_type">
                <!-- <el-input v-model="companyfrom.enterprise_type" placeholder="请输入企业性质"></el-input> -->
                <el-select v-model="companyfrom.enterprise_type" placeholder="请选择企业性质">
                  <el-option
                    v-for="item in enteryall"
                    :label="item.label"
                    :value="item.value">
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="经营地址" prop="address">
                <el-input v-model="companyfrom.address" placeholder="请输入经营地址"></el-input>
              </el-form-item>
              <el-form-item label="行业分类" prop="industry">
                <el-input v-model="companyfrom.industry" placeholder="请输入行业分类"></el-input>
              </el-form-item>
              <el-form-item label="区域" prop="area">
                <el-input v-model="companyfrom.area" placeholder="请输入区域"></el-input>
              </el-form-item>
              <el-form-item label="营业执照号" prop="licence">
                <el-input v-model="companyfrom.licence" placeholder="如已开通三证合一，请填写企业统一信用代码"></el-input>
              </el-form-item>
              <el-form-item label="组织机构代码" prop="code_org">
                <el-input v-model="companyfrom.code_org" placeholder="如已开通三证合一，请填写企业统一信用代码"></el-input>
              </el-form-item>
              <el-form-item label="企业统一信用代码" prop="socialCreditCode">
                <el-input v-model="companyfrom.socialCreditCode" placeholder="如已未开通三证合一，请填写组织机构代码"></el-input>
              </el-form-item>
              <el-form-item label="上传">
                  <el-col :span="24" class="upbutton">
                     <el-upload v-if="liceshow==1"
                      :action="uploadFile.uploadUrl"
                      :data="{'type':'10007'}"
                      :on-preview="handlePreview"
                      :on-remove="handleRemove"
                      name="image_file"
                      :headers="headers"
                      :default-file-list="fileList"
                      :on-success="upload1"
                      :show-upload-list="false"
                      >
                      <el-button class="upload" type="primary">营业执照正本或副本</el-button>
                    </el-upload>
                    <div class="imginfo" v-if="liceshow==0">
                      <!-- <div class="imgleft">
                        <img :src="imgokshow.business_license_path" alt="">
                      </div> -->
                      <div class="imgright">
                        <!-- <p>{{imgokshow.business_license_name}}</p>
                        <p class="clook" @click="showimg(imgokshow.business_license_path)">查看</p>
                        <p class="clook" @click="deleteimg('1007')">删除</p> -->
                        <p>
                          <span class="name">营业执照</span>
                          <span class="clook" @click="showimg('营业执照',imgokshow.business_license_url)">查看</span>
                          <span class="clook" @click="deleteimg('1007')">删除</span>
                        </p>
                      </div>
                    </div>
                  </el-col>
                  
                  <el-col :span="24" class="upbutton">
                    <el-upload v-if="mechshow==1"
                      :action="uploadFile.uploadUrl"
                      :data="{type:'10008'}"
                      :on-preview="handlePreview"
                      :on-remove="handleRemove"
                      name="image_file"
                      :headers="headers"
                      :default-file-list="fileList"
                      :on-success="upload2"
                      :show-upload-list="false">
                      <el-button class="upload" type="primary">组织机构代码证</el-button>
                    </el-upload>
                    <div class="imginfo" v-if="mechshow==0">
                      <!-- <div class="imgleft">
                        <img :src="imgokshow.organization_code_certificate_path" alt="">
                      </div> -->
                      <div class="imgright">
                        <!-- <p>{{imgokshow.organization_code_certificate_name}}</p>
                        <p class="clook" @click="showimg(imgokshow.organization_code_certificate_path)">查看</p>
                        <p class="clook" @click="deleteimg('1005')">删除</p> -->
                        <p>
                          <span class="name">组织机构代码证</span>
                          <span class="clook" @click="showimg('组织机构代码证',imgokshow.organization_code_certificate_url)">查看</span>
                          <span class="clook" @click="deleteimg('1005')">删除</span>
                        </p>
                      </div>
                    </div>
                  </el-col>
                <!-- </el-row> -->
              </el-form-item>
          </el-col>
          <el-col :span="11" :offset="2">
            <div class="onetitle">工商注册信息</div>
            <el-form-item label="工商注册日期">
                 <el-date-picker
                    v-model="companyfrom.reg_date"
                    type="date"
                    placeholder="选择工商注册日期"
                    >
                </el-date-picker>
              <!-- <el-input v-model="companyfrom.reg_date" placeholder="请输入工商注册日期"></el-input> -->
            </el-form-item>
            <el-form-item label="工商注册资金" prop="reg_capital">
              <el-input v-model="companyfrom.reg_capital" placeholder="请输入工商注册资金:10,000万元(例子)" @keyup.native.prevent="trige"></el-input><span style="position: absolute;line-height: 36px;top:0;right: 10px;">万元</span>
            </el-form-item>
            <el-form-item label="注册资金币种" prop="currency">
              <el-input v-model="companyfrom.currency"></el-input>
            </el-form-item>
            <el-form-item label="上传">
              <el-col :span="24">
                <el-upload v-if="Permitshow==1"
                  :action="uploadFile.uploadUrl"
                  :data="{type:'10009'}"
                  :on-preview="handlePreview"
                  :on-remove="handleRemove"
                  name="image_file"
                  :headers="headers"
                  :default-file-list="fileList"
                  :on-success="upload3"
                  :show-upload-list="false">
                  <el-button class="upload" type="primary">开户许可证</el-button>
                </el-upload>
                <div class="imginfo" v-if="Permitshow==0">
                  <!-- <div class="imgleft">
                    <img :src="imgokshow.licence_for_opening_accounts_path" alt="">
                  </div> -->
                  <div class="imgright">
                    <!-- <p>{{imgokshow.licence_for_opening_accounts_name}}</p> -->
                    <!-- <p><span></span></p>
                    <p class="clook" @click="showimg(imgokshow.licence_for_opening_accounts_path)">查看</p>
                    <p class="clook" @click="deleteimg('1011')">删除</p> -->
                    <p>
                      <span class="name">开户许可证</span>
                      <span class="clook" @click="showimg('开户许可证',imgokshow.licence_for_opening_accounts_url)">查看</span>
                      <span class="clook" @click="deleteimg('1011')">删除</span>
                    </p>
                  </div>
                </div>
              </el-col>
            </el-form-item>
            <div class="onetitle">联系人信息</div>
            <el-form-item label="法人姓名" prop="legalPersonNm">
              <el-input v-model="companyfrom.legalPersonNm" placeholder="请输入法人姓名"></el-input>
            </el-form-item>
            <!-- <el-form-item label="证件类型">
              <el-input v-model="formAlignRight.region" placeholder="请输入证件类型"></el-input>
            </el-form-item> -->
            <el-form-item label="联系人">
              <el-input v-model="companyfrom.contacts" placeholder="请输入联系人姓名"></el-input>
            </el-form-item>
            <el-form-item label="联系人电话" prop="contact_number">
              <el-input class="phonecon" v-model="companyfrom.contact_number" placeholder="请输入联系人号码"></el-input>
              <el-button v-loading="yzloading" class="phonecode" type="primary" @click="Authentication" v-if="xinbutton==true">获取验证码</el-button>
              <el-button class="phonecode" type="primary" :disabled="true"  v-if="xinbutton==false">{{nexttime}}s重新发送</el-button>
            </el-form-item>
            <el-form-item label="验证码" prop="">
              <el-input v-model="companyfrom.captcha" placeholder="请输入验证码"></el-input>
            </el-form-item>
            <el-form-item label="联系人邮箱" prop="mailAddress">
              <el-input v-model="companyfrom.mailAddress" placeholder="请输入联系人邮箱地址"></el-input>
            </el-form-item>
            <el-form-item label="上传">
              <!-- <el-row> -->
                <el-col :span="24" class="upbutton">
                   <el-upload v-if="personshow==1"
                    :action="uploadFile.uploadUrl"
                    :data="{type:'10010'}"
                    :on-preview="handlePreview"
                    :on-remove="handleRemove"
                    name="image_file"
                    :headers="headers"
                    :default-file-list="fileList"
                    :on-success="upload4"
                    :show-upload-list="false">
                    <el-button class="upload" type="primary">法人身份证正反面复印件</el-button>
                  </el-upload>
                  <div class="imginfo" v-if="personshow==0">
                    <!-- <div class="imgleft">
                      <img :src="imgokshow.corporate_ID_card_path" alt="">
                    </div> -->
                    <div class="imgright">
                      <!-- <p>{{imgokshow.corporate_ID_card_name}}</p>
                      <p class="clook" @click="showimg(imgokshow.corporate_ID_card_path)">查看</p>
                      <p class="clook" @click="deleteimg('1001')">删除</p> -->
                      <p>
                        <span class="name">法人身份证</span>
                        <span class="clook" @click="showimg('法人身份证',imgokshow.corporate_ID_card_url)">查看</span>
                        <span class="clook" @click="deleteimg('1001')">删除</span>
                      </p>
                    </div>
                  </div>
                </el-col>
                <el-col :span="24" class="upbutton">
                  <el-upload v-if="grantshow==1"
                   :action="uploadFile.uploadUrl"
                   :data="{type:'10011'}"
                    :on-preview="handlePreview"
                    :on-remove="handleRemove"
                    name="image_file"
                    :headers="headers"
                    :default-file-list="fileList"
                    :on-success="upload5"
                    :show-upload-list="false">
                    <el-button class="upload" type="primary">授权书</el-button>
                  </el-upload>
                  <div class="imginfo" v-if="grantshow==0">
                    <!-- <div class="imgleft">
                      <img :src="imgokshow.power_of_attorney_path" alt="">
                    </div> -->
                    <div class="imgright">
                      <!-- <p>{{imgokshow.power_of_attorney_name}}</p>
                      <a class="clook" :href="imgokshow.power_of_attorney_path">查看</a>
                      <p class="clook" @click="deleteimg('1013')">删除</p> -->
                      <p>
                        <span class="name">授权书</span>
                        <span class="clook" @click="showimg('授权书',imgokshow.power_of_attorney_url)">查看</span>
                        <span class="clook" @click="deleteimg('1013')">删除</span>
                      </p>
                    </div>
                  </div>
                </el-col>
              <!-- </el-row> -->
            </el-form-item>
            <el-form-item >
              <p class="download"><a href="http://ojadcva8s.bkt.clouddn.com/%E4%BC%81%E4%B8%9A%E6%8E%88%E6%9D%83%E4%B9%A6.doc">授权书模板下载</a></p>
            </el-form-item>
          </el-col>
          <el-col :span="10" :offset="7">
            <el-button type="primary" @click="gonext(1)">确定提交</el-button>
          </el-col>
        </el-row>
      </el-form>
    </div>
    <div class="step stepone steptwo" v-if="(buzhou==2)||(buzhou==9)">
      <!-- <span class="icon iconfont icon-weibiaoti2"></span> -->
      <p class="tishi">平台将向企业银行卡中打一笔金额随机的验证款，以认证企业信息</p>
      <div class="persoininfo">
        <div class="ctitle">账户认证</div>
        <p><span class="leftcom">用户名</span><span class="rightcom">{{enusername.userName}}</span></p>
        <p><span class="leftcom">银行账号</span><span class="rightcom">{{enusername.account}}</span></p>
        <p><span class="leftcom">所属银行</span><span class="rightcom">{{enusername.bankName}}</span></p>
      </div>
      <div class="anniu" @click="gonext(2)">认证申请</div>
    </div>
    <!-- 认证打款 -->
    <div class="steptwo" v-if="(buzhou==3) || (buzhou==5)">
      <span class="icon iconfont icon-icon21"></span>
      <p class="infoing">认证打款中...</p>
      <div class="persoininfo">
        <div class="ctitle">账户认证</div>
        <p><span class="leftcom">用户名</span><span class="rightcom">{{enusername.userName}}</span></p>
        <p><span class="leftcom">银行账号</span><span class="rightcom">{{enusername.account}}</span></p>
        <p><span class="leftcom">所属银行</span><span class="rightcom">{{enusername.bankName}}</span></p>
      </div>
    </div>

    <div class="steptwo" v-if="(buzhou==4)|| (buzhou==6)">
      <!-- <span class="icon iconfont icon-icon21"></span> -->
      <!-- <p class="infoing">认证打款中...</p> -->
      <span class="el-icon-circle-cross"></span>
      <p class="infoing infoing2" v-if="buzhou==4">企业信息未通过审核,请重新填写信息</p>
      <p class="infoing infoing2" v-if="buzhou==6">认证打款失败,请重新申请</p>
      <el-button class="newadd" v-if="buzhou==4"  type="primary" @click="stepback(0)">重新填写信息</el-button>
      <!-- <el-button class="newadd" v-if="buzhou==6"  type="primary" @click="goback()">重新申请</el-button> -->
      <el-button class="newadd" v-if="buzhou==6"  type="primary" @click="stepback(0)">重新申请</el-button>
      <div class="persoininfo">
        <div class="ctitle">账户认证</div>
        <p><span class="leftcom">用户名</span><span class="rightcom">{{enusername.userName}}</span></p>
        <p><span class="leftcom">银行账号</span><span class="rightcom">{{enusername.account}}</span></p>
        <p><span class="leftcom">所属银行</span><span class="rightcom">{{enusername.bankName}}</span></p>
      </div>
    </div>
    

    <!-- 等待确认 -->
    <div class="stepmthree" v-if="buzhou==7">
      <span class="icon iconfont icon-dengdai"></span>
      <p class="infoing">验证款已汇,请确认</p>
      <el-input class="inputc"
         placeholder="请输入金额"
         v-model="tillmoney.amount">
      </el-input>
      <el-button class="moneysubmit" type="primary" :loading="anbtn" @click="gonext(7)">确认验证款</el-button>
      
      <p v-if="tshow==0" style="font-size:14px;line-height:40px;text-align:center;">您有2次确认验证款的机会，请仔细核对后输入</p>
      <p class="errorinfo">{{info}}</p>
    </div>
    <el-dialog :title="imgtitle" v-model="dialogVisible" size="tiny">
       <img class="billimg2" :src="showbig" alt="">
      <el-button type="primary" class="imgbutton" @click="dialogVisible = false">确 定</el-button>
    </el-dialog>
    <div class="" v-if="buzhou==8">
       <successinfo></successinfo>
    </div>
  </div>
</template>
<script>
import api from '../api'
import {
  temptime,
  isIdCard,
  isPhone,
  isBankCard,
  isemail,
  isbuscode,
  fmoney
} from '../../src/assets/js/common-js'
import successinfo from './successinfo.vue'
  export default {
    props:['authstatus'],
    data() {
      var isphone=(rule,value,callback)=>{
        if(!isPhone(value)){
          callback(new Error('预留手机号格式错误'));
        }
      };
      var isBuscode=(rule,value,callback)=>{
        if(!isbuscode(value)){
          callback(new Error('营业执照格式错误'));
        }
      };
      var isBUscode=(rule,value,callback)=>{
        if(!isbuscode(value)){
          callback(new Error('企业统一信用代码格式错误'));
        }
      };
      var isBUScode=(rule,value,callback)=>{
        if(!isbuscode(value)){
          callback(new Error('组织机构格式错误'));
        }
      };
      var isEmail=(rule,value,callback)=>{
        if(!isemail(value)){
          callback(new Error('邮箱格式错误'));
        }
      };
      var isidcard=(rule,value,callback)=>{
        if(!isIdCard(value)){
          callback(new Error('身份证号码格式错误'));
        }
      };
      var isbankcard=(rule,value,callback)=>{
        if(!isBankCard(value)){
          callback(new Error('银行卡号格式错误'));
        }
      };
    return {
      renzhen:true,
      anbtn:false,
      bankfrom:{
        userName:sessionStorage.getItem('enterprise_name'),
        account:'',
        bankName:'',
        sBankCode:'',
        bankCode:'',
        province:'',
        city:'',
        country:'',
        name:'',
        bank_card_number :'',
        bank_code:'',
      },
      bankrelus:{
        userName:[
          { required: true, message: '请输入出入金账户名', trigger: 'blur' },
        ],
        account:[
          { required: true, message: '请输入提款银行卡号', trigger: 'blur' },
          // {validator:isbankcard,trigger: 'blur'},
        ],
        province:[
          { required: true, message: '请选择省', trigger: 'blur' },
        ],
        city:[
          { required: true, message: '请选择市', trigger: 'blur' },
        ],
        country:[
          { required: true, message: '请选择区', trigger: 'blur' },
        ],
        // sBankCode:[
        //  { required: true, message: '请选择提款银行', trigger: 'blur' },
        // ],
        // bankCode:[
        //  { required: true, message: '请选择提款银行支行', trigger: 'blur' },
        // ],
        name:[
          { required: true, message: '请输入ECDS账户名', trigger: 'blur' },
        ],
        bank_card_number:[
          { required: true, message: '请输入ECDS账户银行卡号', trigger: 'blur' },
          // {validator:isbankcard,trigger: 'blur'},
        ],
        bank_code:[
          { required: true, message: '请输入ECDS账户银行行号', trigger: 'blur' },
        ],
      },
      bank:[],//银行
      bankbranch:[],//支行
      bprovince:[],//省
      bcity:[],//市
      bcountry:[],//区
      delimg:[],
      info:'',
      dialogVisible:false,
      cityselect:true,
      countryselect:true,
      sBankCode:true,
      liceList:[{name:'',url:''}],//营业执照
      mechList:[{name:'',url:''}],//信用代码证
      PermitList:[{name:'',url:''}],//许可证
      personList:[{name:'',url:''}],//身份证
      grantList:[{name:'',url:''}],//授权书
      imgokshow:{
        business_license_id:'',
        business_license_name:'',
        business_license_url:'',
        corporate_ID_card_id:'',
        corporate_ID_card_name:'',
        corporate_ID_card_url:'',
        licence_for_opening_accounts_id:'',
        licence_for_opening_accounts_name:'',
        licence_for_opening_accounts_url:'',
        organization_code_certificate_id:'',
        organization_code_certificate_name:'',
        organization_code_certificate_url:'',
        power_of_attorney_name:'',
      },
      showbig:'',
      xinbutton:true,
      nexttime:'60',
      companyfrom: {
        name:sessionStorage.getItem('enterprise_name'),
        enterprise_type:'',
        address:'',
        industry:'',
        area:'',
        licence:'',
        socialCreditCode:'',
        reg_date:'',
        reg_capital:'',
        captcha:'',
        currency:'人民币',
        legalPersonNm:'',
        mailAddress:'',
        business_license_path:'',
        organization_code_certificate_oath:'',
        licence_for_opening_accounts_path:'',
        corporate_ID_card_path:'',
        power_of_attorney_path:'',
        code_org:'',
        contact_number:'',
        contacts:'',
      },
      yzloading:false,
      companyrules:{
        code_org:[
          { required: true, message: '请输入组织代码', trigger: 'blur' },
        ],
        contact_number:[
          { required: true, message: '请输入联系人电话', trigger: 'blur' },
        ],
        contacts:[
          { required: true, message: '请输入联系人姓名', trigger: 'blur' },
        ],
      //  name:[
        //  { required: true, message: '请输入企业名称', trigger: 'blur' },
        // ],
        enterprise_type:[
          { required: true, message: '请选择企业性质', trigger: 'change' },
        ],
        address:[
          { required: true, message: '请输入经营地址', trigger: 'blur' },
        ],
        industry:[
          { required: true, message: '请输入行业分类', trigger: 'blur' },
        ],
        area:[
          { required: true, message: '请输入区域', trigger: 'blur' },
        ],
        licence:[
          { required: true, message: '如已开通三证合一，请填写企业统一信用代码', trigger: 'blur' },
          // {validator:isBuscode,trigger: 'blur'},
        ],
        socialCreditCode:[
          { required: true, message: '如未开通三证合一，请填组织机构代码', trigger: 'blur' },
          // {validator:isBUscode,trigger: 'blur'},
        ],
        code_org:[
          { required: true, message: '如已开通三证合一，请填写企业统一信用代码', trigger: 'blur' },
          // {validator:isBUScode,trigger: 'blur'},
        ],
        reg_date:[
          { required: true, message: '请输入注册日期', trigger: 'blur' },
        ],
        reg_capital:[
          { required: true, message: '请输入工商注册资金:10,000万元', trigger: 'blur' },
        ],
        currency:[
          { required: true, message: '请输入资金币种', trigger: 'blur' },
        ],
        legalPersonNm:[
          { required: true, message: '请输入法人姓名', trigger: 'blur' },
        ],
        contact_number:[
          { required: true, message: '请输入授权人', trigger: 'blur' },
          // {validator:isphone,trigger: 'blur'},
        ],
        mailAddress:[
          { required: true, message: '请输入邮箱地址', trigger: 'blur' },
          // {validator:isemail,trigger: 'blur'},
        ],

      },
      enteryall:[{
        value: '国有企业',
        label: '国有企业'
      },{
        value: '集体企业',
        label: '集体企业'
      },{
        value: '联营企业',
        label: '联营企业'
      },{
        value: '股份(上市)企业',
        label: '股份(上市)企业'
      },{
        value: '私营企业',
        label: '私营企业'
      },{
        value: '独资(全资)企业',
        label: '独资(全资)企业'
      },{
        value: '合伙(或合作)企业',
        label: '合伙企业'
      },{
        value: '有限责任公司',
        label: '有限责任公司'
      },{
        value: '股份(非上市)公司',
        label: '股份(非上市)公司'
      },{
        value: '股权分散企业',
        label: '股权分散企业'
      },{
        value: '三资企业',
        label: '三资企业'
      },{
        value: '外资企业',
        label: '外资企业'
      },],
      tillmoney:{
        amount:'',
      },
      sbbank:'',
      // citytyped:{
      //   cityType:"1",
      //   cityNode:"",
      // },
      citytyped:{
        node:'',
      },
      bankbranch2:{
        bank:'',
        city:'',
      },
      tshow:0,
      enusername:{
        userName:'',
        account:'',
        bankName:'',
      },
      postdata:{
        mobile:'',
      },
      // loading: true,
      checked:false,
      liceshow:1,//显示营业执照上传按钮
      mechshow:1,//显示信用代码证上传按钮
      Permitshow:1,//显示许可证上传按钮
      personshow:1,//显示身份证上传按钮
      grantshow:1,//显示授权书上传按钮
      licenextshow:0,
      mechnextshow:0,
      Permitnetxshow:0,
      personnetxshow:0,
      grantnextshow:0,
      iserror:0,
      imgtitle:'',
      region: {},
      fileList: [],
      enterinfo:'',
      // buzhou:0,
      // buzhou:0,
      // authstatus:0,
      buzhou:this.authstatus,
      uploadFile: {
        uploadUrl: api.config.api+'upload/image',
        filetype: "",
        uploadword:'',
      },
      };
    },
    computed: {
      headers: function() {
        return {
          Authorization: "Bearer " + sessionStorage.token,
          Accept: "application/json; charset=utf-8"
        }
      }
    },
    // updated: function() {
    //   console.log(this.authstatus);
    //  this.buzhou=this.authstatus;
    // },
    mounted: function() {
      this.$nextTick(() => {
        if(sessionStorage.getItem('token') == null){
          this.$alert(
            '请先登录',
            '提示',
          ).then(() => {
            // window.location.href="/login.html"
            this.$router.push({
              path: '/'
            });
          });
          return true;
        }
        setTimeout(()=>{
          this.renzhen=false;
          this.buzhou=sessionStorage.getItem('enterprise_status');
           if(this.buzhou>0){
              this.getBankinfo();
            }
        }, 500);
        this.setheight();
        this.getbank();
        this.getprovice();
        
      });
    },
     components: {
      successinfo
    },
    methods:{
      handlePreview(){

      },
      handleRemove(){

      },
      setheight:function(){
        var tradepass=document.getElementById('enterprise');
        var wheight=window.screen.availHeight-160+'px';
        tradepass.style.minHeight =wheight;
      },
      trige:function(){
        this.companyfrom.reg_capital=fmoney(this.companyfrom.reg_capital);
      },
       Authentication:function(){
        if(this.companyfrom.contact_number!=''){
          this.postdata.mobile=this.companyfrom.contact_number;
          this.getCaptchbyMobile(this.postdata);
        }else{
          this.$notify({
              title: '提示',
              message: '请输入授权人电话号码',
              type: 'warning'
            });
        }
      },
       getCaptchbyMobile: function(postdata) {
        this.yzloading = true;
        ajax(this, this.extendApi.sendsms, postdata, (data) => {
          this.xinbutton=false;
          this.yzloading = false;
          var ltime=setInterval(()=>{
            this.nexttime=this.nexttime-1;
            if(this.nexttime==0){
              clearInterval(ltime);
              this.nexttime=60;
              this.xinbutton=true;
            }
          }, 1000);
          if(data.code==200){
            this.$notify({
              title: '成功',
              message: data.message,
              type: 'success'
            });
          }else{
            this.xinbutton = true;
            this.$notify({
              title: '失败',
              message: data.message,
              type: 'warning'
            });
          }
        })
      },

      onenum:function(nutype,value){
        if(nutype==1){
          this.bankfrom.account=value.replace(/\D/g,'');
        }else if(nutype==2){
          this.bankfrom.bank_card_number=value.replace(/\D/g,'');
        }else if(nutype==3){
          this.bankfrom.bank_code=value.replace(/\D/g,'');
        }
      },

      //获取银行信息
      async getbank(){
        const data=await api.get(this,api.config.getbank);
        if(data.code==200){
          this.bank = data.data.map((e) => ({
            value: String(e.bankCode),
            label: e.bankName,
            sbankcode:String(e.SBankCode),
          }));
        }
      },
      
      // //获取银行信息
      // getbank:function(){
      //  ajax(this, this.extendApi.getbank, '', (data) => {
      //    if(data.code==200){
      //      this.bank = data.list.map((e) => ({
      //        value: String(e.bankCode),
      //        label: e.bankName,
      //        sbankcode:String(e.SBankCode),
      //      }));
      //    }
      //  });
      // },

      // //获取省
      async getprovice(){
        const data=await api.get(this, api.config.getprovice, '');
        console.log(data);
        if(data.code==200){
           this.bprovince = data.data.map((e) => ({
             value: String(e.cityNode),
             label: e.cityName
           }));
           console.log(this.bprovince);
         }
       // ajax(this, this.extendApi.getprovice, '', (data) => {
       //   if(data.code==200){
       //     this.bprovince = data.list.map((e) => ({
       //       value: String(e.cityNode),
       //       label: e.cityName
       //     }));
       //   }
       // });
      },

      // //获取市
      async getbcity(){
        // this.bankfrom.city = '';
        this.bcity=[];
        this.citytyped.node=this.bankfrom.province;
        const data=await api.get(this,api.config.getcity,this.citytyped);
        if(data.code==200){
          this.cityselect=false;
          this.bcity = data.data.map((e) => ({
            value: String(e.cityCode),
            label: e.cityName
          }));
        }
      },
      // getbcity:function(){
      //  this.bankfrom.city = '';
      //  this.bcity=[];
      //  this.citytyped.cityType="2";
      //  this.citytyped.cityNode=this.bankfrom.province;
       // ajax(this, this.extendApi.getcity, this.citytyped, (data) => {
       //   if(data.code==200){
       //     this.cityselect=false;
       //     this.bcity = data.list.map((e) => ({
       //       value: String(e.cityCode),
       //       label: e.cityName
       //     }));
       //   }
       // });
      // },
      // //获取区
      async getbcountry(){
        this.bankfrom.country = '';
        this.bcountry=[];
        this.citytyped.node=this.bankfrom.city;
        const data=await api.get(this,api.config.getDistrict,this.citytyped);
        if(data.code==200){
           this.countryselect=false;
           this.bcountry = data.data.map((e) => ({
             value: String(e.cityCode),
             label: e.cityName
           }));
         }
      //  console.log('123');
      //  this.bankfrom.country = '';
      //  this.bcountry=[];
      //  this.citytyped.cityType="3";
      //  this.citytyped.cityNode=this.bankfrom.city;
      //  ajax(this, this.extendApi.getcity, this.citytyped, (data) => {
       //   if(data.code==200){
       //     this.countryselect=false;
       //     this.bcountry = data.list.map((e) => ({
       //       value: String(e.cityCode),
       //       label: e.cityName
       //     }));
       //   }
       // });
      },
      async getbankbranch(){
        this.bankbranch=[];
        for(var i=0;i<this.bank.length;i++){
          if(this.bank[i].value==this.bankfrom.bankCode){
            this.sbbank=this.bank[i].sbankcode;
            this.bankfrom.bankName=this.bank[i].label;
          }
        }
        this.bankbranch2.bank=this.bankfrom.bankCode;
        this.bankbranch2.city=this.bankfrom.country;
        const data=await api.get(this,api.config.getbankbanch,this.bankbranch2);
        console.log(data);
        if(data.code==200){
          this.sBankCode=false;
         // console.log(123);
          this.bankbranch = data.data.map((e) => ({
            value: String(e.bankno),
            label: e.bankname
          }));
        }
      },
      // getbankbranch:function(){
      //  this.bankbranch=[];
      //  for(var i=0;i<this.bank.length;i++){
      //    if(this.bank[i].value==this.bankfrom.bankCode){
      //      this.sbbank=this.bank[i].sbankcode;
      //      this.bankfrom.bankName=this.bank[i].label;
      //    }
      //  }
      //  this.bankbranch2.bankCode=this.bankfrom.bankCode;
      //  this.bankbranch2.cityCode=this.bankfrom.country;
      //  ajax(this, this.extendApi.getbankbanch, this.bankbranch2, (data) => {
      //    console.log(data);
      //    if(data.code==200){
      //      this.sBankCode=false;
      //      // console.log(123);
      //      this.bankbranch = data.list.map((e) => ({
      //        value: String(e.bankno),
      //        label: e.bankname
      //      }));
      //    }
      //  });
      // },
      setbankbranch:function(event){
        for(var i=0;i<this.bankbranch.length;i++){
          if(this.bankbranch[i].value==this.bankfrom.sBankCode){
            this.bankfrom.bankName=this.bankbranch[i].label;
          }
        }
      },
      //勾选同提款银行
      identical:function(){
        console.log(this.checked);
        if(this.checked){
          this.bankfrom.name=this.bankfrom.userName;
          this.bankfrom.bank_card_number=this.bankfrom.account;
          this.bankfrom.bank_code=this.bankfrom.sBankCode;
        }else{
          this.bankfrom.name='';
          this.bankfrom.bank_card_number='';
          this.bankfrom.bank_code='';
        }
        
      },

      upload1:function(response,file,filelist){
        if(response.code == 200){
          this.imgokshow.business_license_url=response.url;
          this.imgokshow.business_license_name=response.original_name;
          this.companyfrom.business_license_path=response.path;
          this.liceshow=0;
        }else{
          this.liceList=[];
           this.$notify({
            title: '失败',
            message: response.message,
            type: 'warning'
          });
        }
      },

      upload2:function(response,file,filelist){
        if(response.code == 200){
          this.imgokshow.organization_code_certificate_url=response.url;
          this.imgokshow.organization_code_certificate_name=response.original_name;
          this.companyfrom.organization_code_certificate_path=response.path;
          this.mechshow=0;
        }else{
          this.mechList=[];
           this.$notify({
            title: '失败',
            message: response.message,
            type: 'warning'
          });
        }
        
      },
      upload3:function(response,file,filelist){
        if(response.code == 200){
          this.imgokshow.licence_for_opening_accounts_url=response.url;
          this.imgokshow.licence_for_opening_accounts_name=response.original_name;
          this.companyfrom.licence_for_opening_accounts_path=response.path;
          this.Permitshow=0;
        }else{
          this.PermitList=[];
           this.$notify({
            title: '失败',
            message: response.message,
            type: 'warning'
          });
        }
      },
      upload4:function(response,file,filelist){
        if(response.code == 200){
          this.imgokshow.corporate_ID_card_url=response.url;
          this.imgokshow.corporate_ID_card_name=response.original_name;
          this.companyfrom.corporate_ID_card_path=response.path;
          this.personshow=0;
        }else{
          this.personList=[];
           this.$notify({
            title: '失败',
            message: response.message,
            type: 'warning'
          });
        }
      },
      upload5:function(response,file,filelist){
        if(response.code == 200){
          this.imgokshow.power_of_attorney_name=response.original_name;
          this.imgokshow.power_of_attorney_url=response.url;
          this.companyfrom.power_of_attorney_path=response.path;
          this.grantshow=0;
        }else{
          this.grantList=[];
           this.$notify({
            title: '失败',
            message: response.message,
            type: 'warning'
          });
        }
      },
      //删除
      deleteimg:function(imgtype){
        if(imgtype=='1007'){
            this.liceList=[];
            this.imgokshow.business_license_id='';
            this.liceshow=1;
          }else if(imgtype=='1005'){
            this.mechList=[];
            this.imgokshow.organization_code_certificate_id='';
            this.mechshow=1;
          }else if(imgtype=='1011'){
            this.PermitList=[];
            this.imgokshow.licence_for_opening_accounts_id='';
            this.Permitshow=1;
          }else if(imgtype=='1001'){
            this.personList=[];
            this.imgokshow.corporate_ID_card_id='';
            this.personshow=1;
          }else if(imgtype=='1013'){
            this.grantList=[];
            this.imgokshow.power_of_attorney_id='';
            this.grantshow=1;
          }
      },
      //获取打款信息
       getBankinfo:function(){
        api.get(this, api.config.getBankInfo, {'companyId':sessionStorage.enterprise_id}, (data) => {
          if(data.code==200){
            this.enusername=data;
          }else{
            this.$notify({
              title: '失败',
              message: data.message,
              type: 'warning'
            });
          }
        });
       },
      showimg:function(imtitle,imgurl){
        this.dialogVisible=true;
        this.showbig=imgurl;
        this.imgtitle=imtitle;
      },
       /* 
       * 选择地区 
       */
      // changePro: function(event) {
      //     this.bankfrom.province = event.province[0];
      //     this.bankfrom.city = event.city[0];
      //     this.bankfrom.country = event.district[0];
      // },
      // 
      stepback:function(stepnum){
        console.log(stepnum);
        if((this.authstatus>=3 && this.authstatus!=4) || this.iserror==1 ){
          return;
        }
        api.post(this, api.config.userInfo, '', (data) => {
          if(data.code==200){
            if(stepnum==0){
              this.buzhou=0;
              this.authstatus=this.buzhou;
              api.get(this,api.config.getBankInfo,{'companyId':sessionStorage.enterprise_id},(data)=>{
                this.bankfrom.account=data.account;
                this.bankfrom.bankName=data.bankName
                this.bankfrom.userName=data.userName
                this.bankfrom.sBankCode=data.sBankCode
              })
              api.get(this,api.config.getEcdsInfo,{'enterprise_id':sessionStorage.enterprise_id},(data)=>{
                this.bankfrom.bank_card_number=data.bank_card_number;
                this.bankfrom.bank_code=data.bank_code;
                this.bankfrom.name=data.name;
              })
              // this.bankfrom=data;
              this.bankfrom.bankCode = '301';
              // if(data.city!=''){
              //  ajax(this, this.extendApi.getcityinfo,{'cityCode':data.city,cityType:'2'}, (res) => {
              //    this.bcity=[];
              //    this.bcity = res.list.map((e) => ({
              //      value: String(e.cityCode),
              //      label: e.node_nodename
              //    }));
              //    console.log(this.bcity);
              //  });
              // }
              // if(data.sBankCode!=''){
              //  ajax(this, this.extendApi.getbankinfo, {bankType:'2',bankCode:data.sBankCode}, (res) => {
              //    console.log(res);
              //    this.bankbranch=[];
              //    this.bankbranch = res.list.map((e) => ({
              //      value: String(e.bankno),
              //      label: e.bankname
              //    }));
              //  });
              // }
            }else if(stepnum==1){
              this.buzhou=1;
              this.authstatus=this.buzhou;
              sessionStorage.setItem('enterprise_status',this.buzhou);
              api.post(this,api.config.getAllEnterInfo,'',(data)=>{
                var cdat = JSON.stringify(data);
                this.companyfrom=JSON.parse(cdat);
                this.imgokshow=JSON.parse(cdat);
                if(this.imgokshow.business_license_path!=null){
                  this.liceshow=0;
                } 
                if(this.imgokshow.organization_code_certificate_path!=null){
                  this.mechshow=0;
                }
                if(this.imgokshow.licence_for_opening_accounts_path!=null){
                  this.Permitshow=0;
                }
                if(this.imgokshow.corporate_ID_card_path!=null){
                  this.personshow=0;
                }
                if(this.imgokshow.power_of_attorney_path!=null){
                  this.grantshow=0;
                }
              })
            }else if(stepnum==2){
              this.buzhou=2;
              this.authstatus=this.buzhou;
              sessionStorage.setItem('enterprise_status',this.buzhou);
            }
            
            // this.loading1=false;
          }
        });
      },
      goback:function(){
        this.buzhou=2;
        this.authstatus=this.buzhou;
        this.iserror=1;
        // this.stepback(0);
        // this.authstatus=this.buzhou;
        sessionStorage.setItem('enterprise_status',this.buzhou);
      },
      gonext:function(step){
        if(step==0){
          this.$refs.bankfrom.validate((valid) => {
            if(valid){
              api.post(this, api.config.bankSubmit, this.bankfrom, (data) => {
                console.log(data);
                if(data.code==200){
                  this.buzhou=step+1;
                  console.log();
                  this.authstatus=this.buzhou;
                  // this.stepback(1);
                   this.$notify({
                    title: '成功',
                    message: '成功',
                    type: 'success'
                  });
                  //  api.post(this,api.config.getAllEnterInfo,'',(data)=>{
                  //    this.companyfrom=data;
                  //    if(this.imgokshow.business_license_path!=null){
                  //     this.liceshow=0;
                  //   } 
                  //   if(this.imgokshow.organization_code_certificate_path!=null){
                  //     this.mechshow=0;
                  //   }
                  //   if(this.imgokshow.licence_for_opening_accounts_path!=null){
                  //     this.Permitshow=0;
                  //   }
                  //   if(this.imgokshow.corporate_ID_card_path!=null){
                  //     this.personshow=0;
                  //   }
                  //   if(this.imgokshow.power_of_attorney_path!=null){
                  //     this.grantshow=0;
                  //   }
                  // })
                }else{
                  this.$notify({
                    title: '失败',
                    message: data.message,
                    type: 'warning'
                  });
                }
              });
            }else{
              this.$notify({
                title: '提交失败',
                message: '请认真填写所有信息',
                type: 'warning'
              });
            }
          })
        }
        if(step==1){
          var _this=this;
          this.$refs.companyfrom.validate((valid) => {
            if(valid && this.companyfrom.business_license_path !='' && this.companyfrom.organization_code_certificate_path !='' && this.companyfrom.licence_for_opening_accounts_path !='' && this.companyfrom.corporate_ID_card_path !='' && this.companyfrom.power_of_attorney_path !=''){
              this.companyfrom.reg_date=temptime(this.companyfrom.reg_date);
              this.companyfrom.reg_capital=this.companyfrom.reg_capital.replace(/,/g,'');
              api.post(this, api.config.enterpriseSubmit, this.companyfrom, (data) => {
                if(data.code==200){
                  this.buzhou=step+1;
                  this.tshow=0;
                  this.getBankinfo();
                  this.authstatus=this.buzhou;
                  sessionStorage.setItem('enterprise_status',this.buzhou);
                  sessionStorage.setItem('stepnum',this.buzhou);
                   this.$notify({
                    title: '成功',
                    message: '成功',
                    type: 'success'
                  });
                }else{
                  this.$notify({
                    title: '失败',
                    message: data.message,
                    type: 'warning'
                  });
                }
              });
            }else{
              this.$notify({
                title: '提交失败',
                message: '请输入所有信息及上传所有证明',
                type: 'warning'
              });
            }
          })
          
        }
        if(step==2){
          api.post(this, api.config.enterpriseApply, '', (data) => {
            if(data.code==200){
              this.buzhou=step+1;
              this.authstatus=this.buzhou;
              this.iserror=0;
              this.$notify({
                title: '成功',
                message: '申请成功',
                type: 'success'
              });
            }else{
              this.$notify({
                title: '失败',
                message: data.message,
                type: 'warning'
              });
            }
          })
        }
        if(step==7){
          this.anbtn = true;
          api.post(this, api.config.authEnterprise, this.tillmoney, (data) => {
            this.tshow=1;
            this.anbtn = false;
            if(data.code==200){
              this.$notify({
                title: '提示',
                message: '验证成功',
                type: 'success'
              });
              this.buzhou=step+1;
              this.authstatus=this.buzhou;
              sessionStorage.setItem('enterprise_status',this.buzhou);
              this.enterinfo=data.data;
              // this.$notify({
             //        title: '成功',
             //        message: '认证通过',
             //        type: 'success'
             //    });
            }else if(data.code==29006){
                this.info=data.message;
            }else if(data.code==29007){
              this.info=data.message;
              setTimeout(()=>{
                this.buzhou=9;
                this.authstatus=this.buzhou;
                sessionStorage.setItem('enterprise_status',this.buzhou);
              },5000);
            }else{
              this.$notify({
                title: '失败',
                message: data.message,
                type: 'warning'
              });
            }
          })
        }
        
      },
    },
  }
</script>
<style>
  #enterprise{
    width: 100%;
    height: auto;
    overflow: hidden;
    position: relative;
    background: #fff;
    padding: 20px 30px;
    .title{
      color: #6cb8ec;
      font-size: 20px;
    }
    .phonecon{
      width: 60%;
      float: left;
    }
    .phonecode{
      width: 35%!important;
      float: right;
    }
    .el-icon-loading {
      display: none;
      animation: rotating 1s linear infinite;
    }
    .el-icon-document{
      font-size: 40px;
      color: #6cb8ec;
    }
    .el-date-editor{
      width: 100%;
    }
    .el-form-item.is-required .el-form-item__label:before{
      display: none;
    }
    .el-button--primary{
      width: 100%;
    }
    .el-dialog__header{
      text-align:center;
    }
    .billimg2{
      width: 100%;
      height: auto;
    }
    .el-dialog--tiny{
      width: auto;
    }
    .imginfo{
      height: 36px;
      width:100%;
      /*margin-top: 10px;*/
      clear: both;
      /*margin-left: 36%;*/
      /*margin-bottom: 20px;*/
      .lebl{
        line-height: 35px;
        float: left;
        width: 35%;
        text-align: right;
        font-size: 14px;
        color: #aaa;
      }
      .imgleft{
        width: 62px;
        height: 70px;
        /*margin-left: 20px;*/
        float: left;
        img{
          width: 100%;
          height: 100%;
        }
      }
      .imgright{
        float: left;
        padding-left: 10px;
        P{
          /*width: 500px;*/
          overflow-x: hidden;
          height: 35px;
          font-size: 16px;
          line-height: 35px;
        }
        .name{
          float: left;
          padding-right: 10px;
          font-size: 14px;
        }
        .clook{
          width: 40px;
          float: left;
          color: #2fa5e6;
          cursor: pointer;
        }
      }
    }
    .el-form-item{
      margin-bottom: 20px;
    }
    .regpick {
      width: 100%;
      height: 36px;
      line-height: 36px;
    }
    .regpick select {
        line-height: 36px;
        width: 32.6% !important;
        height: 36px;
        border-color: #2fa5e6;
        color: #aaa;
        border-radius: 4px;
    }
    .buzhoutop{
      width: 1040px;
      height: 70px;
      margin: 40px auto 0;
      .proess{
        width: 970px;
        height: 20px;
        margin: 0 auto;
        .comle{
          float: left; 
        }
        .line{
          width: 220px;
          height: 3px;
          position: relative;
          top:6px;
          background: #a0dbf8;
        }
        .line2{
          left: -2px;
        }
        .line3{
          left: -4px;
        }
        .line5{
          left: -6px;
        }
        .line6{
          left: -8px;
        }
        .cricle{
          position: relative;
          width: 15px;
          height: 15px;
          border-radius: 50%;
          background: #a0dbf8;
        }
        .cricle1{
          right: -1px;
          cursor: pointer;
        }
        .cricle2{
          right: 1px;
          cursor: pointer;
        }
        .cricle3{
          right: 3px;
          cursor: pointer;
        }
        .cricle4{
          right: 5px;
        }
        .cricle5{
          right: 8px;
        }
        .cricle6{
          right: 10px;
        }
        .cactive{
          background: #2fa5e6;
        }
      }
      .info{
        width: 100%;
        height: 50px;
        color: #a0dbf8;
        p{
          padding-top: 20px;
          width: 100px;
          float: left;
          font-size: 18px;
        }
        .p1{
          /*margin-left: 30px;*/
        }
        .p2,.p3,.p4,.p5,.p5,.p6{
          margin-left: 132px;
        }
      }
      .active{
        color: #2fa5e6;
      }
    }
    .el-input__inner{
      border-color:  #2fa5e6;
    }
    .tstemp{
      margin-top: 100px;
      margin-bottom: 50px;
      .onetitle{
        width: 100%;
        font-size: 20px;
        color: #2fa5e6;
        text-align: center;
        margin-bottom: 50px;
      }
      .el-button--primary{
        width: 100%;
        background: #6cb8ec;
        border-color:#6cb8ec;
        margin-top: 30px; 
      }
    }
    .stempone{
      position: relative;
      .el-checkbox {
        position: absolute;
        /* color: #1f2d3d; */
        right: 0;
        cursor: pointer;
        white-space: nowrap;
        top: 30px;
        font-size: 14px;
        color: #666!important;
      }
      .el-checkbox__inner.is-checked {
        background-color: #20a0ff!important;
        border-color: #2e90fe!important;
      }
    }
    .stemptwo{
      /*display: none;*/
      margin-top: 50px;
      margin-bottom: 0px;
      .onetitle{
        margin-bottom: 20px;
      }
      .upbutton{
        margin-bottom: 15px;
      }
      .el-button--primary{
        margin-top: 0px;
      }
      .el-upload__inner{
        width: 100%;
      }
      .upload{
        width: 100%;
        /*display: none;*/
      }
      .download{
        margin-top: -20px;
        a{
          display: inline-block;
          width: 100%;
          font-size: 16px;
          color: #2fa5e6;
          text-align: right;
          padding-right: 20px;
        }
        
      }
    }
    .iconfont{
      display: inline-block;
      width: 440px;
      text-align: center;
      font-size: 90px;
      color: #2fa5e6;
    }
    .infoing{
      text-align: center;
      height: 50px;
      line-height: 50px;
      font-size: 20px;
      color: #2fa5e6;
    }
    .infoing2{
      color: #f13b3a;
    }
    .newadd{
      width: 272px;
      margin-left: 156px;
      margin-top: 20px;
    }
    .stepone{
      width: 100%;
      height: 300px;
      .icon-weibiaoti2{
        text-align: center;
        width: 100%;
        line-height: 150px;
        /*margin:0 auto;*/
      }
      .tishi{
        text-align: center;
        line-height: 36px;
        color: #666;
        font-size: 16px;
      }
      .anniu{
        width: 200px;
        height: 40px;
        line-height: 40px;
        background: #2fa5e6;
        color: #fff;
        font-size: 18px;
        text-align: center;
        border-radius: 6px;
        margin: 30px auto 0;
        cursor: pointer;
      }
    }
    .steptwo{
      /*display: none;*/
      width: 582px;
      height: 460px;
      margin: 50px auto 80px;
      .icon-icon21{
        width: 582px;
      }
      .el-icon-circle-cross{
        width: 100%;
        font-size: 60px;
        color: #f13b3a;
        text-align: center;
        display: inherit;
      }
      .persoininfo{
        width: 100%;
        height: 228px;
        border:1px solid #2fa5e6;
        border-radius: 6px;
        margin-top: 60px;
        .ctitle{
          font-size: 20px;
          color: #2fa5e6;
          width: 100%;
          height: 76px;
          line-height: 76px;
          text-align: center;
        }
        p{
          height: 40px;
          font-size: 16px;
          line-height: 40px;
          span{
            display: inline-block;
          }
          .leftcom{
            width: 180px;
            line-height: 40px;
            text-align: right;
            color: #aaa;
          }
          .rightcom{
            width: 392px;
            line-height: 40px;
            padding-left: 40px;
          }
        }
      }
    }
    .stepmthree{
      /*display: none;*/
      width: 360px;
      height: 365px;
      margin: 100px auto 80px;
      .icon-dengdai{
        width: 360px;
      }
      .moneysubmit{
        .el-icon-loading{
          display: inline-block;
        }
      }
      .el-input__inner{
        margin-top: 90px;
        border-color: #2fa5e6;
        text-align: center;
      }
      .el-button--primary{
        background: #2fa5e6;
        border-color: #2fa5e6;
        margin-top: 28px;
        width:100%;
      }
      .errorinfo{
        font-size: 18px;
        color: red;
        width: 100%;
        text-align: center;
      }
    }
    @media (max-width: 1366px) {
      .buzhoutop{
        width: 850px;
        height: 70px;
        margin: 40px auto 0;
        .proess{
          width: 780px;
          height: 20px;
          margin: 0 auto;
          .comle{
            float: left; 
          }
          .line{
            width: 175px;
            height: 3px;
            position: relative;
            top:6px;
            background: #a0dbf8;
          }
          .cactive{
            background: #2fa5e6;
          }
        }
        .info{
          .p2,.p3,.p4,.p5,.p5,.p6{
            margin-left: 85px;
          }
        }
      }
      .imgright{
        P{
          width: 245px;
        }
        
      }
    }
    @media (min-width: 1366px) and(max-width: 1480px) {
      .buzhoutop{
        width: 1045px;
        height: 70px;
        margin: 40px auto 0;
        .proess{
          width: 975px;
          height: 20px;
          margin: 0 auto;
          .comle{
            float: left; 
          }
          
          .line{
            width: 218px;
            height: 3px;
            position: relative;
            top:6px;
            background: #a0dbf8;
          }
          .cactive{
            background: #2fa5e6;
          }
        }
        .info{
          .p2,.p3,.p4,.p5,.p5,.p6{
            margin-left: 122px;
          }
        }
      }
      .imgright{
        P{
          width: 249px;
        }
        
      }
    }
    @media (min-width: 1480px) and (max-width: 1680px) {
      .buzhoutop{
        width: 1140px;
        height: 70px;
        margin: 40px auto 0;
        .proess{
          width: 1120px;
          height: 20px;
          margin: 0 auto;
          .comle{
            float: left; 
          }
          
          .line{
            width: 260px;
            height: 3px;
            position: relative;
            top:6px;
            background: #a0dbf8;
          }
          .cactive{
            background: #2fa5e6;
          }
        }
        .info{
          .p2,.p3,.p4,.p5,.p5,.p6{
            margin-left: 160px;
          }
        }
      }
      .imgright{
        P{
          width: 349px;
        }
        
      }
    }
    @media (min-width: 1680px) and(max-width: 1920px) {
      .buzhoutop{
        width: 1260px;
        height: 70px;
        margin: 40px auto 0;
        .proess{
          width: 1200px;
          height: 20px;
          margin: 0 auto;
          .comle{
            float: left; 
          }
          
          .line{
            width: 280px;
            height: 3px;
            position: relative;
            top:6px;
            background: #a0dbf8;
          }
          .cactive{
            background: #2fa5e6;
          }
        }
        .info{
          .p2,.p3,.p4,.p5,.p5,.p6{
            margin-left: 190px;
          }
        }
      }
      .imgright{
        P{
          width: 549px;
        }
        
      }
    }
    @media (min-width: 1920px) {
      .buzhoutop{
        width: 1560px;
        height: 70px;
        margin: 40px auto 0;
        .proess{
          width: 1500px;
          height: 20px;
          margin: 0 auto;
          .comle{
            float: left; 
          }

          .line{
            width: 355px;
            height: 3px;
            position: relative;
            top:6px;
            background: #a0dbf8;
          }
          .cactive{
            background: #2fa5e6;
          }
        }
        .info{
          .p2,.p3,.p4,.p5,.p5,.p6{
            margin-left: 265px;
          }
        }
      }
    }
  }
</style>
