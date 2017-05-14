<template>
  <div>
    <el-dialog title="挂牌" v-model="gpDialog" size="tiny" class="tradedialog examineDialog tinyD1">
      <el-form class="company-msg3" label-width='80px'>
        <el-row>
          <el-col :span="16" :offset='4'>
            <el-form-item label="票面金额">
              <el-input class="billmoney" :value="financingmoney.amount" :disabled="true"></el-input>
            </el-form-item>
            <p class="bigs">{{bigFinancingmoneyAmount}}</p>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="7" :offset='4'>
            <el-form-item label="贴现率">
              <el-input class="billmoney" v-model="financingmoney.lossrate" @keyup.native.prevent="calculation(3)" @blur="againcalculation()"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="1"><span style="position: relative;top: 5px;">%</span></el-col>
          <el-col :span="8">
            <el-form-item class="cankao"  label="参考贴现率:">
              <div class="showrate"><span>22-23</span></div>
            </el-form-item>
          </el-col>
          <el-col :span="16" :offset='4'>
            <el-form-item label="贴现费">
              <div class="dwgroup">
                <el-input class="billmoney" v-model="financingmoney.lossmoney" @keyup.native.prevent="calculation(2)"></el-input>
                <span class="danwei">元</span>
              </div>
            </el-form-item>
          </el-col>
          <el-col :span="16" :offset='4'>
            <el-form-item label="融资金额">
              <div class="dwgroup">
                <el-input class="billmoney" placeholder="必填" v-model="financingmoney.financing_amount" @keyup.native.prevent="calculation(1)"></el-input>
                <span class="danwei">元</span>
              </div>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-row>
            <el-col :span='16' :offset='4'>
                <el-button class='sure-btn' type="primary" @click="createGp()">确认挂牌</el-button>
            </el-col>
        </el-row>
      </span>
    </el-dialog>
  </div>
</template>
<script>
import api from '../api'
import {fmoney,smalltobig} from '../../src/assets/js/common-js'
export default{
  props:['dialogData','isshow'],
  data(){
    return{
      gpDialog:false,
      bigFinancingmoneyAmount:'',
      financingmoney: {         // 挂牌自动计算数据
        financing_amount: '', // 融资金额
        id:'',
        amount: '',           // 票面金额
        lossmoney: '',        // 帖限额
        lossrate: '',         // 贴现率
        left_day:'', 
        bill_number:'',       
      },
    }
  },
  watch:{
    isshow:function(){
      this.gpDialog=true;
      this.financingmoney.financing_amount='';
      this.financingmoney.lossmoney='';
      this.financingmoney.lossrate='';
      setTimeout(()=>{
        this.financingmoney.amount = parseFloat(this.dialogData.face_amount).toFixed(2);
        // console.log(this.financingmoney.amount);
        var tobig=this.financingmoney.amount.replace(/,/g,"");
        this.bigFinancingmoneyAmount=smalltobig(tobig);
        this.financingmoney.id = this.dialogData.id;
        this.financingmoney.left_day=this.dialogData.left_day;
        this.financingmoney.bill_number=this.dialogData.bill_number;
      }, 200);
    }
  },
  methods:{
    // 挂牌
    async createGp(){
      this.financingmoney.financing_amount=this.financingmoney.financing_amount.replace(/,/g,"");
      var amount_fian=this.financingmoney.financing_amount*100;
      let result=api.billNoteSigns().signSellNote(api.userkey(), this.financingmoney.bill_number,amount_fian);
      const data = await api.post(this, api.config.tradeListing, {
        financing_amount: this.financingmoney.financing_amount,
        id: this.financingmoney.id,...{
          signature: result.signdata,
          instruction_id: result.sid
        },
      });
      // const data = await api.post(this, api.config.tradeListing, {
      //   financing_amount: this.financingmoney.financing_amount,
      //   id: this.financingmoney.id
      // });
      if(data.code == 200){
        this.iscontinuity=true;
        this.blocktype="1";
        this.show=true;
        setTimeout(() => {
          this.show = false;
          this.blocktype="0";
          this.$notify({
            title: '提示',
            message: '挂牌成功，已录入区块链',
            type: 'success'
          });
        }, 3500);
        this.gpDialog = false;
        this.$emit('reFlashBill');
      } else{
        this.$notify({
          title:"提示",
          message:data.message,
          type:'warning'
        });
      }
    },
    //贴现率失去焦点重新计算
    againcalculation:function(){
      let A=this.financingmoney.amount;
      A=A.replace(/,/g,"");
      let a=this.financingmoney.financing_amount;
      a=a.replace(/,/g,"");
      let n=this.financingmoney.left_day;
      let c=this.financingmoney.lossmoney;
      c=c.replace(/,/g,"");
      a=parseFloat(a).toFixed(2);
      if(!isNaN(a)){
        this.financingmoney.financing_amount=a;
        this.financingmoney.lossmoney=parseFloat(A-a).toFixed(2);
      }else{
        this.financingmoney.financing_amount=this.financingmoney.amount;
        this.financingmoney.lossmoney='0.00';
      }
      
      // console.log(A-a);
      
      let year = ((c / (A * n) * 360) * 100).toFixed(2);
      if(isNaN(year) || (year=='Infinity')){
        year='0.00'
      }else{
        this.financingmoney.lossrate = year; 
      }
    },
    /* 
     * 自动计算贴现率公式 年利率  
     */
    calculation: function(inputnum) {
        let A = this.financingmoney.amount;
        A = parseFloat(A.replace(/[^\d\.-]/g, ""))
        let n = this.financingmoney.left_day;
        this.financingmoney.lossmoney=this.textnum(this.financingmoney.lossmoney);//限制只能输入数字和小数点后两位
        this.financingmoney.financing_amount=this.textnum(this.financingmoney.financing_amount);
        this.financingmoney.lossrate=this.textnum(this.financingmoney.lossrate);
        let a = String(this.financingmoney.financing_amount);
        let fa= this.financingmoney.lossmoney;
        let fyear=this.financingmoney.lossrate;
        if(inputnum==1){//输入融资金额
            // a=(a.match(/\d+(\.\d{0,3})?/)||[''])[0];
            a=parseFloat(a).toFixed(2);
             // 如果是负数或者不是数字，则退回
            if(parseInt(a) > parseInt(A)){
                // a = a.substr(0, a.length-1);
                a = A;
                this.financingmoney.financing_amount = A;
            }
            // this.bigmoney2=smalltobig(this.financingmoney.financing_amount);
            let c = A - a;
            c=parseFloat(c).toFixed(2);
            let year = ((c / (A * n) * 360) * 100).toFixed(2);
            // console.log(a);
            if(isNaN(year) || (year=='Infinity')){
                year='0.00'
            }
            if(a != '' && (a!='NaN')){
                this.financingmoney.lossmoney = fmoney(parseFloat(c));
                this.financingmoney.lossrate = year; 
            }else{
                this.financingmoney.lossmoney = '0.00';
                this.financingmoney.lossrate = '0.00';  
            }
        }else if(inputnum==2){//输入贴现费
            if(parseInt(fa)>parseInt(A)){
                fa=A;
                this.financingmoney.lossmoney=fa;
            }
            let year = ((fa / (A * n) * 360) * 100).toFixed(2);
            if(isNaN(year) || (year=='Infinity')){
                year='0.00';
            }
            a=A-fa;
            a=parseFloat(a).toFixed(2);
            // this.bigmoney2=smalltobig(a);
             if(fa != '' && fa!='NaN'){
                this.financingmoney.financing_amount = fmoney(parseFloat(a));
                this.financingmoney.lossrate = year;  
            }else{
                this.financingmoney.financing_amount = '0.00';
                // this.bigmoney2=smalltobig(this.financingmoney.financing_amount);
                this.financingmoney.lossrate = '0.00';  
            }
        }else{//输入贴现率
            fa=(fyear * A * n)/(360*100);
            if(parseInt(fa)>parseInt(A)){
                fa=A;
                this.financingmoney.lossmoney=fa;
                let year = ((fa / (A * n) * 360) * 100).toFixed(2);
                if(isNaN(year) || (year=='Infinity')){
                    year='0.00'
                }
                a=A-fa;
                // this.bigmoney2=smalltobig(a);
                 if(fa != '' && fa!='NaN'){
                    this.financingmoney.financing_amount = fmoney(parseFloat(a));
                    // this.financingmoney.financing_amount = this.fmoney(this.financingmoney.financing_amount);
                    this.financingmoney.lossrate = year;  
                }else{
                    this.financingmoney.financing_amount = '0.00';
                    // this.bigmoney2=smalltobig(this.financingmoney.financing_amount);
                    this.financingmoney.lossrate = '0.00';  
                }
            }else{
                a=A-fa;
                a=parseFloat(A-fa).toFixed(2);
               if(parseInt(a) > parseInt(A)){
                    a = A;
                }
                this.financingmoney.financing_amount = a;
                // this.bigmoney2=smalltobig(this.financingmoney.financing_amount);
                let c = A - a;
                c=parseFloat(c).toFixed(2);
                if(a != '' && a!='NaN'){
                    this.financingmoney.lossmoney = fmoney(parseFloat(c));
                    this.financingmoney.financing_amount = fmoney(parseFloat(a));  
                }else{
                    this.financingmoney.lossmoney = '0.00';
                    this.financingmoney.lossrate = '0.00';  
                }
            }
        }
        this.financingmoney.financing_amount = fmoney(this.financingmoney.financing_amount);
        this.financingmoney.lossmoney = fmoney(this.financingmoney.lossmoney);
    },
    textnum:function(textnumm){
      console.log(textnumm);
      return textnumm.replace(/[^\d.]/g,"").replace(/\.{2,}/g,".").replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');
    },
  }
}
</script>
<style>
  .tradedialog {
    .bigs{
      text-align: right;
     color: #2fa4e7;
    }
  .info{
    text-align: left;
  }
  .recordData {
    width: 100%;
  }
  .el-dialog {
    width: 660px;
    border-radius: 6px;
    &__header {
      text-align: center;
    }
    &__title {
      font-weight: 400;
      text-align: center;
    }
  }
  .el-dialog__footer{
    text-align: center!important;
  }
  .el-dialog__body{
    text-align: center!important;
  }
  .el-dialog__title{
    font-size: 18px!important;
    color:#2fa4e7!important;
  }
  .cankao .el-form-item__label{
    padding-right: 0;
  }
  .el-form-item{

    position: relative;
  }
  .showrate{
    height: 36px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background: #eff2f7;
    color: #bbb;
    text-align: center;
  }
  .billmoney{
    position: relative;
  }
  .danwei{
    position: absolute;
    right: 10px;
    top:0;
  }
  .sure-btn{
    width: 100%;
  }
}
</style>