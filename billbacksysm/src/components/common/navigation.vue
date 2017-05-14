<template>
    <div class="navigation">
        <header class="main-header">
            <router-link to="/index" class="logo">
                <!-- <img style="" src="../../assets/images/haipingxian.png" alt=""> -->
            </router-link>
            <nav class="navbar navbar-static-top">
                <a class="sidebar-toggle" @click="sidebarToggle"></a>
                <div class="navbar-custom-menu">
                    <ul class="navbar-nav">
                        <li><i class="icon iconfont icon-geren" style="color:#fff;margin-right:10px;"></i>欢迎您，{{username}}</li>
                        <li><i class="icon iconfont icon-exit" style="color:#fff;margin-right:10px;"></i><a @click="exitLogin" style="cursor:pointer">退出</a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <ul class="sidebar-menu">
                    <li :class="{'active':liarr[0]}" @click="liChange(0)">
                        <router-link to="/user/home">
                            <i class="icon iconfont icon-sigekuang"></i> <span>后台主页</span>
                        </router-link>
                    </li>
                    <li :class="{'active':liarr[1]}" @click="liChange(1)">
                        <a @click="slideToggle(1)">
                            <i class="icon iconfont icon-geren"></i>
                            <span>用户管理</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <router-link to="/user/enterCode"><i class="fa fa-circle-o"></i>企业认证</router-link>
                            </li>
                            <li>
                                <router-link to="/user/enterInfo"><i class="fa fa-circle-o"></i>用户信息</router-link>
                            </li>
                            <li>
                                <router-link to="/user/managerInfo"><i class="fa fa-circle-o"></i>经办人信息</router-link>
                            </li>
                            <!-- <li>
                                <router-link to="/user/rights"><i class="fa fa-circle-o"></i>用户权限</router-link>
                            </li> -->
                            <li>
                                <router-link to="/user/assets"><i class="fa fa-circle-o"></i>用户资产</router-link>
                            </li>
                        </ul>
                    </li>

                    <li :class="{'active':liarr[2]}" @click="liChange(2)">
                        <a @click="slideToggle(2)">
                            <i class="icon iconfont icon-suanpan"></i>
                            <span>票据管理</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <router-link to="/user/billReview"><i class="fa fa-circle-o"></i>录入审核</router-link>
                                <router-link to="/user/process"><i class="fa fa-circle-o"></i>交易进程</router-link>
                            </li>
                        </ul>
                    </li>

                    <li :class="{'active':liarr[3]}" @click="liChange(3)">
                        <a @click="slideToggle(3)">
                            <i class="icon iconfont icon-23"></i>
                            <span>财务管理</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <router-link to="/user/fundTransfer"><i class="fa fa-circle-o"></i>充值</router-link>
                            </li>
                            <li>
                                <router-link to="/user/fundwithdrawal"><i class="fa fa-circle-o"></i>提款</router-link>
                            </li>
                            <li>
                                <router-link to="/user/accountTokens"><i class="fa fa-circle-o"></i>发行代币</router-link>
                            </li>
                        </ul>
                    </li>

                   <!--  <li :class="{'active':liarr[4]}" @click="liChange(4)">
                        <router-link to="/user/thawCode">
                            <i class="icon iconfont icon-sigekuang"></i> <span>解冻码管理</span>
                        </router-link>
                    </li> -->
                   <!--  <li :class="{'active':liarr[5]}" @click="liChange(5)">
                        <router-link to="/user/enterCode">
                            <i class="icon iconfont icon-sigekuang"></i> <span>企业验证码</span>
                        </router-link>
                    </li> -->
                    <li :class="{'active':liarr[6]}" @click="liChange(6)">
                        <router-link to="/user/systemConfig">
                            <i class="icon iconfont icon-sigekuang"></i> <span>系统配置</span>
                        </router-link>
                    </li>
                    <li :class="{'active':liarr[7]}" @click="liChange(7)">
                        <a @click="slideToggle(7)">
                            <i class="icon iconfont icon-suanpan"></i>
                            <span>账户开立</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <router-link to="/user/financialEntry"><i class="fa fa-circle-o"></i>财务录入</router-link>
                                <router-link to="/user/financialExamine"><i class="fa fa-circle-o"></i>财务审核</router-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </section>
        </aside>
        <div class="wrapper">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import {postUrl} from '../../../src/assets/js/api'
export default {
    data() {
            return {
                username: '--',
                liarr: [true, false, false, false, false, false],
            }
        },
        mounted: function() {
            this.$nextTick(() => {
                this.getUserInfo();
                this.flashRoute();
            })
        },
        watch: {
        '$route' (to, from) {
            console.log(to.path);
             var path = to.path;
            switch(path){
                case '/user/home':
                    this.liChange(0);
                    break;
                case '/user/enterInfo':
                case '/user/managerInfo':
                case '/user/enterCode':
                case '/user/assets':
                    this.liChange(1);
                    break;
                case '/user/billReview':
                case '/user/process':
                    this.liChange(2);
                    break;
                case '/user/fundTransfer':
                case '/user/fundwithdrawal':
                case '/user/accountTokens':
                    this.liChange(3);
                    break;
                // case '/user/thawCode':
                //     this.liChange(4);
                //     break;
                // case '/user/enterCode':
                //     this.liChange(5);
                //     break;
                case '/user/systemConfig':
                    this.liChange(6);
                    break;
            }
        }
        },
        methods: {
            /* 退出登录 */
            exitLogin: function() {
                sessionStorage.clear();
                this.$router.push({
                    path: '/index'
                });
            },
            /* 获取用户信息 */
            getUserInfo: function() {
                postUrl('/user/info', '').then(data => {
                    this.username = data.username;
                });
                
            },
            flashRoute: function() {
                var path = this.$route.path;
                 switch(path){
                    case '/user/home':
                        this.liChange(0);
                        break;
                    case '/user/enterInfo':
                    case '/user/managerInfo':
                    case '/user/enterCode':
                    case '/user/assets':
                        this.liChange(1);
                        break;
                    case '/user/billReview':
                    case '/user/process':
                        this.liChange(2);
                        break;
                    case '/user/fundTransfer':
                    case '/user/fundwithdrawal':
                    case '/user/accountTokens':
                        this.liChange(3);
                        break;
                    case '/user/thawCode':
                        this.liChange(4);
                        break;
                    case '/user/enterCode':
                        this.liChange(5);
                        break;
                    case '/user/systemConfig':
                        this.liChange(6);
                        break;
                }
            },
            /* 导航选中切换 */
            liChange: function(index) {
                this.liarr = [false, false, false, false, false, false];
                this.$set(this.liarr, index, true);
            },
            /* 判断是否触发滑动动画 */
            slideToggle: function(index){
                if(this.liarr[index] == true){
                    this.$set(this.liarr, index, false);
                    window.event.stopPropagation();
                }
            },
            /* sidebar动画 */
            sidebarToggle: function() {
                if (document.documentElement.clientWidth < 1001) {
                    document.body.classList.contains('sidebar-open') ? document.body.classList.remove('sidebar-open') : document.body.classList.add('sidebar-open');
                } else {
                    document.body.classList.contains('sidebar-collapse') ? document.body.classList.remove('sidebar-collapse') : document.body.classList.add('sidebar-collapse');
                }
            }
        }
}
</script>
<style>
.notable-data{
    width:100%;
    text-align:center;
    padding:40px 0;
    border:1px solid #eee;
    border-top: 0;
    font-size:14px;
}
.el-pager li.active{
    border-color: #22b8e5 !important;
    background: #22b8e5 !important;
}
.navigation{
    background: #222d32;
}
.main-header {
    position: relative;
    position: fixed;
    width: 100%;
    max-height: 100px;
    z-index: 1030;
    display: block;
}

.main-header .logo {
    background-color: #357ca5;
    color: #fff;
    border-bottom: 0 solid transparent;
    display: block;
    float: left;
    height: 50px;
    font-size: 20px;
    line-height: 50px;
    text-align: center;
    width: 230px;
    padding: 0 15px;
    padding-top: 10px;
    font-weight: 300;
    overflow: hidden;
    -webkit-transition: width 0.3s ease-in-out;
    -o-transition: width 0.3s ease-in-out;
    transition: width 0.3s ease-in-out;
}

.main-header .navbar {
    background-color: #3c8dbc;
    margin-bottom: 0;
    margin-left: 230px;
    -webkit-transition: margin-left 0.3s ease-in-out;
    -o-transition: margin-left 0.3s ease-in-out;
    transition: margin-left 0.3s ease-in-out;
    border: none;
    height: 50px;
    border-radius: 0;
}

.navbar-custom-menu,
.main-header .navbar-right {
    float: right;
}

.main-sidebar .iconfont {
    display: inline-block;
    width: 40px;
    font-size: 13px !important;
}

.main-sidebar {
    position: absolute;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 50px;
    min-height: 100%;
    width: 230px;
    z-index: 810;
    background-color: #222d32;
    -webkit-transition: -webkit-transform 0.3s ease-in-out, width 0.3s ease-in-out;
    -moz-transition: -moz-transform 0.3s ease-in-out, width 0.3s ease-in-out;
    -o-transition: -o-transform 0.3s ease-in-out, width 0.3s ease-in-out;
    transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
}
.sidebar-menu{
    margin-top: 30px;
}
.sidebar-menu>li {
    position: relative;
    margin: 0;
    padding: 0;
}

.sidebar-menu>li>a {
    cursor: pointer;
    border-left: 3px solid transparent;
    color: #b8c7ce;
    position: relative;
    padding: 12px 5px 12px 45px;
    display: block;
    font-size: 14px;
}

li.active .treeview-menu {
    height: 116px;
}
li:nth-child(3).active .treeview-menu {
    height: 58px;
}
li:nth-child(4).active .treeview-menu {
    height: 87px;
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
    padding: 5px 5px 5px 90px;
    display: block;
    font-size: 14px;
}
.treeview-menu>li>a:hover{
    color:#fff;
}
.treeview-menu>li>a.active {
    color: #fff;
}

.sidebar-menu>li.active>a,
.sidebar-menu>li>a:hover {
    color: #fff;
    background: #1e282c;
    border-left-color: #3c8dbc;
}

.sidebar-menu>li>.treeview-menu {
    margin: 0 1px;
    background: #2c3b41;
}

.navbar-nav li {
    color:#fff;
    float: left;
    line-height: 50px;
    font-size: 14px;
    margin-right: 60px;
}
.navbar-nav li a{
    color:#fff;
}
.navbar-nav .icon {
    width: 25px;
    color: #2fb8e3;
}

.wrapper {
    margin-left: 230px;
    padding: 30px;
    padding-top: 80px; 
    background-color: #f3f3f3;
    height: 100%;
    -webkit-transition: -webkit-transform 0.3s ease-in-out, width 0.3s ease-in-out;
    -moz-transition: -moz-transform 0.3s ease-in-out, width 0.3s ease-in-out;
    -o-transition: -o-transform 0.3s ease-in-out, width 0.3s ease-in-out;
    transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
}

.sidebar-toggle {
    display: inline-block;
    width: 50px;
    height: 50px;
    margin-left: 20px;
    background: url(../../assets/images/toggle.png) no-repeat center center;
}

/* < 767px */
@media (max-width: 1000px) {
    body{
        overflow: scroll !important;
    }
    .main-sidebar {
        padding-top: 100px;
        -webkit-transform: translate(-230px, 0);
        -ms-transform: translate(-230px, 0);
        -o-transform: translate(-230px, 0);
        transform: translate(-230px, 0);
    }

    .wrapper {
        padding-top: 130px;
        width: 100%;
        -webkit-transform: translate(-230px, 0) ;
        -ms-transform: translate(-230px, 0) ;
        -o-transform: translate(-230px, 0) ;
        transform: translate(-230px, 0) ;
    }
    .main-header .logo {
        float: none;
        width: 100%;
    }
    .main-header .navbar {
        margin-left: 0;
    }
    .sidebar-open .main-sidebar {
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        -o-transform: translate(0, 0);
        transform: translate(0, 0);
    }
    .sidebar-open .wrapper {
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        -o-transform: translate(0, 0);
        transform: translate(0, 0);
    }
    
}
/* > 768px */
@media (min-width: 1001px) {
    .sidebar-collapse .sidebar-menu li a {
        padding-left: 20px !important;
    }
    .sidebar-collapse .main-sidebar {
        width: 50px !important;
        z-index: 850;
    }
    .sidebar-collapse .wrapper {
        width: 100%;
        padding-left:80px;
        -webkit-transform: translate(-230px, 0);
        -ms-transform: translate(-230px, 0);
        -o-transform: translate(-230px, 0);
        transform: translate(-230px, 0);
    }
    .sidebar-collapse .main-sidebar .treeview-menu{
        position: absolute;
        left: 48px;
        top:30px;
    }
    .sidebar-collapse .main-sidebar .treeview-menu{
        border-radius:0 6px 6px 0;
        display: none;
    }
    .sidebar-collapse .main-sidebar .treeview-menu a{
        padding-left: 20px;
        width: 120px;
    }
    .sidebar-collapse .main-sidebar li:hover .treeview-menu{
        display: block;
    }
}
/*公共*/

.bill-list{
    border-top: 3px solid #d2d6de;
    border-radius:3px;
    border-bottom: 2px solid #d2d6de;
    background-color: #fff;
    padding: 0 30px;
    .top-title{
        font-size:18px;font-family: 'Source Sans Pro',sans-serif; padding-left:20px;margin-top:10px;margin-bottom:10px;
    }
    .ttop th{
        height: 40px !important;
        line-height: 40px !important;
        font-weight: 500;
    }

    .table1 {
        clear: both;
        width: 100%;
        overflow-x: auto;
        table {
            width: 100%;
            thead {
                width: 100%;
                border-bottom: 1px solid #eee;
                .ttop {
                    width: 100%;
                    th {
                        min-width: 80px;
                        height: 55px;
                        text-align: center;
                        line-height: 55px;
                        color: #000;
                        font-size: 15px;
                        background: #f5f8f7;
                    }
                    th:nth-child(3) {
                        min-width: 120px;
                    }
                }
            }
            tbody {
                width: 100%;
                .ttbody {
                    width: 100%;
                    border-bottom: 1px solid #eee;
                    &:hover {
                        background: #f5f5f5;
                    }
                    td {
                        padding: 0!important;
                        min-width: 80px;
                        height: 55px;
                        text-align: center;
                        font-size: 14px;
                        .Investment {
                            cursor: pointer;
                            padding: 5px 10px;
                            background: #22b8e5;
                            color: #fff;
                            border-radius: 4px;
                        }
                    }
                    td:nth-child(3) {
                        min-width: 120px;
                    }
                }
            }
        }
    }
}
</style>
