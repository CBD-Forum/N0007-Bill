<template>
  <div class="infoconten" v-loading="loading1">
    <el-tabs :active-name="activeName">
        <el-tab-pane label="银行信息" name="first">
          <div class="bankinfo">
            <el-col :span="12">
              <el-col :span="20" :offset="3">
                <div class="contentinfo">
                  <p class="title">出入金账户</p>
                  <div class="contfo">
                    <p class="binfo"><span class="sleft">账户名</span><span class="sright">{{enterinfo.userName}}</span></p>
                    <p class="binfo"><span class="sleft">银行账号</span><span class="sright">{{enterinfo.account}}</span></p>
                  <!--  <p class="binfo"><span class="sleft">所属银行</span><span class="sright">{{enterinfo.bankName}}</span></p> -->
                    <p class="binfo khbank"><span class="sleft left1">开户银行</span><span class="sright right2">{{enterinfo.bankName}}</span></p>
                  </div>
                </div>
              </el-col>
            </el-col>
            <el-col :span="12">
              <el-col :span="20" :offset="3">
                <div class="contentinfo">
                  <p class="title">ECDS账户</p>
                  <div class="contfo">
                    <p class="binfo"><span class="sleft">账户名</span><span class="sright">{{enterinfo.name}}</span></p>
                    <p class="binfo"><span class="sleft">银行账号</span><span class="sright">{{enterinfo.bank_card_number}}</span></p>
                    <p class="binfo"><span class="sleft">银行行号</span><span class="sright">{{enterinfo.bank_code}}</span></p>
                  </div>
                </div>
              </el-col>
            </el-col>
          </div>
        </el-tab-pane>
        <el-tab-pane label="企业信息" name="second">
          <div class="enterinfo">
              <div class="enterleft">
                <p class="title">客户基本信息</p>
                <p class="binfo"><span class="sleft">企业名称</span><span class="sright">{{enterinfo.enterprise_name}}</span></p>
                <p class="binfo"><span class="sleft">企业性质</span><span class="sright">{{enterinfo.enterprise_type}}</span></p>
                <p class="binfo"><span class="sleft">经营地址</span><span class="sright">{{enterinfo.address}}</span></p>
                <p class="binfo"><span class="sleft">行业分类</span><span class="sright">{{enterinfo.industry}}</span></p>
                <p class="binfo"><span class="sleft">区域</span><span class="sright">{{enterinfo.area}}</span></p>
                <p class="binfo"><span class="sleft">营业执照</span><span class="sright">{{enterinfo.licence}}<span class="canlook" @click="showbigimg('营业执照',enterinfo.business_license_url)"> 查看</span></span></p>
                <p class="binfo"><span class="sleft">组织机构代码证</span><span class="sright">{{enterinfo.socialCreditCode}}<span class="canlook" @click="showbigimg('组织机构代码证',enterinfo.organization_code_certificate_url)">查看</span></span></p>
              </div>
              <div class="enterright">
                <div class="rightcom register">
                  <p class="title">工商注册信息</p>
                  <p class="binfo"><span class="sleft">工商注册日期</span><span class="sright">{{enterinfo.reg_date}}</span></p>
                  <p class="binfo"><span class="sleft">工商注册资金</span><span class="sright">{{enterinfo.reg_capital}}万元</span></p>
                  <p class="binfo"><span class="sleft">注册资金币种</span><span class="sright">{{enterinfo.currency}}</span></p>
                  <p class="binfo"><span class="sleft">开户许可证</span><span class="sright">开户许可证<span class="canlook" @click="showbigimg('开户许可证',enterinfo.licence_for_opening_accounts_url)">查看</span></span></p>
                  
                </div>
                <div class="rightcom telephone" v-if='change=="0"'>
                  <p class="title">联系信息</p>
                  <p class="binfo"><span class="sleft">法人姓名</span><span class="sright">{{enterinfo.legalPersonNm}}</span></p>
                  <p class="binfo"><span class="sleft">授权人</span><span class="sright">{{enterinfo.contacts}}</span></p>
                  <p class="binfo"><span class="sleft">授权人电话</span><span class="sright">{{enterinfo.contact_number}}</span></p>
                  
                  <p class="binfo"><span class="sleft">授权人邮箱</span><span class="sright">{{enterinfo.mailAddress}}</span></p>
                <p class="binfo"><span class="sleft">授权书</span><span class="sright">授权书<span class="canlook" @click="showbigimg('授权书',enterinfo.power_of_attorney_url)">查看</span></span></p>
                  
                </div>
                <div class="rightcom telephone telephone2" v-if='change=="1"'>
                  <p class="title">联系信息</p>
                  
                  <p class="binfo"><span class="sleft">法人姓名</span>
                  
                  <input v-model="enterinfoNew.legalPersonNm" class="sright2" type="" name="" :value="enterinfo.legalPersonNm">
                  </p>

                  <p class="binfo"><span class="sleft">新授权人</span>
                  
                  <input v-model="enterinfoNew.contacts" class="sright2" type="" name="" :value="enterinfo.contacts">
                  </p>
                  <p class="binfo"><span class="sleft">新授权人电话</span>
            
                  <input v-model="enterinfoNew.contact_number" class='sright2'  type="" name="" :value="enterinfo.contact_number">
                  </p>
                  <p class="binfo"><span class="sleft">新授权人邮箱</span>
            
                  <input v-model="enterinfoNew.mailAddress" class='sright2'  type="" name="" :value="enterinfo.mailAddress">
                  </p>
                  <p class="binfo">
                    <span class="sleft">原授权人手机</span>
                    
                    <span class="sright mobile1">{{enterinfo.contact_number}}</span>
                  </p>
                  <p class="binfo code-line">
                    <span class="sleft">手机验证码</span>
                    
                    <input class='sright2'  type="" name="" placeholder="">
                    <span class='seat'></span>
                    <el-button>获取验证码</el-button>
                  </p>
                  <div class="imginfo imginfo2">
                    <p class="lebll">授权书</p>
                    <div class="imgright">
                      <p>{{enterinfo.power_of_attorney_name}}</p>
                      <a class="clook" :href="enterinfo.power_of_attorney_url">查看</a>
                    </div>
                  </div>
                  <div class='btn-box'>
                    <el-button @click="changePerson('0')">取消</el-button>
                    <el-button @click="updateAuthor()">确定</el-button>
                  </div>
                  
                </div>
              </div>
          </div>
        </el-tab-pane>
    </el-tabs>
    <el-dialog :title="imgtitle" v-model="dialogVisible" size="tiny" @close="sclose()">
             <img class="billimg2" :src="imgshoww" alt="">
            <el-button type="primary" class="imgbutton" @click="dialogVisible = false">确 定</el-button>
        </el-dialog> 
        <!-- <el-dialog class='change-box' title="授权人验证" v-model="dialogVisible1" size="tiny">
             <el-form label-width="100px">
              <el-form-item label="原授权人手机">
                13237827382     
              </el-form-item>
              <el-form-item label="手机验证码">
                <el-input placeholder="请输入验证码"></el-input>
                <el-button class='code-btn'>获取验证码</el-button>     
              </el-form-item>
             </el-form>
            <el-button type="primary" class="imgbutton" @click="dialogVisible1 = false">确 定</el-button>
        </el-dialog> -->
  </div>
</template>
<script>
import api from '../api'
export default {
  data() {
    return {
      activeName: 'first',
      dialogVisible:false,
      // dialogVisible1:true,
      imgtitle:'',
      imgshoww:'',
      loading1:true,
      change:"0",
      enterinfoNew:{
        legalPersonNm:"",
        contacts:"",
        contact_number:"",
        mailAddress:"",
        captcha:"",
      },
      enterinfo:{
        enterprise_name:'',
        contact_number:'',
        contacts:'',
        address:"",
        amount:"",
        area:"",
        bankCode:"",
        bankName:"",
        bank_card_number:"",
        bank_code:"",
        business_license:"",
        business_license_name:"",
        city:"",
        corporate_ID_card:"",
        corporate_ID_card_name:"",
        country:"",
        currency:"",
        enterprise_type:"",
        industry:"",
        code_org:'',
        legalPersonNm:"",
        licence:"",
        licence_for_opening_accounts:"",
        licence_for_opening_accounts_name:"",
        mailAddress:'',
        name:"",
        organization_code_certificate:"",
        organization_code_certificate_name:"",
        power_of_attorney:"",
        power_of_attorney_name:"",
        province:"",
        reg_capital:"",
        reg_date:"",
        sBankCode:"",
        socialCreditCode:"",
        userName:"",
        SBankcName:"",
      },
    };
  },
  mounted: function() {
    this.$nextTick(() => {
      this.getinfo();
    });
  },
  methods:{
    changePerson:function(val){
      this.change=val;

    },
    sclose:function(){//关闭弹框
      this.imgshoww='';
    },
    fillperson:function(){
      this.change='1';
      this.enterinfoNew.legalPersonNm=this.enterinfo.legalPersonNm;
      this.enterinfoNew.contacts=this.enterinfo.contacts;
      this.enterinfoNew.contact_number=this.enterinfo.contact_number;
      this.enterinfoNew.mailAddress=this.enterinfo.mailAddress;
      this.enterinfoNew.captcha=this.enterinfo.captcha;

    },
    updateAuthor:function(){
      ajax(this,this.extendApi.newAuthor,this.enterinfoNew,(data)=>{
        console.log(data);
      })
    },
    async getinfo(){
      const data = await api.get(this,  api.config.getAllEnterInfo, '');
      if(data.code==200){
        this.loading1=false;
        this.enterinfo=data;
      }
    },
    showbigimg:function(name,imgurl){
      this.dialogVisible=true;
      this.imgtitle=name;
      this.imgshoww=imgurl;
    }
  },
};
</script>
<style>
  .infoconten{
    .change-box{
      .el-dialog--tiny{
        width: 430px !important;
      }
      .el-input{
        display: inline-block;
      }
      .code-btn{
        background-color: 
      } 
    }
    .el-tabs__header{
      border:0px;
    }
    .el-tabs__active-bar{
      height: 0;
    }
    .el-tabs__item{
      width: 130px;
      height: 50px;
      line-height: 50px;
      padding: 0;
      font-size: 18px;
    }
    .is-active{
      border-bottom: 4px solid #2fa5e6;
      color: #2fa5e6;
    }
    .bankinfo{
      width: 100%;
      height: 100%;
      .contentinfo{
        margin: 100px 0 100px;
        height: 300px;
        border:1px solid #2fa5e6;
        border-radius: 12px;
        overflow:hidden;
        background: #f3fbfe;
        
      }
    }
    .title{
      width: 100%;
      height: 50px;
      line-height: 50px;
      font-size: 20px;
      background: #2fa5e6;
      color: #fff!important;
      text-align: center;
      margin-bottom: 30px;
    }
    .contfo{
      width: 360px;
      margin:20px auto;
    } 
    .binfo{
        height: auto;
        /*overflow:hidden; */
        /*height: 35px;*/
        line-height: 40px;
        span{
          display: inline-block;
        }
        .sleft{
          font-size: 14px;
          width: 25%;
          text-align: right;
          color: #aaa;
        }
        .sright{
          /*white-space:nowrap; */
          width: 75%;
          font-size: 16px;
          padding-left: 16px;
          /*word-wrap: break-word;*/
          color: #666;
          .canlook{
            cursor: pointer;
            padding-left: 20px;
            color: #2fa5e6;
            font-size: 14px;
          }
        }
      }
    .khbank{
      position: relative;
    }
    .left1{
      position: absolute;
      top:0;
    }
    .right2{
      margin-left: 100px;
      word-wrap:break-word; 
      word-break:normal;
    }
    .imginfo{
      height: 75px;
      width:100%;
      /*margin-left: 36%;*/
      margin-bottom: 20px;
      .lebll{
        line-height: 35px;
        float: left;
        width: 30%;
        text-align: right;
        font-size: 14px;
        color: #aaa;
      }
      .imgleft{
        width: 68px;
        height: 75px;
        margin-left: 20px;
        margin-top: 10px;
        float: left;
        background: #aaa;
        border-radius: 10px;
      }
      .imgdoc{
        width: 68px;
        height: 75px;
        margin-left: 20px;
        margin-top: 10px;
        float: left;
        border-radius: 10px;
      }
      .imgright{
        float: left;
        padding-left: 10px;
        P{
          height: 35px;
          font-size: 16px;
          line-height: 35px;
        }
        .clook{
          color: #2fa5e6;
        }
      }
    }
    .enterinfo{
      width: 90%;
      height: 740px;
      display: flex;
      margin: 30px auto 20px;
      &>div{
        flex:1;
      }
      .enterleft{
        overflow: hidden;
        height: 740px;
        border:1px solid #2fa5e6;
        border-radius: 12px;
        background: #f3fbfe;
      }
      .enterright{
        /*overflow: hidden;*/
        margin-left: 20px;
        .rightcom{
          width: 100%;
          border:1px solid #2fa5e6;
          border-radius: 12px;
          overflow:hidden;
        }
        .register{
          background: #f3fbfe;
          height: 280px;
        }
        .telephone{
          background: #f3fbfe;
          margin-top: 20px;
          /*height: 370px;*/
          height: 440px;
          position: relative;
          .change1{
            position: absolute;
            top: 0;
            right: 20px;
            line-height: 50px;
            
            
            cursor: pointer;
            text-align: center;
            button{
              background-color: transparent;
              border: 1px solid #fff;
              line-height: 1;
              color: #fff;
              /*height: 26px;*/
              padding:6px 10px;
              font-size: 16px;
            }
          }
        }
        .telephone2{
          .title{
            height: 50px;
            line-height:50px;
          }
          .imginfo2{
            margin-bottom: 0 !important;
            height: 70px !important;
            overflow: hidden;
            .imgdoc{
              height: 100% !important;
            }
          }
          .code-line{
            input{
              width:80px;
            }
            .seat{
              display: inline-block;
              width:4px;
            }
            button{
              height: 25px;
              font-size: 12px;
              background-color: #2fa5e6;
              border:1px solid #2fa5e6;
              line-height: 25px;
              color: #fff;
              padding:0 6px;
              margin-top:;
            }
            
          }
          .sleft{
            /*padding-right: 10px;*/
          }
          .mobile1{
            font-size: 14px;
            color: #2fa5e6;
          }
          .sright2{
            border:1px solid #2fa5e6;
            border-radius: 4px;
              font-size: 14px;
              margin-left: 10px;
              padding:2px 0 2px 10px;
              
              line-height: 1;
              color: rgb(102, 102, 102);
          }
          .sright{
            width: 50%;
          }
          .btn-box{
            text-align: center;
            button{
              padding:8px 0;
              width: 20%;
              background-color:#2fa5e6;
              border:none;
              color: #fff; 
            }
          }
        }
      }
      
    }
  }
</style>