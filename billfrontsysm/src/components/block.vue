<!--区块链动画有两种情况 一种为交易动画分为四步  一种为单链动画  演示点击行情列表中的票据交易的重置按钮即可 -->
<!--引用与声明
    ##引入 
    import block from '../user/block.vue'  //引入
    ##声明
    components: {  
        block
    }
-->
<!--:open参数为控制弹框的打开关闭  true为打开  false为关闭-->

<!--交易动画分为四步 挂牌（1），摘牌（2），转让（3），收款（4） 传入 blocktype等于对应的数值即可-->
<!-- <block :open="show" blocktype="1"></block> -->

<!--单链动画 必须声明  即 :single="true" -->
<!--单链动画有三种情况，分别任撤单（3）,入金（1）,提款（2） 传入 singletype等于对应的数值即可-->
<!-- <block :open="show" :single="true" singletype="3"></block> -->
<template>
    <div class="blockk">
        <el-dialog v-model="open" class="gpanm" :class="{smsingle:single}" top="30%" >
            <div class="gp-block clearfix single" v-if="single">
                <h4 v-if="singletype != '0'" :class= "{masked:single}">
                    <span class='iconfont icon-lian' v-for="i in 4"></span>
                </h4>
                <div v-if="singletype != '0'" class="hexagon">
                  <span :class="{font_active:font_bg}">{{singlemsg}}</span>
                  <div class="iconfont icon-lbx" v-for="t in 3" :class="'box_'+t"></div>
                </div>
                <h4 class="marl">
                    <span class='iconfont icon-lian' v-for="i in 4"></span>
                </h4>
            </div>
            <div class="gp-block clearfix" v-else>
                <h4 :class="{masked: blocktype=='1',lian_active: blocktype>1}">
                    <span class='iconfont icon-lian' v-for="i in step.step_a"></span>
                </h4>
                <div class="hexagon" v-if="blocktype=='1'">
                  <span :class="{font_active:font_bg}">{{step.name_a}}</span>
                  <div class="iconfont icon-lbx" v-for="t in 3" :class="'box_'+t"></div>
                </div>
                <div class="iconfont icon-lbx sixbor_active" v-if="blocktype!=='1'">
                  <span>{{step.name_a}}<span>
                </div>

                <h4 :class="{masked: blocktype=='2',marl:blocktype=='1',lian_active: blocktype>2}" >
                  <span class='iconfont icon-lian' v-for="i in step.step_b"></span>
                </h4>

                <div class="hexagon" v-if="blocktype=='2'">
                  <span :class="{font_active: font_bg}">{{step.name_b}}</span>
                  <div class="iconfont icon-lbx" v-for="t in 3" :class="'box_'+t"></div>
                </div>
                <div class="iconfont icon-lbx" v-if="blocktype!=='2'" :class="{sixbor_active: blocktype>2}">
                  <span>{{step.name_b}}<span>
                </div>

                <h4 :class="{masked: blocktype=='3',marl:blocktype=='2',lian_active: blocktype>3}" >
                  <span class='iconfont icon-lian' v-for="i in step.step_c"></span>
                </h4>

                <div class="hexagon" v-if="blocktype=='3'">
                  <span :class="{font_active:font_bg}">{{step.name_c}}</span>
                  <div class="iconfont icon-lbx" v-for="t in 3" :class="'box_'+t"></div>
                </div>
                <div class="iconfont icon-lbx" v-if="blocktype!=='3'" :class="{sixbor_active: blocktype>3}">
                  <span>{{step.name_c}}<span>
                </div>

                <h4 :class="{masked: blocktype=='4',marl:blocktype=='3',}" >
                  <span class='iconfont icon-lian' v-for="i in step.step_d"></span>
                </h4>

                <div class="iconfont icon-lbx" v-if="blocktype!='4'">
                  <span>{{step.name_d}}<span>
                </div>
                <div class="hexagon"v-if="blocktype=='4'">
                  <span :class="{font_active:font_bg}">{{step.name_d}}</span>
                  <div class="iconfont icon-lbx" v-for="t in 3" :class="'box_'+t"></div>
                </div>

            </div >
                
            <div v-if="single == true">
                <div class="mts" v-if="singletype=='3'"  :class="{'icon-animation':singletype=='3'}">
                    <img src="../assets/img/qm.png" />
                    <img src="../assets/img/block.png" />
                    <img src="../assets/img/hy.png" />
                    <img src="../assets/img/block.png" />
                </div>
                <div class="mts" v-if="singletype=='4'"  :class="{'icon-animation':singletype=='4'}">
                    <img src="../assets/img/qm.png" />
                    <img src="../assets/img/block.png" />
                    <img src="../assets/img/hy.png" />
                    <img src="../assets/img/block.png" />
                </div>
                <div class="mts" v-if="singletype=='1'" :class="{'icon-animation':singletype=='1'}" >
                    <img src="../assets/img/qm.png" />
                    <img src="../assets/img/bank.png" />
                    <img src="../assets/img/block.png" />
                    <img src="../assets/img/block.png" />
                </div>

                <div class="mts" v-if="singletype=='2'" :class="{'icon-animation':singletype=='2'}" >
                    <img src="../assets/img/qm.png" />
                    <img src="../assets/img/bank.png" />
                    <img src="../assets/img/block.png" />
                    <img src="../assets/img/block.png" />
                </div>
            </div>
            <div v-if="single != true">
                <div v-if="blocktype=='1'" :class="{'icon-animation':blocktype=='1'}">
                    <img src="../assets/img/qm.png" />
                    <img src="../assets/img/block.png" />
                    <img src="../assets/img/hy.png" />
                    <img src="../assets/img/block.png" />
                </div>
                <div v-if="blocktype=='2'" :class="{'icon-animation':blocktype=='2'}"> 
                    <img src="../assets/img/bank.png" />
                    <img src="../assets/img/qm.png" />
                    <img src="../assets/img/block.png" />
                    <img src="../assets/img/hy.png" />
                    <img src="../assets/img/block.png" />
                </div>
                <div v-if="blocktype=='3'" :class="{'icon-animation':blocktype=='3'}">
                    <img src="../assets/img/ecds.png" />
                    <img src="../assets/img/qm.png" />
                    <img src="../assets/img/block.png" />
                    <img src="../assets/img/hy.png" />
                    <img src="../assets/img/block.png" />
                </div>
                <div v-if="blocktype=='4'" :class="{'icon-animation':blocktype=='4'}">
                    <img src="../assets/img/ecds.png" />
                    <img src="../assets/img/qm.png" />
                    <img src="../assets/img/bank.png" />
                    <img src="../assets/img/block.png" />
                    <img src="../assets/img/hy.png" />
                    <img src="../assets/img/block.png" />
                </div>
            </div>
        </el-dialog>
    </div>
</template>
<script>
export default {
    props: ['open','blocktype','single','singletype'],
    /* 监听open */
    watch: {
        singletype:function(val){
            console.log('动画状态',val);
        },
        open: function(val) {
            // open为true执行动画,为false清空数据
            if (val == true) {
                var that = this;
                this.font_bg = false;
                
                if(this.single){
                    switch(this.singletype){
                        case "1":
                            this.singlemsg="入金中"
                        break;
                        case "2":
                            this.singlemsg="提款中"
                        break;
                        case "3":
                            this.singlemsg="撤单中"
                        break;
                        default:
                            this.singlemsg="撤消中"
                    };
                    setTimeout(function(){
                        that.font_bg = true;
                        switch(that.singletype){
                            case "1":
                                that.singlemsg="入金成功"
                            break;
                            case "2":
                                that.singlemsg="提款成功"
                            break;
                            case "3":
                                that.singlemsg="撤单成功"
                            break;
                            default:
                                that.singlemsg="撤消成功"
                        }
                        setTimeout(function(){
                            that.singletype = "0"
                        },1500)
                    },2000)  
                }else{
                    console.log("当前状态",this.blocktype)
                    this.step = {
                        step_a : 2,
                        step_b : 2,
                        step_c : 2,
                        step_d : 2,
                        name_a : "挂牌",
                        name_b : "摘牌",
                        name_c : "确认转让",
                        // name_d : "确认收款"
                        name_d : "确认收票"
                    }
                    switch(this.blocktype){
                        case "1":
                            this.step.step_a = 6;
                            this.step.name_a = this.showmsg;
                        break;
                        case "2":
                            this.step.step_b = 6;
                            this.step.name_b = this.showmsg;
                        break;
                        case "3":
                            this.step.step_c = 6;
                            this.step.name_c = this.showmsg;
                        break;
                        default:
                            this.step.step_d = 6;
                            this.step.name_d = this.showmsg;
                    };
                    
                    setTimeout(function(){
                        that.font_bg = true;
                        switch(that.blocktype){
                            case "1":
                                that.step.name_a = that.endmsg;
                            break;
                            case "2":
                                that.step.name_b = that.endmsg;
                            break;
                            case "3":
                                that.step.name_c = that.endmsg;
                            break;
                            default:
                                that.step.name_d = that.endmsg;
                        }
                        setTimeout(function(){
                            that.blocktype = "0"
                        },1500)
                    },2000) 
                }
                
            } else {
                 this.open = false;
            }
        }
    },
    data() {
        return {
            step:{
                name_a:"挂牌",
                name_b:"摘牌",
                name_c:"确认转让",
                name_d:"确认收票",
                step_a:2,
                step_b:2,
                step_c:2,
                step_d:2,
            },
            singlemsg:"",
            iconan:"",
            font_bg : false
        }
    },
    computed:{
        showmsg:function(){
            var blocktype = this.blocktype;
            switch(blocktype){
                case "1":
                    return "挂牌中";
                break;
                case "2":
                    return "摘牌中";
                break;
                case "3":
                    return "转让中";
                break;
                default:
                    return "确认中"; 
            }
        },
        endmsg:function(){
            var blocktype = this.blocktype;
            switch(blocktype){
                case "1":
                    return "挂牌成功";
                break;
                case "2":
                    return "摘牌成功";
                break;
                case "3":
                    return "确认转让";
                default:
                    return "确认收票"; 
            }
        }
    }
}
</script>
<style>
.blockk{
.smsingle{
    .el-dialog{
        w:460px!important;
        h:220px!important;
        bg:rgba(29, 42, 63,0.7);
        border-radius:10px;
    }
    .icon-animation{
        width:430px!important;
    }
}
.mts{
    mt:35px!important;
}
.gpanm{
    .icon-animation{
        w:600px;
        h:60px;
        m:20px auto;
        ta:center;
        img{
            h:50px;
            m:0px 15px;
            animation: img-animation 0.7s infinite linear;
            animation-iteration-count:1;
            &:nth-of-type(2){
                animation-delay: 0.3s; /* W3C 和 Opera */
                -moz-animation-delay: 0.3s; /* Firefox */
                -webkit-animation-delay: 0.3s; /* Safari 和 Chrome */
            };
            &:nth-of-type(3){
                animation-delay: 0.6s; /* W3C 和 Opera */
                -moz-animation-delay: 0.6s; /* Firefox */
                -webkit-animation-delay: 0.6s; /* Safari 和 Chrome */
            };
            &:nth-of-type(4){
                animation-delay: 0.9s; /* W3C 和 Opera */
                -moz-animation-delay: 0.9s; /* Firefox */
                -webkit-animation-delay: 0.9s; /* Safari 和 Chrome */
            };
            &:nth-of-type(5){
                animation-delay: 1.2s; /* W3C 和 Opera */
                -moz-animation-delay: 1.2s; /* Firefox */
                -webkit-animation-delay: 1.2s; /* Safari 和 Chrome */
            };
            &:nth-of-type(6){
                animation-delay: 1.5s; /* W3C 和 Opera */
                -moz-animation-delay: 1.5s; /* Firefox */
                -webkit-animation-delay: 1.5s; /* Safari 和 Chrome */
            };
            
        }
        .imgrest{
            img{
                animation: img-rest 0.7s infinite linear;
            }
        }
    }
    .icon-lian{
        c:#306184;
    }
    .lian_active{
        .iconfont{
          c:#2fa4e7;
        }
    }
    .sixbor_active{
        c:#2fa4e7!important;
        span{
            c:#fff!important;
        }
    }
    .el-dialog__headerbtn{
        margin-top:-13px;
        mr:-10px;
    }
    .el-dialog{
        w:800px;
        h:230px;
        bg:rgba(29, 42, 63,0.7);
        border-radius:10px;
    }
    .gp-block{
        mt:5px;
        ml:15px;
        pos:relative;
        h4{
            fl:left;
            mt:30px;
            &:nth-of-type(1){
                ml:0px;
            }
            &.marl{
                ml:85px;
            }
        }
        .hexagon{
            pos: absolute;
            &:nth-of-type(1){
                l:200px;
            };
            &:nth-of-type(2){
                l:345px;
            };
            &:nth-of-type(3){
                l:490px;
            };
            &:nth-of-type(4){
                l:640px;
            };
            span{
                z-index:100;
                pos: absolute;
                lh: 56px;
                l:10px;
                ta:center;
                fz:12px;
                w:60px;
                c:#fff;
                t:15px;
                animation:chanfont 2s linear infinite;
                animation-iteration-count:1;
            }
            .icon-lbx{
                pos: absolute;
                ml:0px;
                fz:80px;
                t:0px;
                c:#21aeff;
            }
        }
        .icon-lbx{
            c:#306184;
            fl: left;
            fz:80px;
            pos: relative;
            ta:center;
            span{
                z-index: 100;
                position: absolute;
                line-height: 56px;
                left: 10px;
                text-align: center;
                font-size: 12px;
                top: 15px;
                width: 60px;
                color: #82b7d4;
                opacity: 1;
            }
            
            &.box_1{
                opacity: 0.3;
                animation:gobig 2s linear infinite;
                animation-iteration-count:1;
            };
            &.box_2{
                opacity: 0.4;
                animation:gobigb 2s linear infinite;
                animation-iteration-count:1;
            };
            &.box_3{
                opacity: 1;
                animation:gobigc 1.5s linear infinite;
                animation-iteration-count:1;
            };
        }
    }
    .single{
        ml:40px;
        .hexagon{
            l:135px!important
        }
    }
}
.el-dialog__header{
        text-align: center !important;
    }
.el-dialog__body {
    text-align: center;
    .el-checkbox__label {
        color: #e01c1c !important;
    }
    .el-checkbox__inner.is-checked {
        border-color: #1fbae6 !important;
        background-color: #1fbae6 !important;
    }
}
}
</style>

<style scoped>
@keyframes gobig
{
  0% {
    -webkit-transform:scale(1);
  }
  12.5% {
    -webkit-transform:scale(1.35);
  }
  25% {
    -webkit-transform:scale(1.7);
  }
  37.5% {
    -webkit-transform:scale(1.35);
  }
  50% {
    -webkit-transform:scale(1);
  }
  62.5% {
    -webkit-transform:scale(1.35);
  }
  75% {
    -webkit-transform:scale(1.7);
  }
  87.5% {
    -webkit-transform:scale(1.35);
  }
  100% {
    -webkit-transform:scale(1);
  }
}

@keyframes gobigb
{
  0% {
    -webkit-transform:scale(0);
  }
  12.5% {
    -webkit-transform:scale(0.75);
  }
  25% {
    -webkit-transform:scale(1.35);
  }
  37.5% {
    -webkit-transform:scale(1.35);
  }
  50% {
    -webkit-transform:scale(1);
  }
  62.5% {
    -webkit-transform:scale(1.35);
  }
  75% {
    -webkit-transform:scale(1.35);
  }
  87.5% {
    -webkit-transform:scale(1.35);
  }
  100% {
    -webkit-transform:scale(1);
  }
}
@keyframes gobigc
{
  0% {
    -webkit-transform:scale(0);
  }
  25% {
    -webkit-transform:scale(0.5);
  }
  50% {
    -webkit-transform:scale(1);
  }
  75% {
    -webkit-transform:scale(1);
  }
  100% {
    -webkit-transform:scale(1);
  }
}

@keyframes chanfont
{
  0% {
    -webkit-transform:scale(0);
  }
  12.5% {
    -webkit-transform:scale(1.2);
  }
  25% {
    -webkit-transform:scale(1.5);
  }
  37.5% {
    -webkit-transform:scale(1.2);
  }
  50% {
    -webkit-transform:scale(1);
  }
  62.5% {
    -webkit-transform:scale(1.2);
  }
  75% {
    -webkit-transform:scale(1.5);
  }
  87.5% {
    -webkit-transform:scale(1.2);
  }
  100% {
    -webkit-transform:scale(1);
  }
}
.font_active{
    fz:16px!important;
    l:20px!important;
    t:22px!important;
    w:40px!important;
    lh:22px!important;
}
.masked{
    background-image: -webkit-linear-gradient(left,#00a2ff,#0c72b2 30%,#174265 70%,#174265 100%);
    -webkit-text-fill-color: transparent;
    -webkit-background-clip: text;
    background-size: 800% 100%;
    animation: masked-animation 2s infinite linear;
    animation-iteration-count:1;
}

@keyframes masked-animation {
    0%  { background-position: 100% 0;}
    100% { background-position: 0% 0;}
}

@keyframes img-animation {
    0%  { opacity: 0;-webkit-transform:scale(1.5);}
    20%  { opacity: 0.2;-webkit-transform:scale(2.5);}
    100% { opacity: 1;-webkit-transform:scale(1);}
}

@keyframes img-rest {
    0%  { opacity: 0;-webkit-transform:scale(1.5);}
}

@keyframes masked-rest {
    0%  { background-position: 100% 0;}
}
</style>
