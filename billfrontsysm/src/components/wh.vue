<template>
  <div>
    <el-dialog title="票据维护" v-model="whopenTemp" @close="closeD" class="whdialog">
      <div class="whcontent_tab">
        <a :class="{active:tabActive == 1}" @click="changeTab(1)">贴现</a>
        <a :class="{active:tabActive == 2}" @click="changeTab(2)">背书</a>
      </div>
      <div class="whcontent mt" v-show="tabActive == 1">
        <div class="whcontent__info">
          <el-form label-width="130px" class="demo-ruleForm" :model="mainSubDate" :rules="rules" ref="mainSubDate">
            <el-form-item label="贴现对象" prop="name">
              <el-button class="whButton" :class="{active:btnActive == 1}" @click="changeBtn(1)">财务公司</el-button>
              <el-button class="whButton" :class="{active:btnActive == 2}" @click="changeBtn(2)">金融机构</el-button>
            </el-form-item>
            <el-form-item label="票据类型" prop="region">
              <el-input v-model="whDate.type" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="票据编号" prop="name">
              <el-input v-model="whDate.bill_number" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="出票人" prop="name">
              <el-input v-model="whDate.drawer" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="承兑人" prop="name">
              <el-input v-model="whDate.acceptor" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="出票日" prop="name">
              <el-input v-model="whDate.issue_at" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="票据到期日" prop="name">
              <el-input v-model="whDate.acceptance_at" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="票据金额" prop="name">
              <el-input v-model="whDate.face_amount" :disabled="true">
                <template slot="append">元</template>
              </el-input>
            </el-form-item>
            <el-form-item label="贴现人" prop="discount_applicant">
              <el-input placeholder="贴现人" v-model="mainSubDate.discount_applicant"></el-input>
            </el-form-item>
            <el-form-item label="贴现日期" prop="transfer_at">
              <el-date-picker v-model="mainSubDate.transfer_at" type="date" :editable="false" placeholder="选择日期" @change="dateChange" :picker-options="pickerOptions0" class="recordData">
              </el-date-picker>
            </el-form-item>
            <el-form-item label="贴现利率" prop="annualized_rate">
              <el-input placeholder="贴现利率" v-model="mainSubDate.annualized_rate" @keyup.native.prevent="calculation()">.
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item label="贴现金额" prop="transfer_amount">
              <el-input placeholder="贴现金额" v-model="mainSubDate.transfer_amount" @keyup.native.prevent="calculation2()">
                <template slot="append">元</template>
              </el-input>
            </el-form-item>
            <el-form-item label="转让协议编号" prop="contract_number">
              <el-input placeholder="转让协议编号" v-model="mainSubDate.contract_number"></el-input>
            </el-form-item>
          </el-form>
        </div>
        <div class="whcontent__img" style="padding-top:29px;">
          <p>票面信息</p>
          <img :src="whDate.bill_front_path" />
          <p>背书信息</p>
          <img :src="whDate.bill_back_path">
          <p>转让协议</p>
          <el-upload :action='url+"maintenance/discount-contract"' class="upload-demo"  name="contract_file" :on-success="handleSuccess" :on-error="handleError" :on-remove="remove1" :default-file-list="fileList" :headers="headers">
            <!-- <i class="el-icon-upload"></i> -->
            <el-button size="small" type="primary" v-show="showupload1">上传附件</el-button>
            <!-- <div slot="tip" class="el-upload__tip">只能上传pdf/word文件</div> -->
          </el-upload>
        </div>
      </div>
      <div class="whcontent mt green" v-show="tabActive == 2">
        <div class="whcontent__info">
          <el-form label-width="130px" class="demo-ruleForm" :model="reciteSubDate" :rules="rules2" ref="reciteSubDate">
            <el-form-item label="背书对象" prop="name">
              <el-button class="whButton" :class="{active:btnActive2 == 1}" @click="changeBtn2(1)">集团成员</el-button>
              <el-button class="whButton" :class="{active:btnActive2 == 2}" @click="changeBtn2(2)">外部公司</el-button>
            </el-form-item>
            <el-form-item label="票据类型" prop="region">
              <el-input v-model="whDate.type" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="票据编号" prop="name">
              <el-input v-model="whDate.bill_number" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="出票人" prop="name">
              <el-input v-model="whDate.drawer" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="承兑人" prop="name">
              <el-input v-model="whDate.acceptor" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="出票日" prop="name">
              <el-input v-model="whDate.issue_at" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="票据到期日" prop="name">
              <el-input v-model="whDate.acceptance_at" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="票据金额" prop="name">
              <el-input v-model="whDate.face_amount" :disabled="true">
                <template slot="append">元</template>
              </el-input>
            </el-form-item>
            <el-form-item label="被背书人" prop="endorsor">
              <el-input placeholder="被背书人" v-model="reciteSubDate.endorsor"></el-input>
            </el-form-item>
            <el-form-item label="转让日期" prop="acceptance_at">
              <el-date-picker v-model="reciteSubDate.transfer_at" type="date" :editable="false" placeholder="选择日期" @change="dateChange2" :picker-options="pickerOptions0" class="recordData">
              </el-date-picker>
            </el-form-item>
            <el-form-item label="转让利率" prop="name">
              <el-input placeholder="转让利率" v-model="reciteSubDate.annualized_rate" @keyup.native.prevent="calculation(true)">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item label="转让金额" prop="name">
              <el-input placeholder="转让金额" v-model="reciteSubDate.transfer_amount" @keyup.native.prevent="calculation2(true)">
                <template slot="append">元</template>
              </el-input>
            </el-form-item>
            <el-form-item label="转让协议编号" prop="name">
              <el-input placeholder="转让协议编号" v-model="reciteSubDate.contract_number"></el-input>
            </el-form-item>
          </el-form>
        </div>
        <div class="whcontent__img" style="padding-top:29px;">
          <p>票面信息</p>
          <img :src="whDate.bill_front_path" />
          <p>背书信息</p>
          <img :src="whDate.bill_back_path">
          <p>转让协议</p>
          <el-upload :action='url+"maintenance/endorsed-contract"' name="contract_file" :on-remove="remove2" :on-success="handleSuccess2" :on-error="handleError" :default-file-list="fileList2" :headers="headers" >
            <el-button size="small" type="primary" style="background:#00bca4" v-show="showupload2">上传附件</el-button>
            <!-- <div slot="tip" class="el-upload__tip" >只能上传pdf/word文件</div> -->
          </el-upload>
        </div>
      </div>
      <div slot="footer" class="dialog-footer" v-show="tabActive == 1">
        <el-button type="primary" @click="tradeRecordMaintenance('mainSubDate')" :loading="load1">确认提交</el-button>
      </div>
      <div slot="footer" class="dialog-footer" v-show="tabActive == 2">
        <el-button type="primary" @click="tradeRecordEndorse('reciteSubDate')" :loading="load2" style="background:#00bca4;border-color:#00bca4;">确认提交</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import api from '../api'
export default {
  props: ['whopen', 'whDate', 'isSelf', 'isAgain'],
  data() {
    return {
      url:api.config.api,
      load1: false,
      load2: false,
      btnActive: 1,
      btnActive2: 1,
      tabActive: 1,
      showupload1:true,
      showupload2:true,
      fileList: [],
      fileList2: [],
      mainSubDate: {
        discount_applicant: '',
        annualized_rate: '',
        transfer_amount: '',
        transfer_at: '',
        contract_number: '',
        contract_file_path: '',
        bill_id: '',
        type: 1,
        bill_number:'',
      },
      reciteSubDate: {
        endorsor: '',
        annualized_rate: '',
        transfer_amount: '',
        transfer_at: '',
        contract_number: '',
        contract_file_path: '',
        bill_id: '',
        type: 3,
      },
      pickerOptions0: {},
      rules: {
        discount_applicant: [{
          required: true,
          message: '请输入贴现人'
        }, ],
        annualized_rate: [{
          required: true,
          message: '请输入贴现利率'
        }, ],
        transfer_amount: [{
          required: true,
          message: '请输入贴现金额'
        }, ],
        contract_number: [{
          required: true,
          message: '请输入转让协议'
        }, ],
        transfer_at: [{
          required: true,
          message: '请输入贴现日期'
        }],

      },
      rules2: {
        endorsor: [{
          required: true,
          message: '请输入被背书人'
        }, ],
      }
    }
  },
  mounted: function() {
    this.$nextTick(() => {

    })
  },
  watch: {
    whopen: function(val) {
      if (val == true && this.isAgain) {
        this.getTradeInfo();
        console.log(this.whDate);
      }
    }
  },
  computed: {
    // 由于子组件不能修改pro ps的值，因此添加一个clone
    whopenTemp: function() {
      return this.whopen;
    },
    headers: function() {
      return {
        Authorization: "Bearer " + sessionStorage.getItem('token'),
        Accept: "application/json; charset=utf-8"
      }
    }
  },
  methods: {
    async getTradeInfo() {
      const data = await api.post(this, api.config.tradeRecordInfo, {
        id: this.whDate.id
      });
      if (data.code == 200) {
        if (data.type == 1 || data.type == 2) {
          this.tabActive = 1;
          this.btnActive = data.type;
          this.mainSubDate.type = data.type;
          this.mainSubDate = data;
        } else {
          this.tabActive = 2;
          this.btnActive = data.type;
          this.reciteSubDate.type = data.type;
          this.reciteSubDate = data;
        }
      } else {
        console.log(data.message)
      }
    },
    // 计算日期差
    daysBetween(DateOne, DateTwo) {
      if (DateOne == undefined || DateTwo == undefined) return;
      let OneMonth = DateOne.substring(5, DateOne.lastIndexOf('-'));
      let OneDay = DateOne.substring(DateOne.length, DateOne.lastIndexOf('-') + 1);
      let OneYear = DateOne.substring(0, DateOne.indexOf('-'));
      let TwoMonth = DateTwo.substring(5, DateTwo.lastIndexOf('-'));
      let TwoDay = DateTwo.substring(DateTwo.length, DateTwo.lastIndexOf('-') + 1);
      let TwoYear = DateTwo.substring(0, DateTwo.indexOf('-'));
      let cha = ((Date.parse(OneMonth + '/' + OneDay + '/' + OneYear) - Date.parse(TwoMonth + '/' + TwoDay + '/' + TwoYear)) / 86400000);
      return Math.abs(cha);
    },
    // 限制贴现率输入
    textnum(textnum) {
      return textnum.replace(/[^\d.]/g, "").replace(/\.{2,}/g, ".").replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3');
    },
    calculation(isEndor) {
      // 区分贴现 背书
      let obj = isEndor ? 'reciteSubDate' : 'mainSubDate';
      if (this[obj].transfer_at == '' || this[obj].annualized_rate == '') {
        return;
      }
      let A = this.whDate.face_amount;
      let n = this.daysBetween(this.whDate.acceptance_at, this[obj].transfer_at);
      this[obj].annualized_rate = this.textnum(this[obj].annualized_rate);
      let R = this[obj].annualized_rate / 100;
      this[obj].transfer_amount = (this.whDate.face_amount -  (A * (R / 360) * n)).toFixed(2);
    },
    calculation2(isEndor) {
      // 区分贴现 背书
      let obj = isEndor ? 'reciteSubDate' : 'mainSubDate';
      if (this[obj].transfer_at == '' && this[obj].annualized_rate == '') {
        return;
      }
      this[obj].transfer_amount = this.textnum(this[obj].transfer_amount);
      let A = this.whDate.face_amount;
      let n = this.daysBetween(this.whDate.acceptance_at, this[obj].transfer_at);
      this[obj].annualized_rate = this.textnum(this[obj].annualized_rate);
      this[obj].annualized_rate = ((this.whDate.face_amount - this[obj].transfer_amount) / (A*n)*36000).toFixed(2);
    },
    dateChange(events) {
      this.mainSubDate.transfer_at = events;
      if(this.mainSubDate.transfer_at == undefined) return;
      this.calculation();
    },
    dateChange2(events) {
      this.reciteSubDate.transfer_at = events;
      if(this.mainSubDate.transfer_at == undefined) return;
      this.calculation(true);
    },
    // 自贴现或者代贴现
    async selfMain(url) {
      var key = '';
      if(this.btnActive==1){
         var data = await api.get(this, api.config.getPublicKey, {
          enterprise_name: this.mainSubDate.discount_applicant
        });
        if (data.code == 200) {
          key = data.public_key;
        } else {
          this.$notify({
            title: '提示',
            message: data.message,
            type: 'warning'
          });
          this.load1 = false;
          return;
        }
      }
      this.mainSubDate.bill_id = this.whDate.id;
      var conpath='';
      if(this.mainSubDate.contract_file_path==''){
        conpath='无';
      }else{
        conpath=this.mainSubDate.contract_file_path;
      }
      console.log(111111);
      let result = api.billNoteSigns().signDiscountNote(api.userkey(), this.whDate.possessor_address,this.whDate.bill_number,  this.mainSubDate.discount_applicant, this.mainSubDate.annualized_rate, Date.parse(new Date(this.mainSubDate.transfer_at)) / 1000, conpath,key);
      const data2 = await api.post(this, url, {...this.mainSubDate,
        ... {
          signature: result.signdata,
          instruction_id: result.sid,
        }
      });
      // const data2 = await api.post(this, url, this.mainSubDate);
      this.load1 = false;
      if (data2.code == 200) {
        this.$notify({
          title: '提示',
          message: data2.message,
          type: 'success'
        });
        this.$emit('close');
      } else {
        this.$notify({
          title: '提示',
          message: data2.message,
          type: 'warning'
        });
      }
    },
    async tradeRecordMaintenanceSub() {
      this.load1 = true;
      this.selfMain(api.config.tradeRecordMaintenance);
      return;
    },
    tradeRecordMaintenance(formName) {
      this.$refs[formName].validate((valid) => {
        if (!valid) return;
        this.tradeRecordMaintenanceSub();
      });
    },
    async selfEndorse(url) {
      var key = '';
      // 自背书 集团内 需要获取背书人公钥
      // console.log('zzzzz');
      if(this.reciteSubDate.type==4){
        key='';
      }else{
        var data = await api.get(this, api.config.getPublicKey, {
        enterprise_name: this.reciteSubDate.endorsor
        });
        if (data.code == 200) {
          key = data.public_key;
        } else {
          this.$notify({
            title: '提示',
            message: data.message,
            type: 'warning'
          });
           this.load2 = false;
          return;
        }
      }
      this.tradeRecordEndorse.bill_id=this.whDate.id;
      console.log(this.whDate.id);
      console.log(key);
      console.log(this.whDate.possessor_address);
      console.log(this.reciteSubDate);
      let result = api.billNoteSigns().signEndorseNote(api.userkey(), this.whDate.possessor_address, key, this.whDate.bill_number);
      const data2 = await api.post(this, url, {...this.reciteSubDate,
        ... {
          signature: result.signdata,
          instruction_id: result.sid,
          address: key
        }
      });
      // const data2 = await api.post(this, url, this.reciteSubDate);
      this.load2 = false;
      if (data2.code == 200) {
        this.$notify({
          title: '提示',
          message: data2.message,
          type: 'success'
        });
        this.$emit('close');
      } else {
        this.$notify({
          title: '提示',
          message: data2.message,
          type: 'warning'
        });
      }
    },
    async tradeRecordEndorseSub() {
      this.reciteSubDate.bill_id = this.whDate.id;
      this.load2 = true;
      console.log('sssss');
      this.selfEndorse(api.config.tradeRecordEndorse);
      // // 自背书
      // if (this.isSelf) {
      //   this.selfEndorse(api.config.tradeRecordEndorse);
      // // 再背书
      // } else {
      //   this.selfEndorse(api.config.tradeRecordEndorse);
      // }
    },
    async tradeRecordEndorse(formName) {
      this.$refs[formName].validate((valid) => {
          // if (!valid) return;
          console.log('wwwwww');
        this.tradeRecordEndorseSub();
      });
    },
    closeD() {
      this.$emit('close');
      // 清空数据
      this.mainSubDate = {
        discount_applicant: '',
        annualized_rate: '',
        transfer_amount: '',
        transfer_at: '',
        contract_number: '',
        contract_file_path: '',
        bill_id: '',
        type: 1
      };
      this.reciteSubDate = {
        endorsor: '',
        annualized_rate: '',
        transfer_amount: '',
        transfer_at: '',
        contract_number: '',
        contract_file_path: '',
        bill_id: '',
        type: 3
      };
    },
    remove1(){
      this.showupload1=true;
      this.fileList=[];
    },
    remove2(){
      this.showupload2=true;
      this.fileLis2=[];
    },
    handleSuccess(response, file, fileList) {
      this.showupload1=false;
      if(response.code == 200){
        this.mainSubDate.contract_file_path = response.path
      }else{
        this.$notify({
          title:'提示',
          message:response.message,
          type:'warning'
        });
      }
    },
    handleSuccess2(response, file, fileList) {
      this.showupload2=false;
      // this.reciteSubDate.contract_file_path = response.path
      if(response.code == 200){
        this.reciteSubDate.contract_file_path = response.path
      }else{
        this.$notify({
          title:'提示',
          message:response.message,
          type:'warning'
        });
      }
    },
    handleError() {
      this.$notify.error({
        title: '错误',
        message: '图片上传失败'
      });
      return false
    },
    changeBtn(index) {
      this.btnActive = index;
      this.mainSubDate.type = index;
    },
    changeBtn2(index) {
      this.btnActive2 = index;
      this.reciteSubDate.type = index + 2;
    },
    changeTab(index) {
      if (this.isAgain && index != this.tabActive) {
        this.$notify({
          title: '提示',
          message: '不能转换贴现背书！',
          type: 'warning'
        });
        return
      }
      this.tabActive = index;
    },
    handleRemove(file, fileList) {
      console.log(file, fileList);
    },
    handlePreview(file) {
      console.log(file);
    }
  }
}
</script>
<style>
.whButton {
  background-color: #94d7f4;
  color: #fff;
  border-color: #94d7f4;
  padding: 8px 18px;
  float: left;
}

.green .whButton{
  background-color: #9bded5; 
  border-color: #9bded5;
}
.whButton:hover {
  border-color: #94d7f4;
  color: #fff;
}

.whButton.active {
  background: #2fa4e7;
  color: #fff;
  border-color: #2fa4e7;
}

.green .whButton.active {
  background: #00bca4;
  color: #fff;
  border-color: #00cb4a;
}

.whcontent_tab {
  position: absolute;
  left: 0;
  width: 100%;
  top: 60px;
  a {
    cursor: pointer;
    display: block;
    float: left;
    width: 450px;
    text-align: center;
    font-size: 18px;
    color: #808080;
    padding-bottom: 10px;
    border-bottom: 1px solid #808080;
  }
  a.active {
    color: #2fa4e7;
    border-bottom: 4px solid #2fa4e7;
  }
  a.active:nth-child(2){
    color: #00bca4;
    border-bottom: 4px solid #00bca4;
  }
}

.whdialog {
  .el-form-item__error {
    left: 140px;
    width: inherit!important;
  }
  .recordData {
    width: 100%;
  }
  .el-dialog {
    width: 900px;
    height: 770px;
    border-radius: 6px;
    &__header {
      text-align: center;
      padding-bottom: 20px;
    }
    &__title {
      font-weight: 400;
      text-align: center;
    }
    &__body {
      padding: 0px 80px;
      .whcontent.mt {
        margin-top: 50px;
      }
      .whcontent {
        /*margin-top: 50px;*/
        display: flex;
        &__info {
          flex: 1;
          margin-top: 23px;
        }
        &__img {
          padding-left: 40px;
          flex: 0.75;
          &>P {
            font-size: 14px;
            color: #333333;
            margin-bottom: 7px;
          }
          &>img {
            width: 300px;
            height: 140px;
            border-radius: 6px;
            border: #2fa4e7 1px solid;
            margin-bottom: 4px;
          }
          .el-upload {
            width: 300px;
          }
          .el-dragger {
            width: 300px;
            height: 140px;
            background: none!important;
            border: #2fa4e7 1px solid;
          }
        }
      }
    }
    .dialog-footer {
      padding-top: 20px;
      text-align: center;
      button {
        width: 300px;
        &:hover {
          background: #2fa4e7;
          border-color: #2fa4e7;
        }
        &:nth-child(2) {
          margin-left: 40px;
          background: #10b597;
          color: #fff;
          border-color: #10b597;
          &:hover {
            border-color: #10b597;
          }
        }
        ;
      }
    }
  }
}
.green img {
  border: #00bca4 1px solid !important;
}
.green .el-input .el-input__inner{
  border: #00bca4 1px solid !important;
  color: #00bac4 !important;
}
</style>
