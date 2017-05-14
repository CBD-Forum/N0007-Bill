<template>
  <div class="mcrIndex">
    <div class="pub__main">
      <div class="pub__title">
        操作员认证
      </div>
      <el-col :span="24">
        <div class="pub__icon iconfont icon-password"></div>
      </el-col>
      <el-form label-width="100px" class="clearfix" style="width:550px;margin:0 auto;">
        <el-col :span="24">
          <el-form-item label="操作人姓名">
            <el-autocomplete v-model="auth.name" :fetch-suggestions="querySearch" @select="handleSelect" @keyup.native.prevent="changeinput"></el-autocomplete>
          </el-form-item>
          <el-form-item label="身份证号码">
            <el-input v-model="auth.id_number" :disabled="noregrh" @focus="inputfoucs()"></el-input>
          </el-form-item>
          <el-form-item label="银行卡号码">
            <el-input v-model="auth.bank_card_number" :disabled="noregrh"></el-input>
          </el-form-item>
          <el-form-item label="预留手机号">
            <el-input v-model="auth.mobile" :disabled="noregrh"></el-input>
          </el-form-item>
          <el-form-item label="短信验证码">
            <el-col :span="12">
              <el-input v-model="auth.captcha"></el-input>
            </el-col>
            <el-col :span="12">
              <el-button style="" type="primary" :disabled='btnDis' class="codeBtn" @click="sendCaptcha">{{btnText}}</el-button>
            </el-col>
          </el-form-item>
        </el-col>
        <el-col :span="24" class="pub_btnBox">
          <el-button type="primary" @click="authCer" :loading="btnActive">确认提交</el-button>
        </el-col>
      </el-form>
    </div>
  </div>
</template>
<script>
import api from '../api'
export default {
  data() {
      return {
        btnDis:false,
        btnActive:false,
        btnText: '获取验证码',
        auth: {
          name: '',
          mobile: '',
          id_number: '',
          bank_card_number: '',
          captcha: ''
        },
        noregrh:false,
        upselect:0,
        susuth:{
          name: '',
          mobile: '',
          id_number: '',
          bank_card_number: '',
          captcha: ''
        },
        acceptor_type: [],
        showImg: false,
        swiperSlides: [],
        restaurants: []
      }
    },
    mounted: function() {
      this.$nextTick(() => {
        this.getAgentList();
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

    },
    methods: {
      async getAgentList() {
        const data = await api.get(this, api.config.agentList);
        // console.log(data);
        this.restaurants = data.data.map((e) => ({
          value: e.name,
          name: e.name,
          mobile: e.mobile,
          id_number: e.id_number,
          bank_card_number: e.bank_card_number
        }));
      },
      changeinput:function(){
        this.auth.mobile='',
        this.auth.id_number='',
        this.auth.bank_card_number='',
        this.noregrh=false;
        this.upselect=0;
      },
      formatter(row, column) {
        return api.toThousands(row.face_amount);
      },
      inputfoucs(){
        this.upselect=0;
      },
      /* 选择融资经办人 */
      handleSelect: function(item) {
        this.upselect=1;
        this.susuth=item;
        this.noregrh=true;
        this.auth.bank_card_number =item.bank_card_number.replace((item.bank_card_number.substring(4,item.bank_card_number.length-4)),'**************');
        this.auth.id_number=item.id_number.replace(item.id_number.substring(4,item.id_number.length-4),'**********');
        this.auth.mobile=item.mobile.replace(item.mobile.substring(3,item.mobile.length-3),'*****');
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
      // 操作员认证
      async authCer() {
        this.btnActive = true;
        var fromdata={};
        if(this.upselect==0){
          fromdata=this.auth;
        }else{
          this.susuth.captcha=this.auth.captcha;
          fromdata=this.susuth;
        }
        const data = await api.post(this, api.config.authCer, fromdata);
        this.btnActive = false;
        if (data.code == 200) {
          this.$notify({
            title: '提示',
            message: '验证成功',
            type: 'success',
            duration:'1000',
          });
          sessionStorage.isAuth = '1';
          if(sessionStorage.type==4){
            if(sessionStorage.enterprise_status!=8){
              this.$router.push({
                path: '/user/enterprise'
              });
            }else{
              this.$router.push({
                path: '/user/fcrIndex2'
              });
            }
          }
          if(sessionStorage.type==3){
            if(sessionStorage.status==4){
              this.$router.push({
                path: '/user/password'
              });
            }else{
              this.$router.push({
                path: '/user/mcrIndex'
              });
            }
          }
        } else {
          this.$notify({
            title: '提示',
            message: data.message,
            type: 'warning'
          });
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
        var mobile='';
        if(this.upselect==0){
          mobile=this.auth.mobile;
        }else{
          mobile=this.susuth.mobile;
        }
        const data = await api.post(this, api.config.sendCaptcha, {
          mobile: mobile
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
