<template>
  <div class="enterAuth">
    <div class="pub__main">
      <div class="pub__title" v-if="progress!=8">
        企业认证
      </div>
      <div class="bzBox"  v-if="progress!=8">
        <div class="bz_progress">
          <div class="circle bz_active" @click='step(0)'></div>
          <div class="line" :class="{'bz_active':progress>=1}"></div>
          <div class="circle" :class="{'bz_active':progress>=1}" @click='step(1)'></div>
          <div class="line" :class="{'bz_active':progress>=2}"></div>
          <div class="circle" :class="{'bz_active':progress>=2}" @click='step(2)'></div>
          <div class="line" :class="{'bz_active':progress>=3}"></div>
          <div class="circle" :class="{'bz_active':progress>=3}" @click='step(3)'></div>
          <div class="line"></div>
          <div class="circle"></div>
        </div>
      </div>
      <div class="info"  v-if="progress!=8">
        <span>银行信息</span>
        <span>企业信息</span>
        <span>账户认证</span>
        <span>等待确认</span>
        <span>认证完成</span>
      </div>
      <div class="auth" v-if="progress == 0">
        <el-form ref="bankfrom" label-width="80px">
          <el-row>
            <el-col :span="11" :offset="1">
              <div class="onetitle">出入金账户</div>
              <el-form-item label="账户名">
                <el-input v-model="bankfrom.userName" placeholder="请输入账户名"></el-input>
              </el-form-item>
              <el-form-item label="银行账号">
                <el-input v-model="bankfrom.account" placeholder="请输入银行账号"></el-input>
              </el-form-item>
              <el-form-item label="所属地区">
                <el-row>
                  <el-col :span="8">
                    <el-select v-model="bankfrom.province" placeholder="省" @change="getbcity()">
                       <!-- <el-option
                        v-for="item in bprovince"
                        :label="item.label"
                        :value="item.value"
                        
                        >
                      </el-option> -->
                    </el-select>
                  </el-col>
                 <!--  <el-col :span="8">
                    <el-select v-model="bankfrom.city" :disabled="cityselect" placeholder="市">
                      <el-option
                        v-for="item in bcity"
                        :label="item.label"
                        :value="item.value">
                      </el-option>
                    </el-select>
                  </el-col> -->
                 <!--  <el-col :span="8">
                     <el-select v-model="bankfrom.country" :disabled="countryselect" placeholder="区">
                      <el-option v-for="item in bcountry" :label="item.label" :value="item.value">
                      </el-option>
                    </el-select>
                  </el-col> -->
                </el-row>
              </el-form-item>
              <el-form-item label="所属银行">
                <el-select v-model="bankfrom.bankCode" placeholder="请选择所属银行">
                  <!--     <el-option v-for="item in bank" :label="item.label" :value="item.value">
                  </el-option> -->
                </el-select>
              </el-form-item>
              <el-form-item label="开户支行">
                <el-select v-model="bankfrom.sBankCode" placeholder="请选择银行支行">
                  <!-- <el-option
                    v-for="item in bankbranch"
                    :label="item.label"
                    :value="item.value">
                  </el-option> -->
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :span="11" :offset="1">
              <div class="onetitle">ECDS账户</div>
              <el-checkbox v-model="checked" @change="checkECDS">同提款银行</el-checkbox>
              <el-form-item label="账户名" prop="name">
                <el-input v-model="bankfrom.name" placeholder="请输入账户名"></el-input>
              </el-form-item>
              <el-form-item label="银行账号" prop="bank_card_number">
                <el-input v-model="bankfrom.bank_card_number" placeholder="请输入银行账号"></el-input>
              </el-form-item>
              <el-form-item label="银行行号" prop="bank_code">
                <el-input v-model="bankfrom.bank_code" placeholder="请输入银行行号"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="10" :offset="7">
              <el-button type="primary" @click="subBankInfo">确定提交</el-button>
            </el-col>
          </el-row>
        </el-form>
      </div>
      <div class="auth" v-if="progress == 1">
        <el-form ref="companyfrom" label-width="130px">
          <el-row>
            <el-col :span="11">
              <div class="onetitle">客户基本信息</div>
              <el-form-item label="企业名称" prop="name">
                <el-input v-model="companyfrom.name" placeholder="请输入企业名称"></el-input>
              </el-form-item>
              <el-form-item label="企业性质" prop="enterprise_type">
                <el-select v-model="companyfrom.enterprise_type" placeholder="请选择企业性质">
                  <el-option v-for="item in enteryall" :label="item.label" :value="item.value">
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
              <el-form-item label="统一信用代码" prop="socialCreditCode">
                <el-input v-model="companyfrom.socialCreditCode" placeholder="如已未开通三证合一，请填写组织机构代码"></el-input>
              </el-form-item>
              <el-form-item label="上传">
                <el-col :span="24" class="upbutton">
                  <el-upload class="upload-demo" name="image_file" :action='url+ "upload/image"' :on-success="handleSuccess" :headers="headers" :data="{type:'10007'}">
                    <el-button class="upload" type="primary" v-show='showUploadButton[0]'>营业执照正本或副本</el-button>
                  </el-upload>
                </el-col>
                <el-col :span="24" class="upbutton">
                  <el-upload class="upload-demo" name="image_file" :action='url+ "upload/image"' :on-success="handleSuccess" :headers="headers" :data="{type:'10008'}">
                    <el-button class="upload" type="primary" v-show='showUploadButton[1]'>组织机构代码证</el-button>
                  </el-upload>
                </el-col>
              </el-form-item>
            </el-col>
            <el-col :span="11" :offset="2">
              <div class="onetitle">工商注册信息</div>
              <el-form-item label="工商注册日期">
                <el-date-picker @change="dateChange" v-model="companyfrom.reg_date" type="date" placeholder="选择工商注册日期">
                </el-date-picker>
              </el-form-item>
              <el-form-item label="工商注册资金" prop="reg_capital">
                <el-input v-model="companyfrom.reg_capital" placeholder="请输入工商注册资金:10,000万元(例子)" @keyup.native.prevent="trige"></el-input><span style="position: absolute;line-height: 36px;top:0;right: 10px;">万元</span>
              </el-form-item>
              <el-form-item label="注册资金币种" prop="currency">
                <el-input v-model="companyfrom.currency"></el-input>
              </el-form-item>
              <el-form-item label="上传">
                <el-col :span="24">
                  <el-upload class="upload-demo" name="image_file" :action='url+ "upload/image"' :on-success="handleSuccess" :headers="headers" :data="{type:'10009'}">
                    <el-button class="upload" type="primary" v-show='showUploadButton[2]'>开户许可证</el-button>
                  </el-upload>
                </el-col>
              </el-form-item>
              <div class="onetitle">联系人信息</div>
              <el-form-item label="法人姓名" prop="legalPersonNm">
                <el-input v-model="companyfrom.legalPersonNm" placeholder="请输入法人姓名"></el-input>
              </el-form-item>
              <el-form-item label="联系人">
                <el-input v-model="companyfrom.contacts" placeholder="请输入联系人姓名"></el-input>
              </el-form-item>
              <el-form-item label="联系人电话" prop="contact_number">
                <el-input class="phonecon" v-model="companyfrom.contact_number" placeholder="请输入联系人号码"></el-input>
                <el-button v-loading="yzloading" class="phonecode" type="primary" @click="Authentication" v-if="xinbutton==true">获取验证码</el-button>
                <el-button class="phonecode" type="primary" :disabled="true" v-if="xinbutton==false">{{nexttime}}s重新发送</el-button>
              </el-form-item>
              <el-form-item label="验证码" prop="">
                <el-input v-model="companyfrom.captcha" placeholder="请输入验证码"></el-input>
              </el-form-item>
              <el-form-item label="联系人邮箱" prop="mailAddress">
                <el-input v-model="companyfrom.mailAddress" placeholder="请输入联系人邮箱地址"></el-input>
              </el-form-item>
              <el-form-item label="上传">
                <el-col :span="24" class="upbutton">
                  <el-upload class="upload-demo" name="image_file" :action='url+ "upload/image"' :on-success="handleSuccess" :headers="headers" :data="{type:'10010'}">
                    <el-button class="upload" type="primary" v-show='showUploadButton[3]'>法人身份证正反面复印件</el-button>
                  </el-upload>
                </el-col>
                <el-col :span="24" class="upbutton">
                  <el-upload class="upload-demo" name="image_file" :action='url+ "upload/image"' :on-success="handleSuccess" :headers="headers" :data="{type:'10011'}">
                    <el-button class="upload" type="primary" v-show='showUploadButton[4]'>授权书</el-button>
                  </el-upload>
                </el-col>
              </el-form-item>
              <el-form-item>
                <p class="download"><a href="http://ojadcva8s.bkt.clouddn.com/%E4%BC%81%E4%B8%9A%E6%8E%88%E6%9D%83%E4%B9%A6.doc">授权书模板下载</a></p>
              </el-form-item>
            </el-col>
            <el-col :span="10" :offset="7">
              <el-button type="primary" @click="subEnterInfo()">确定提交</el-button>
            </el-col>
          </el-row>
        </el-form>
      </div>
      <div class="step2" v-if="progress == 2">
        <p class="tishi">平台将向企业银行卡中打一笔金额随机的验证款，以认证企业信息</p>
        <div class="persoininfo">
          <div class="ctitle">账户认证</div>
          <p><span class="leftcom">用户名</span><span class="rightcom">{{enusername.userName}}</span></p>
          <p><span class="leftcom">银行账号</span><span class="rightcom">{{enusername.account}}</span></p>
          <p><span class="leftcom">所属银行</span><span class="rightcom">{{enusername.bankName}}</span></p>
        </div>
        <div class="anniu" @click="authApply(2)">认证申请</div>
      </div>
      <div class="step2" v-if="progress == 5">
        <span class="icon iconfont icon-icon21"></span>
        <p class="infoing">认证打款中...</p>
        <div class="persoininfo">
          <div class="ctitle">账户认证</div>
          <p><span class="leftcom">用户名</span><span class="rightcom">{{enusername.userName}}</span></p>
          <p><span class="leftcom">银行账号</span><span class="rightcom">{{enusername.account}}</span></p>
          <p><span class="leftcom">所属银行</span><span class="rightcom">{{enusername.bankName}}</span></p>
        </div>
      </div>
      <div class="step2" v-if="(progress==4)|| (progress==6)">
        <span class="el-icon-circle-cross"></span>
        <p class="infoing infoing2" v-if="progress==4">企业信息未通过审核,请重新填写信息</p>
        <p class="infoing infoing2" v-if="progress==6">认证打款失败,请重新申请</p>
        <el-button class="newadd" v-if="progress==4" type="primary" @click="stepback()">重新填写信息</el-button>
        <el-button class="newadd" v-if="progress==6" type="primary" @click="goback()">重新申请</el-button>
        <div class="persoininfo">
          <div class="ctitle">账户认证</div>
          <p><span class="leftcom">用户名</span><span class="rightcom">{{enusername.userName}}</span></p>
          <p><span class="leftcom">银行账号</span><span class="rightcom">{{enusername.account}}</span></p>
          <p><span class="leftcom">所属银行</span><span class="rightcom">{{enusername.bankName}}</span></p>
        </div>
      </div>
      <div class="step3" v-if="progress==7">
        <span class="icon iconfont icon-dengdai"></span>
        <p class="infoing">验证款已汇,请确认</p>
        <el-input class="inputc" v-model="amount" placeholder="请输入金额">
        </el-input>
        <el-button class="moneysubmit" type="primary" @click="authFinal()">确认验证款</el-button>
      </div>
      <div class="" v-if="progress==8">
        <successinfo></successinfo>
      </div>
    </div>
</template>
<script>
import api from '../api'
import successinfo from './successinfo.vue'
export default {
  data() {
      return {
        isEntersuccess:false,
        showUploadButton:[true,true,true,true,true],
        info:'',
        amount:'', // 验证金额
        url: api.config.api,
        enteryall: [{ // 企业性质
          value: '国有企业',
          label: '国有企业'
        }, {
          value: '集体企业',
          label: '集体企业'
        }, {
          value: '联营企业',
          label: '联营企业'
        }, {
          value: '股份(上市)企业',
          label: '股份(上市)企业'
        }, {
          value: '私营企业',
          label: '私营企业'
        }, {
          value: '独资(全资)企业',
          label: '独资(全资)企业'
        }, {
          value: '合伙(或合作)企业',
          label: '合伙企业'
        }, {
          value: '有限责任公司',
          label: '有限责任公司'
        }, {
          value: '股份(非上市)公司',
          label: '股份(非上市)公司'
        }, {
          value: '股权分散企业',
          label: '股权分散企业'
        }, {
          value: '三资企业',
          label: '三资企业'
        }, {
          value: '外资企业',
          label: '外资企业'
        }, ],
        xinbutton: false,
        nexttime: 0,
        checked: false,
        progress: sessionStorage.status,
        bankfrom: '',
        companyfrom: '',
        enusername:{
          userName:'',
          account:'',
          bankName:'',
        },
        bankfrom: { // 银行信息提交
          userName: '',
          account: '',
          bankName: 'test', // 开户行名称
          sBankCode: '1',
          bankCode: '1',
          province: '1',
          city: '1',
          country: '1',
          name: '',
          bank_card_number: '',
          bank_code: '',
        },
        companyfrom: { // 企业信息提交
          name: '1',
          enterprise_type: '2',
          address: '3',
          industry: '4',
          area: '5',
          licence: '5',
          socialCreditCode: '6',
          reg_date: '', // 工商注册日期
          reg_capital: '8',
          captcha: '9',
          currency: '人民币',
          legalPersonNm: '10',
          mailAddress: '767927286@qq.com',
          business_license_path: '11',
          organization_code_certificate_path: '11',
          licence_for_opening_accounts_path: '12',
          corporate_ID_card_path: '13',
          power_of_attorney_path: '15',
          code_org: '12',
          contact_number: '137',
          contacts: '14',
        },
        bankInfo:{//获取银行信息
          companyId:'',
        },
        enterInfo: '',
      }
    },
    components: {
      successinfo
    },
    computed: {
      headers: function() {
        return {
          Authorization: "Bearer " + sessionStorage.getItem('token'),
          Accept: "application/json; charset=utf-8"
        }
      }
    },
    mounted() {
      this.$nextTick(() => {});
    },

    methods: {
      async authFinal(){
        const data = await api.post(this, api.config.authEnterprise, {amount: this.amount}); 
        if (data.code == 200) {
          this.$notify({
            title: '提示',
            message: '验证成功',
            type: 'success'
          });

          // this.enterinfo=data.data;
          this.$router.push('fcrIndex2');
        } else {
          this.$notify({
            title: '提示',
            message: data.message,
            type: 'warning'
          });
        }
      },
      goback() {
        this.progress = 2;
        sessionStorage.setItem('status', this.progress);
      },
      stepback() {
        this.progress = 0;
        sessionStorage.setItem('status', this.progress);
      },
      async authApply() {
        const data = await api.post(this, api.config.enterpriseApply);
        if (data.code == 200) {
          this.progress = 5;
        } else {
          this.$notify({
            title: '提示',
            message: data.message,
            type: 'warning'
          });
        }
      },
      handleSuccess(response, file, fileList) {
        switch (response.type) {
          case '10007':
            this.companyfrom.business_license_path = response.path;
            this.$set(this.showUploadButton,0,false);
            break;
          case '10008':
            this.companyfrom.organization_code_certificate_path = response.path;
            this.$set(this.showUploadButton,1,false);
            break;
          case '10009':
            this.companyfrom.licence_for_opening_accounts_path = response.path;
            this.$set(this.showUploadButton,2,false);
            break;
          case '10010':
            this.companyfrom.corporate_ID_card_path = response.path;
            this.$set(this.showUploadButton,3,false);
            break;
          case '10011':
            this.companyfrom.power_of_attorney_path = response.path;
            this.$set(this.showUploadButton,4,false);
            break;
        }
      },
      dateChange(events) {
        this.companyfrom.reg_date = events;
      },
      handlePreview() {

      },
      async subEnterInfo() {
        const data = await api.post(this, api.config.enterpriseSubmit, this.companyfrom);
        if (data.code == 200) {
          this.progress = 2;
          this.getBankInfo();
        } else {
          this.$notify({
            title: '提示',
            message: data.message,
            type: 'warning'
          });
        }
      },
      async subBankInfo() {
        const data = await api.post(this, api.config.bankSubmit, this.bankfrom);
        if (data.code == 200) {
          this.progress = 1;
        } else {
          this.$notify({
            title: '提示',
            message: data.message,
            type: 'warning'
          });
        }
      },
      async getBankInfo(){//获取银行信息
        // this.bankInfo.companyId=sessionStorage.getItem('enterprise_id');
        const data=await api.get(this,api.config.getBankInfo);
        if(data.code==200){
          console.log(data.userName);
          this.enusername.userName=data.userName;
          this.enusername.account=data.account;
          this.enusername.bankName=data.bankName;
        }
      },
      checkECDS() {
        console.log('fff');
        console.log(this.checked);
        if (this.checked) {
          this.bankfrom.name = this.bankfrom.userName;
          this.bankfrom.bank_card_number = this.bankfrom.account;
          this.bankfrom.bank_code = this.bankfrom.sBankCode;
        } else {
          this.bankfrom.name = '';
          this.bankfrom.bank_card_number = '';
          this.bankfrom.bank_code = '';
        }
      },
      step(index) {
        this.progress = index;
      }
    }
}
</script>
<style>
.enterAuth .pub__main {
  height: auto;
}

.bzBox {
  width: 1500px;
  height: 20px;
  margin: 0 auto;
}

.auth .el-upload__inner {
  width: 100% !important;
}

.auth .el-upload {
  width: 100%;
}

.circle {
  cursor: pointer;
  float: left;
  width: 15px;
  height: 15px;
  border-radius: 50%;
  background: #a0dbf8;
}

.line {
  float: left;
  width: 355px;
  height: 3px;
  position: relative;
  top: 6px;
  background: #a0dbf8;
}

.bz_active {
  background: #2fa5e6;
}

.info {
  width: 1560px;
  margin: 0 auto;
  padding-top: 20px;
}

.info span {
  font-size: 18px;
  color: #a0dbf8;
  margin-right: 296px;
}

.info span:nth-child(5) {
  margin-right: 0;
}

.auth {
  margin-top: 100px;
  padding-bottom: 100px;
}

.onetitle {
  width: 100%;
  font-size: 20px;
  color: #2fa5e6;
  text-align: center;
  margin-bottom: 50px;
}

.auth .el-checkbox {
  position: absolute;
  right: 0;
  cursor: pointer;
  white-space: nowrap;
  top: 30px;
  font-size: 14px;
  color: #666!important;
}

.auth .el-button--primary {
  width: 100%;
  background: #6cb8ec;
  border-color: #6cb8ec;
  margin-top: 30px;
}

.auth .el-upload__inner .el-button--primary {
  margin-top: 0;
  margin-bottom: 15px;
  width: 100%;
}

.auth .el-date-editor--date {
  width: 100% !important;
}

.step2 {
  width: 582px;
  height: 460px;
  margin: 50px auto 80px;
}

.tishi {
  text-align: center;
  line-height: 36px;
  color: #666;
  font-size: 16px;
}

.persoininfo {
  width: 100%;
  height: 228px;
  border: 1px solid #2fa5e6;
  border-radius: 6px;
  margin-top: 60px;
}

.persoininfo p {
  height: 40px;
  font-size: 16px;
  line-height: 40px;
}

.anniu {
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

.ctitle {
  font-size: 20px;
  color: #2fa5e6;
  width: 100%;
  height: 76px;
  line-height: 76px;
  text-align: center;
}

span.leftcom {
  display: inline-block;
  width: 180px;
  line-height: 40px;
  text-align: right;
  color: #aaa;
}

.infoing {
  text-align: center;
  height: 50px;
  line-height: 50px;
  font-size: 20px;
  color: #2fa5e6;
}

span.rightcom {
  width: 392px;
  line-height: 40px;
  padding-left: 40px;
}

p.download a {
  display: inline-block;
  width: 100%;
  font-size: 16px;
  color: #2fa5e6;
  text-align: right;
  padding-right: 20px;
}

.auth .phonecon {
  width: 60%;
  float: left;
}

.auth .phonecode {
  width: 35%!important;
  float: right;
  margin-top: 0 !important;
  height: 32px;
}

.step3{
      width: 360px;
    height: 365px;
    margin: 100px auto 80px;
}

.step3 .el-button--primary {
    background: #2fa5e6;
    border-color: #2fa5e6;
    margin-top: 28px;
    width: 100%;
}
/*.step3 .infoing{
  margin-bottom: 20px;
}*/
</style>
