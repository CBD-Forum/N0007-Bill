<template>
  <div class="password">
    <div class="pub__main" v-if="!hasSet">
      <div class="pub__title">
        设置交易密码
      </div>
      <el-col :span="24">
        <div class="pub__icon iconfont icon-password"></div>
      </el-col>
      <el-form  label-width="120px" class="clearfix passwordFrom" style="width:550px;margin:0 auto;"  :model="pwd" >
        <el-col :span="24">
          <el-form-item label="交易密码">
            <el-input type="password" placeholder="请设置交易密码" v-model="pwd.new_trade_password"></el-input>
          </el-form-item>
          <el-form-item label="重复交易密码">
            <el-input type="password" placeholder="请重复交易密码" v-model="pwd.repeat_trade_password"></el-input>
          </el-form-item>
        </el-col>
        <el-col :span="24" class="pub_btnBox">
          <el-button :loading = 'btnActive' type="primary" @click="setPassword()">确认提交</el-button>
        </el-col>
      </el-form>
    </div>
    <div class="pub__main" v-if="hasSet">
      <div class="pub__title">
        修改交易密码
      </div>
      <el-col :span="24">
        <div class="pub__icon iconfont icon-password"></div>
      </el-col>
      <el-form  label-width="120px" class="clearfix passwordFrom" style="width:550px;margin:0 auto;"  :model="changePwd" >
        <el-col :span="24">
          <el-form-item label="原交易密码">
            <el-input type="password" placeholder="请输入原交易密码" v-model="changePwd.trade_password"></el-input>
          </el-form-item>
          <el-form-item label="新交易密码">
            <el-input type="password" placeholder="请输入新交易密码" v-model="changePwd.new_trade_password"></el-input>
          </el-form-item>
          <el-form-item label="重复交易密码">
            <el-input type="password" placeholder="请重复新交易密码" v-model="changePwd.repeat_trade_password"></el-input>
          </el-form-item>
        </el-col>
        <el-col :span="24" class="pub_btnBox">
          <el-button :loading = 'btnActive' type="primary" @click="changePassword()">确认提交</el-button>
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
      hasSet: true,
      btnActive: false,
      pwd:{
        new_trade_password:'',
        repeat_trade_password:''
      },
      changePwd:{
        trade_password:'',
        new_trade_password:'',
        repeat_trade_password:''
      }
    }
  },
  mounted() {
    api.auth(this);
  },
  methods: {
    /**
     * [设置交易密码]
     */
    async setPassword() {
      this.btnActive = true;
      const data = await api.post(this,  api.config.securitySetTradePassword, this.pwd);
      if(data.code == 200){
        this.$notify({
          title:'提示',
          message:data.message,
          type:'success'
        })
        this.btnActive = false;
      }else{
        this.$notify({
          title:'提示',
          message:data.message,
          type:'warning'
        });
        this.btnActive = false;
      }
    },
    /**
     * [修改交易密码]
     */
    async changePassword() {
      this.btnActive = true;
      const data = await api.post(this,  api.config.securityChangeTradePassword, this.changePwd);
      if(data.code == 200){
        this.$notify({
          title:'提示',
          message:data.message,
          type:'success'
        })
        this.btnActive = false;
      }else{
        this.$notify({
          title:'提示',
          message:data.message,
          type:'warning'
        });
        this.btnActive = false;
      }
    },
  }
}
</script>
<style scoped>
.pub__main {
  height: 870px;
}
</style>
<style>
.passwordFrom{
  .el-form-item__error{
    left: 270px;
    text-align: right;
    width: 150px;
  }
}
</style>
