<template>
  <div class="getRechange">
    <el-dialog title="充值" :close-on-click-modal="false" v-model="Recharge" size="tiny">
      <div class="diagcontent">
          <div class="rechang" v-if="message==1">
              <p><span class="pleft">收款户名</span><span class="pright">{{rechargeBank.name}}</span></p>
              <p><span class="pleft">收款银行</span><span class="pright">中信银行 徐汇支行</span></p>
              <p><span class="pleft">收款账号</span><span class="pright">{{rechargeBank.accno}}</span></p>
              <div class="buttomok" @click = "recharge_done">已完成充值</div>
          </div>
          <p class="error" v-if="message==2">暂未查到最新充值资金，请稍后再试</p>
          <p class="succss" v-if="message==3">充值已到账</p>
      </div>
    </el-dialog>
    <el-dialog title="提款" v-model="drawing" size="tiny">
      <div class="usercontent">
        <el-form ref="form" :model="form" label-width="80px">
          <el-form-item label="企业名称">
              <p>{{enterprise_name}}</p>
          </el-form-item>
          
          <el-form-item label="提款金额">
              <el-input style="width:80%" v-model="form.amount" placeholder="请输入提款金额" type="number"></el-input>
          </el-form-item>
          <el-form-item label="交易密码">
              <el-input style="width:80%" v-model="form.trade_password" placeholder="请输入交易密码" :type="typeType" @focus="getflur"></el-input>
          </el-form-item>
        </el-form>
        <el-button type="primary" :loading="cashoutLoading" @click="docashout">确认提款</el-button>
      </div>
    </el-dialog>
    <!-- <block :open="show" :single="true" :singletype="singletype"></block> -->
  </div>
</template>
<script>
  import api from '../api'
  import block from './block.vue'
  export default{
    props:['dialogtype','ischaneg'],
    data(){
      return{
        typeType:'text',
        cashoutLoading:false,
        message:1,
        drawing: false, //提款弹框
        Recharge:false,
        show:false,
        singletype:'0',
        rechargeBank:{
          name:"",
          accno:""
        },
        form:{
          amount:'',
          trade_password:'',
          signature:'',
          instruction_id:'',
        },
        enterprise_name:'',
      }
    },
    watch:{
      ischaneg:function(val){
        if(this.dialogtype=='chzhi'){
          this.Recharge=true;
        }else{
          this.form.amount='';
          this.form.trade_password='';
          this.drawing=true;
        }
      }
    },
    components: {
      block,
    },
    mounted:function(){
      this.$nextTick(() => {
        this.enterprise_name=sessionStorage.enterprise_name;
        if(this.Recharge){
          api.post(this, api.config.getBankInfo,(data)=>{
            console.log(data);
          });
        }
      })
      
    },
    methods:{
      async docashout(){
        // let publicKey=sessionStorage.publicKey;
        let uid=sessionStorage.uid;
        console.log(api.bankPublick);
        let result = api.billNoteSigns().signUserWithdraw(api.userkey(),api.config.bankPublick,uid,this.form.amount*100);
        this.form.signature= result.signdata;
        this.form.instruction_id= result.sid;
        const data=await api.post(this, api.config.getmoney,this.form);
        this.drawing=false;
        if(data.code==200){
          this.singletype="2";
          this.show=true;
          setTimeout(()=>{
            this.singletype="0";
            this.show=false;
            this.$notify({
              title: '成功',
              message: '提款成功',
              type: 'success'
            });
            this.$emit('reFlashBill');
          }, 3500);
        }else{
          this.$notify({
            title: '提示',
            message: '提款失败',
            type: 'error'
          });
        }
      },
      getflur(){
        console.log(11111);
        this.typeType='password';
      },
      recharge_done(){

      },
    },
  }
</script>
<style>
  .getRechange{
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button{
        -webkit-appearance: none !important;
        margin: 0; 
    }
    .el-dialog__header{
      text-align: center!important;
    }
    input[type="number"]{-moz-appearance:textfield;}
    .el-dialog--tiny{
      width: 660px;
      border-radius: 10px;
    }
    .el-dialog__title{
      color: #2ba7e4;
    }
    .el-dialog__body {
      .diagcontent {
        padding: 25px 0 45px;
        p {
          height: 40px;
          line-height: 40px;
          span {
            display: inline-block;
          }
          .pleft {
            width: 205px;
            text-align: right;
            color: #aaa;
            font-size: 16px;
          }
          .pright {
            width: 400px;
            font-size: 18px;
            text-align: left;
            padding-left: 35px;
          }
        }
        .error,
        .succss {
          font-size: 18px;
        }
        .buttomok {
          cursor: pointer;
          margin: 20px auto 0;
          width: 249px;
          height: 35px;
          line-height: 35px;
          text-align: center;
          color: #fff;
          border-radius: 4px;
          background: #2ba7e4;
        }
      }
      .usercontent {
        width: 390px;
        text-align: center;
        margin: 40px auto 30px;
        .el-input__inner {
          border: 1px solid #2ba7e4;
          height: 36px;
        }
        .phonecode {
          width: 170px;
          float: left;
        }
        .moneynum {
          color: #2ba7e4;
          font-size: 14px;
          text-align: left;
          margin-bottom: -20px;
          margin-top: -20px;
        }
        .getcode {
          width: 110px;
          float: right;
          background: #2ba7e4;
          border-color: #2ba7e4;
        }
        .newbank {
          width: 100%;
          border-radius: 4px;
          background: #2ba7e4;
          text-align: center;
          height: 30px;
          line-height: 30px;
          color: #fff;
          }
        }
        .banklist {
          width: 100%;
          height: auto;
          /*padding: 10px 30px 30px 25px;*/
          overflow: hidden;
          .bankinfo {
            float: left;
            margin-bottom: 20px;
            padding: 15px 20px;
            width: 280px;
            height: 80px;
            border: 1px solid #2ba7e4;
            border-radius: 4px;
            margin-left: 20px;
            p {
              height: 25px;
              line-height: 25px;
              text-align: left;
              span {
                display: inline-block;
              }
              .pleft {
                width: 170px;
                font-size: 18px;
              }
              .pright {
                width: 58px;
                color: #2ba7e4;
                font-size: 12px;
              }
            }
          }
        }
    }
  }
</style>