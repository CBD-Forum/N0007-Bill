<template>
  <div class="login-containerNew">
<!--     <div class="loginNew" v-show="showType == -1">
      <div class="title">
        已登录
      </div>
      <button v-on:click="returnUserCenter">返回用户中心</button>
      <button v-on:click="exitLogin">退出</button>
    </div> -->
    <div class="loginNew" >
      <!-- <div v-show="loginData.showTitle == 0" class="title">
        登录海平线
      </div> -->
      <div v-show="loginData.showTitle == 0" class="title">
        登录票据信息服务平台
      </div>
      <div v-show="loginData.showTitle == 1" class="error">
        {{loginData.errMsg}}
      </div>
      <input v-on:keyup.enter="login" type="text" placeholder="请输入邮箱或手机" v-model="loginBody.email" />
      <input v-on:keyup.enter="login" type="password" placeholder="请输入登录密码" v-model="loginBody.password" />
      <div class="row-contorl clearfix">
      </div>
      <el-button type="primary" :loading="!loginData.loginBtn" @click.native.prevent="login">{{ loginData.loginBtn? '登录':'登录中...'}}</el-button>
    </div>
  </div>
</template>
<script>
import api from '../api'
export default {
  data() {
      return {
        showType: 0,
        loginData: {
          loginBtn: true,
          showTitle: 0,
          errMsg: '',
        },
        loginBody: {
          email: "",
          password: "",
        },
      }
    },
    mounted: function() {
      sessionStorage.getItem('token') == null ? this.showType = 0 : this.showType = -1;
    },
    methods: {
      returnUserCenter: function() {
        this.$router.push({
          path: '/user'
        });
      },
      exitLogin: function() {
        sessionStorage.clear();
        this.showType = 0;
      },
      async login() {
        this.loginData.loginBtn = false;
        const data = await api.post(this,  api.config.authToken, this.loginBody);
        if (data.code == 200) {
          sessionStorage.setItem('token', data.access_token);
          let privateKey = api.getPrivatekey(this.loginBody.password, data.random);
          let publicKey = api.getPublicKey(privateKey);
          sessionStorage.setItem('privateKey', privateKey);
          sessionStorage.setItem('publicKey', publicKey);
          sessionStorage.setItem('random', data.random);
          this.$router.push({
            path: '/user'
          });
        } else {
          this.loginData.loginBtn = true;
          alert(data.message);
        }
        console.log(data);
      },
    }
}
</script>
<style>
.el-checkbox {
  font-size: 12px;
  float: left;
  color: #fff !important;
}

.el-checkbox__label {
  font-size: 12px !important;
}

.el-checkbox__inner.is-checked {
  border-color: #3bd0bd !important;
  background-color: #3bd0bd !important;
}

.reg {
  float: left !important;
}

.login-containerNew {
  width: 100%;
  height: 100%;
  background-color: rgb(34, 122, 119);
  .loginNew {
    position: absolute;
    left: 50%;
    top: 20%;
    margin-left: -175px;
    width: 350px;
    height: 350px;
    border-radius: 5px;
    background-color: rgb(34, 122, 119);
    text-align: center;
    padding: 24px 30px 0;
    .title {
      font-size: 28px;
      color: #ffffff;
    }
    .error {
      background: #c2f9f2;
      height: 24px;
      line-height: 24px;
      color: #666;
      border: 1px solid #3bd0bd;
      font-size: 12px;
    }
    input {
      width: 100%;
      height: 34px;
      border-radius: 5px;
      border: 0;
      margin-top: 25px;
      padding-left: 60px;
      color: #666;
      font-size: 13px;
    }
    .row-contorl {
      margin-top: 25px;
      a {
        color: #ffffff;
      }
      a:nth-child(1) {
        float: left;
      }
      a:nth-child(2) {
        float: right;
      }
    }
    button {
      width: 100%;
      color: #ffffff;
      background: #3bd0bd;
      border: 0;
      margin-top: 25px;
      border-radius: 5px;
      height: 34px;
      font-size: 16px;
      cursor: pointer;
    }
  }
  .forgetpasswordNew {
    width: 350px;
    height: 350px;
    border-radius: 5px;
    background-color: rgb(34, 122, 119);
    text-align: center;
    padding: 24px 30px 0;
    .title {
      font-size: 28px;
      color: #ffffff;
    }
    .input-group {
      margin: 30px 5px;
      .input-group-btn {
        .btn-default {
          color: #fff;
          background-color: #c81118;
        }
      }
    }
  }
}
</style>
