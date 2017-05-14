<template>
  <div class="nav">
    <header>
      <a class="nav__toggle "> <i class="iconfont indexicon icon-weibiaoti12"></i></a> 
      <img class="nav__logo transY" src="../assets/img/logo1.png" alt="">
      <div class="nav__custom fr">
        <a>欢迎您，{{username}}</a>
        <a style="cursor:pointer;" @click="exitLogin">退出</a>
      </div>
    </header>
    <aside class="sidebar">
      <section>
        <ul class="sidebar__menu">
        <li v-show="type == 1">
            <router-link to="/user/fcpIndex"><i class="iconfont indexicon icon-wodezhuye"></i>我的主页</router-link>
        </li>
        
        <li v-show="type == 4">
          <router-link to="/user/fcrIndex2"><i class="iconfont indexicon icon-wodezhuye"></i>我的资产</router-link>
        </li>
        <li v-show="type == 3">
          <router-link to="/user/mcrIndex"><i class="iconfont indexicon icon-wodezhuye"></i>我的主页</router-link>
        </li>
        <li v-show="type == 2">
          <router-link to="/user/fcrIndex"><i class="iconfont indexicon icon-wodezhuye"></i>我的主页</router-link>
        </li>
        <li v-show="type!=2" :class="{'active':liarr[1]}" @click="liChange(1)">
          <a @click="slideToggle(1)">
              <i class="icon iconfont icon-iconfontgupiao"></i>
              <span>交易中心</span>
              <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
          </span>
          </a>
          <ul class="treeview-menu">
              <li>
                <router-link to="/user/market"><i class="iconfont indexicon"></i>行情列表</router-link>
              </li>
              <li>
                <router-link to="/user/tradeRecord"><i class="iconfont indexicon"></i>交易记录</router-link>
              </li>
          </ul>
        </li>
       <!--  <li v-show="type!=2">
            <router-link to="/user/market"><i class="iconfont indexicon icon-geren"></i>行情列表</router-link>
        </li>
        <li v-show="type != 2">
          <router-link to="/user/tradeRecord"><i class="iconfont indexicon icon-jiaoyi"></i>交易记录</router-link>
        </li> -->
        <li v-show="type == 1">
          <router-link to="/user/examine"><i class="iconfont indexicon icon-audit"></i>票据审核</router-link>
        </li>
        <li v-show="type != 2" @click="enbill()">
          <router-link to="/user/billRecord"><i class="iconfont indexicon icon-luru"></i>录入票据</router-link>
        </li>
        
        <li v-show="type == 1 || type ==3 || type ==2" >
          <router-link to="/user/maintainRecord"><i class="iconfont indexicon icon-jilu"></i>维护记录</router-link>
        </li>
        <li v-show="type == 1">
          <router-link to="/user/fcpAccount"><i class="iconfont indexicon icon-tuijiankaihu"></i>账户开立</router-link>
        </li>
        <li v-show="type == 1 || type == 3">
          <router-link to="/user/MannageMoney"><i class="iconfont indexicon icon-caiwuguanli"></i>财务管理</router-link>
        </li>
        <li :class="{'active':liarr[2]}" @click="liChange(2)">
          <a @click="slideToggle(2)">
              <i class="icon iconfont icon-icon02"></i>
              <span>安全设置</span>
              <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
          </span>
          </a>
          <ul class="treeview-menu">
              <li>
                <router-link to="/user/password">登录密码</router-link>
              </li>
              <li v-show="type != 2">
                <router-link to="/user/tradePassword"></i>交易密码</router-link>
              </li>
          </ul>
        </li>
        <!-- <li>
          <router-link to="/user/password"><i class="iconfont indexicon icon-icon02"></i>登录密码</router-link>
        </li>
        <li v-show="type != 2">
          <router-link to="/user/tradePassword"><i class="iconfont indexicon icon-icon02"></i>交易密码</router-link>
        </li> -->
       <!--  <li v-show="type == 3 || type == 4">
          <router-link to="/user/auth"><i class="iconfont indexicon icon-suo"></i>四要素验证</router-link>
        </li> -->
        <li v-show="type != 2">
          <router-link to="/user/enterprise"><i class="iconfont indexicon icon-qiyerenzheng"></i>企业认证</router-link>
        </li>
        </ul>
        </section>
        </aside>
        <div class="wrapper">
          <router-view :enter="enter" :authstatus='authstatus' :loading="viewloding"></router-view>
        </div>
  </div>
</template>
<script>
import api from '../api'
export default {
  data() {
      return {
        type: 0,
        username: '',
        authstatus:'',
        viewloding:true,
        liarr: [false, false, false],
        enter:1,
      }
    },
    watch: {
      '$route' (to, from) {
        var path = to.path; 
          switch(path){
            case '/user/market':
            case '/user/tradeRecord':
              this.liChange(1);
            break;
            case '/user/password':
            case '/user/tradePassword':
              this.liChange(2);
            break;  
          }
        }
      },
    mounted() {
      this.$nextTick(() => {
        this.getUserInfo();
        this.flashRoute();
      });
    },
    methods: {
      flashRoute: function() {
        var path = this.$route.path;
        switch(path){
          case '/user/market':
          case '/user/tradeRecord':
            this.liChange(1);
          break;
          case '/user/password':
          case '/user/tradePassword':
            this.liChange(2);
          break;
        }
      },
      liChange: function(index) {
        this.liarr = [false, false, false];
        this.$set(this.liarr, index, true);
      },
      /* 判断是否触发滑动动画 */
      slideToggle: function(num){
        this.liarr[num]=this.liarr[num]==true?this.$set(this.liarr, num, false):this.$set(this.liarr, num, true);
        window.event.stopPropagation();
        // if(this.liarr[1] == true){
        //   this.$set(this.liarr, 1, false);
        //   window.event.stopPropagation();
        // }
        // if(this.liarr[2] == true){
        //   this.$set(this.liarr, 2, false);
        //   window.event.stopPropagation();
        // }
      },
      /*
       * 退出登录
       */
      exitLogin() {
        sessionStorage.clear();
        // location.href="https://www.haipingx.com/login.html";
        this.$router.push({
          path: '/index'
        });
      },
      enbill(){
        this.enter=this.enter==1?0:1;
      },
      /*
       * 根据不同的角色跳转不同的页面
       */
      routerPushByType(type) {
        // 仅在第一次进入首页进行判断
        if (this.$route.path != '/user') return;
        // 成员公司和外部公司首先进行四要素验证
        if (type == 3 || type == 4){
          this.$router.push({
            path: '/user/auth'
          });
          return;
        }
        if (type == 1) {
          this.$router.push({
            path: '/user/fcpIndex'
          });
        } else if (type == 2) {
          this.$router.push({
            path: '/user/fcrIndex'
          });
        } else {
          this.$router.push({
            path: '/user/mcrIndex'
          });
        }
      },
      /*
       * 获取用户信息
       */
      async getUserInfo() {
        const data = await api.post(this, api.config.userInfo);
        if (data.code == 200) {
          // console.log(data);
          this.viewloding=false;
          this.type = data.type;
          this.username = data.enterprise_name;
          sessionStorage.type = data.type;
          sessionStorage.status = data.status;
          this.authstatus=data.enterprise_status;
          sessionStorage.enterprise_status=data.enterprise_status;
          sessionStorage.uid = data.user_id;
          sessionStorage.enterprise_id=data.enterprise_id;
          sessionStorage.setItem('enterprise_name',data.enterprise_name);
          this.routerPushByType(data.type);
        } else {
          console.log(data.message);
        }
      }
    }
}
</script>
<style>
header {
  position: fixed;
  width: 100%;
  z-index: 1000;
  height: 50px;
  background: #2fa4e7;
  top: 0px;
}

.nav__toggle {
  display: inline-block;
  width: 80px;
  height: 50px;
  margin-left: 10px;
  /*background: url('../assets/img/toggle.png') no-repeat center center;*/
  i {
    color: #fff;
    font-size: 18px;
    position: absolute;
    top: 15px;
    left: 40px;
    cursor: pointer;
  }
}

.nav__logo {
  /*width: 150px;*/
  width: 140px;
  height: 40px;
}

.nav__custom {
  margin-right: 40px;
  height: 100%;
}

.nav__custom a {
  color: #fff;
  display: inline-block;
  height: 100%;
  line-height: 50px;
}

.nav__custom a:nth-child(1) {
  margin-right: 20px;
}

.sidebar {
  position: fixed;
  top: 60px;
  left: 0;
  z-index: 1000;
  width: 200px;
  background: #2d3542;
  height: 100%;
  -webkit-transition: -webkit-transform 0.3s ease-in-out, width 0.3s ease-in-out;
  -moz-transition: -moz-transform 0.3s ease-in-out, width 0.3s ease-in-out;
  -o-transition: -o-transform 0.3s ease-in-out, width 0.3s ease-in-out;
  transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
}
li.active .treeview-menu {
    height: 116px;
}

.treeview-menu {
    height: 0;
    overflow: hidden;
    -webkit-transition: height 0.6s;
    -moz-transition: height 0.6s;
    -o-transition: height 0.6s;
    transition: height 0.6s;
}

.treeview-menu>li>a {
    color: #8aa4af;
    padding: 5px 5px 5px 45px;
    display: block;
    font-size: 14px;
}
.treeview-menu>li>a:hover{
    color:#2ba7e4;
}
.treeview-menu>li>a.active {
    color: #2ba7e4;
}
@media (max-width: 900px) {
  .sidebar {
    -webkit-transform: translate(-200px, 0);
    -ms-transform: translate(-200px, 0);
    -o-transform: translate(-200px, 0);
    transform: translate(-200px, 0);
  }
  .wrapper {
    padding-top: 130px;
    width: 100%;
    -webkit-transform: translate(-200px, 0);
    -ms-transform: translate(-200px, 0);
    -o-transform: translate(-200px, 0);
    transform: translate(-200px, 0);
  }
}

.sidebar__menu li a {
  display: inline-block;
  font-size: 16px;
  height: 50px;
  line-height: 50px;
  width: 100%;
  text-align: center;
  color: #788592;
}

.sidebar__menu li a:hover,
.sidebar__menu li a.router-link-active {
  color: #fff;
  background: #20252b;
}

.wrapper {
  margin-left: 200px;
  /*margin-top: 10px;*/
  padding: 0px 10px;
  padding-top: 60px;
  height: 100%;
  background: #eaebf0;
}

.indexicon {
  margin-right: 10px;
}
</style>
