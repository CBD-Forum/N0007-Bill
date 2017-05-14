<template>
  <div>
    <el-dialog title="修改票据信息" v-model="whopenTemp" @close="closeD"  class="modifydialog">
      <div class="whcontent">
        <div class="whcontent__info">
          <el-form label-width="130px" class="demo-ruleForm">
            <el-form-item label="票据类型" >
              <el-select v-model="billInfo.type" @change="typeChange" v-if="billInfo.type_status == 0">
                <el-option v-for="item in Billoption" :label="item.label" :value="item.value" >
                </el-option>
              </el-select>
              <el-input v-else placeholder="票据类型" :disabled="true" v-model="billInfo.type"></el-input>
            </el-form-item>
            <el-form-item label="票据编号">
              <el-input placeholder="票据编号" v-model="billInfo.bill_number" :disabled="billInfo.bill_number_status != 0"></el-input>
            </el-form-item>
            <el-form-item label="出票人" >
              <el-input placeholder="出票人" v-model="billInfo.drawer" :disabled="billInfo.drawer_status != 0"></el-input>
            </el-form-item>
            <el-form-item label="收票人" >
              <el-input placeholder="收票人" v-model="billInfo.taker" :disabled="billInfo.taker_status != 0"></el-input>
            </el-form-item>
            <el-form-item label="承兑人" >
              <el-input placeholder="承兑人" v-model="billInfo.acceptor" :disabled="billInfo.acceptor_status != 0"></el-input>
            </el-form-item>
            <el-form-item label="承兑人类型" >
              <el-input placeholder="承兑人类型" v-model="billInfo.acceptor_type" v-if="billInfo.acceptor_type_status != 0" :disabled="billInfo.acceptor_type_status != 0"></el-input>
              <el-select v-model="billInfo.acceptor_type" v-else >
                <el-option
                  v-for="item in acceptor_type"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="出票日" >
              <el-date-picker
                v-model="billInfo.issue_at"
                type="date"
                :disabled="billInfo.issue_at_status !=0"
                :editable="false"
                placeholder="选择日期"
                :picker-options="pickerOptions0"
                @change="changeDate1"
                class="recordData">
              </el-date-picker>
            </el-form-item>
            <el-form-item label="票据到期日" >
              <el-date-picker
                v-model="billInfo.acceptance_at"
                type="date"
                :disabled="billInfo.acceptance_at_status !=0"
                :editable="false"
                placeholder="选择日期"
                :picker-options="pickerOptions0"
                @change="changeDate2"
                class="recordData">
              </el-date-picker>
            </el-form-item>
            <el-form-item label="票据金额" >
              <el-input placeholder="票据金额" v-model="billInfo.face_amount" :disabled="billInfo.face_amount_status != 0"></el-input>
            </el-form-item>
          </el-form>
        </div>
        <div class="whcontent__img">
          <p>上传票据（正面）</p><span>(只能上传jpeg/jpg/png文件)</span>
          <el-upload :action='url+ "upload/image"' :thumbnail-mode="true" type="drag" name="image_file" :on-success="handleSuccess" :on-error="handleError" :headers="headers" :data="{type:'10001'}">
            <i class="el-icon-upload"></i>
          </el-upload>
          <p style="margin-top:10px;">上传票据（背面）</p><span>(只能上传jpeg/jpg/png文件)</span>
          <el-upload :action='url+ "upload/image"' :thumbnail-mode="true" type="drag" name="image_file" :on-success="handleSuccess" :on-error="handleError"  :headers="headers" :data="{type:'10002'}">
            <i class="el-icon-upload"></i>
        </el-upload>
        </div>
      </div>
      <div slot="footer" class="dialog-footer">
        <el-button type="primary" @click="modifyBill" :loading="btnActive">确认提交</el-button>
        <!-- <el-button @click="closeD">背书转让</el-button> -->
      </div>
    </el-dialog>
  </div>
</template>
<script>
import api from '../api'
export default {
  props: ['whopen', 'id', 'isSelf'],
  data() {
    return {
      url:api.config.api,
      btnActive: false,
      fileList: [],
      Billoption: [{
        value: '1',
        label: '银行承兑'
      }, {
        value: '2',
        label: '商业承兑'
      }],
      acceptor_type:[],
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
      billInfo:[],
      pickerOptions0: {

      },
    }
  },
  watch: {
    async whopen(val) {
      this.billInfo = await api.get(this,  api.config.billInfo,{
        id:this.id
      });
      // console.log(this.billInfo.id)
      if(this.billInfo.type == "商业承兑"){
        this.acceptor_type = this.acceptor_typea;
      }else{
        this.acceptor_type = this.acceptor_typeb;
      }
    },
  },
  mounted: function() {
    this.$nextTick(() => {

    })
  },
  computed: {
    // 由于子组件不能修改props的值，因此添加一个clone
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
    closeD() {
      this.$emit('close');
    }, 
    changeDate1(e) {
      this.billInfo.issue_at = e;
    },
    changeDate2(e) {
      this.billInfo.acceptance_at = e;
    },
    async modifyBill() {
      this.btnActive = true;
      // 创建临时变量 是因为防止点击后数据绑定导致的 类型变为数字问题
      var temp = {};
      temp.type = api.transBillType(this.billInfo.type);
      temp.acceptor_type = api.transAcceType(this.billInfo.acceptor_type);
      temp.bill_number = this.billInfo.bill_number;
      temp.drawer = this.billInfo.drawer;
      temp.taker = this.billInfo.taker;
      temp.acceptor = this.billInfo.acceptor;
      temp.acceptance_at = this.billInfo.acceptance_at;
      temp.issue_at = this.billInfo.issue_at;
      temp.face_amount = this.billInfo.face_amount;
      temp.bill_front_path = this.billInfo.bill_front_path;
      temp.bill_back_path = this.billInfo.bill_back_path;
      temp.id =  this.billInfo.id;
      var data = await api.post(this, api.config.billUpdate,temp)
      this.btnActive = false;
      if(data.code == 200){
        this.closeD();
        this.$notify({
          title: '提示',
          message: data.message,
          type: 'success'
        });
      }else {
        this.$notify({
          title: '提示',
          message: data.message,
          type: 'warning'
        });
      }
    },
    handleSuccess(response, file, fileList) {
      if (response.type == "10001") {
        this.billInfo.bill_front_path=response.path
      }
      if (response.type == "10002") {
        this.billInfo.bill_back_path=response.path
      }
    },
    handleError() {
      this.$notify.error({
        title: '错误',
        message: '图片上传失败'
      });
      return false
    },
    typeChange(event){
        // console.log(event);
      if(event == 1){
        this.acceptor_type = this.acceptor_typeb;
      }else{
        this.acceptor_type = this.acceptor_typea;
      }
    },
  }
}
</script>

<style>
.whButton{
  background-color: #94d7f4;
  color: #fff;
  border-color: #94d7f4;
  padding: 8px 20px;
}
.whButton:hover{
  border-color: #94d7f4;
  color:#fff;
}
.whButton.active{
  background: #2fa4e7;
  color: #fff;
  border-color: #2fa4e7;
}
.whcontent_tab{
  position: absolute;
  left: 0;
  width: 100%;
  top:60px;
  a{
    cursor: pointer;
    display: block;
    float:left;
    width: 450px;
    text-align: center;
    font-size: 18px;
    color: #808080;
    padding-bottom: 10px; 
    border-bottom: 1px solid #808080;
  }
  a.active{
    color: #2fa4e7;
    border-bottom: 4px solid #2fa4e7;
  }
}
.tinyD{
  .el-dialog{
    height: 550px !important;
  }
}
.modifydialog {
  .recordData{
    width: 100%;
  }
  .el-dialog {
    width: 900px;
    height: 540px;
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
      .whcontent {
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
            margin-bottom: 6px;
            display: inline-block;
          }
          &>span {
            font-size: 12px;
            color: #808080;
          }
          &>img {
            width: 300px;
            height: 140px;
            border-radius: 6px;
            border: #2fa4e7 1px solid;
            margin-bottom: 15px;
          }
          .el-upload {
            width: 300px;
          }
          .el-dragger {
            width: 300px;
            height: 140px;
            border: #2fa4e7 1px solid;
            background: url(http://ojadcva8s.bkt.clouddn.com/bill1.jpg);
            background-size: cover;
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
</style>
