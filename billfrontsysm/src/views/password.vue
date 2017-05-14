<template>
  <div class="password">
    <div class="pub__main">
      <div class="pub__title">
        修改登录密码
      </div>
      <el-col :span="24">
        <div class="pub__icon iconfont icon-mima"></div>
      </el-col>
      <el-form  label-width="120px" class="clearfix passwordFrom" style="width:550px;margin:0 auto;"  :model="pwd" :rules="pwdruls" ref="pwd" >
        <el-col :span="24">
          <el-form-item label="原登录密码"  prop="password">
            <el-input type="password" placeholder="请输入原登录密码" v-model="pwd.password"></el-input>
          </el-form-item>
          <el-form-item label="新登录密码"  prop="new_password">
            <el-input type="password" placeholder="请设置新登录密码" v-model="pwd.new_password"></el-input>
          </el-form-item>
          <el-form-item label="重复新密码"  prop="repeat_password">
            <el-input type="password" placeholder="请重复新登录密码" v-model="pwd.repeat_password"></el-input>
          </el-form-item>
        </el-col>
        <el-col :span="24" class="pub_btnBox">
          <el-button :loading = 'btnActive' type="primary" @click="passwordSubmit('pwd')">确认提交</el-button>
        </el-col>
      </el-form>
    </div>
  </div>
</template>
<script>
import api from '../api'
export default {
  data() {
    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请再次输入密码'));
      } else if (value !== this.pwd.new_password) {
        callback(new Error('两次输入密码不一致!'));
      } else {
        callback();
      }
    };
    return {
      btnActive: false,
      pwd:{
        password:'',
        new_password:'',
        repeat_password:'',
        signature:'',
        instruction_id:''
      },
      pwdruls: {
        password: [
          { required: true, message: '请输入密码', trigger: 'blur' },
        ],
        new_password: [
          { required: true, message: '请输入新密码', trigger: 'blur' }
        ],
        repeat_password: [
          { required: true, validator: validatePass2, trigger: 'blur' }
        ]
      }
    }
  },
  mounted() {
    api.auth(this);
  },
  methods: {
    /**
     * [密码修改]
     */
    async changePassword() {
      let uid=sessionStorage.getItem('uid');
      let prive_key=api.getPrivatekey(this.pwd.new_password,sessionStorage.getItem('random'));
      let publick_key=api.getPublicKey(prive_key);
      let result=api.billNoteSigns().signTransferAsset(api.userkey(),uid,publick_key);
      this.pwd.signature= result.signdata,
      this.pwd.instruction_id= result.sid
      const data = await api.post(this,  api.config.changePassword, this.pwd);
      if(data.code == 200){
        this.$notify({
          title:'提示',
          message:'修改成功',
          type:'success',
          duration:'1000'
        })
        sessionStorage.setItem('privateKey',prive_key);
        sessionStorage.setItem('publicKey',publick_key);
        this.getUserInfo();
        this.btnActive = false;
        setTimeout(()=>{
          this.$router.push({
            path: '/user/market'
          });
        }, 1000);
      }else{
        this.$notify({
          title:'提示',
          message:data.message,
          type:'warning'
        });
        this.btnActive = false;
      }


      // this.btnActive = true;
      // // console.log(sessionStorage.random);
      // // console.log(this.pwd.new_password);
      // var pri = api.getPrivatekey(this.pwd.new_password, sessionStorage.random);
      // var pub = api.getPublicKey(pri);

      // if(sessionStorage.status == "4" && sessionStorage.type == "3"){
      //   console.log('创建公司');
      //   var result = api.billNoteSigns().signCreateCompany(api.userkey(),pub);
      // }else{
      //   console.log('资产转移');
      //   var result = api.billNoteSigns().signTransferAccount(api.userkey(),sessionStorage.publicKey,pub);
      // }
      // // console.log(result);
      // let body = {
      //   signature:  result.signdata,
      //   instructionId: result.sid
      // }
      // const data = await api.post(this,  api.config.changePassword, {...body, ...this.pwd});
      // if(data.code == 200){
      //   this.$notify({
      //     title:'提示',
      //     message:'修改成功',
      //     type:'success'
      //   })
      //   sessionStorage.publicKey = pub;
      //   sessionStorage.privateKey = pri;
      //   this.btnActive = false;
      //   // 如果是成员公司第一次登陆修改密码，要求跳转首页进行操作员认证 
      //   if(sessionStorage.status == "4" && sessionStorage.type == "3" ){
      //     sessionStorage.status = 2;
      //     this.$router.push({
      //       path: '/user/mcrIndex'
      //     });
      //   }
      // }else{
      //   this.$notify({
      //     title:'提示',
      //     message:data.message,
      //     type:'warning'
      //   });
      //   this.btnActive = false;
      // }
    },
     /*
       * 获取用户信息
       */
    async getUserInfo() {
      const data = await api.post(this, api.config.userInfo);
      if (data.code == 200) {
        console.log(data);
        sessionStorage.type = data.type;
        sessionStorage.status = data.status;
        sessionStorage.uid = data.user_id;
        sessionStorage.enterprise_id=data.enterprise_id;
      } else {
        console.log(data.message);
      }
    },
    passwordSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.changePassword();
        } else {
          console.log('error submit!!');
          return false;
        }
      })
    }
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
