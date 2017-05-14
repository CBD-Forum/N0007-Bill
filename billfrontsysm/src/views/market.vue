<template>
  <div class="market">
    <div class="clearfix tablemain">
      <div class="title">
        <div class="clearfix">
          <h2>票据交易</h2>
          <div class="search-warp">
            <el-input class="search-warp__title" placeholder="编号/承兑人/金额" v-model="billListinglistParam.query"> </el-input>
            <el-date-picker class="search-warp__date" type="date" :editable="false" v-model="billListinglistParam.startTime" placeholder="到期日-从">
            </el-date-picker>
            <el-date-picker class="search-warp__date" type="date" :editable="false" v-model="billListinglistParam.endTime" placeholder="到期日-到">
            </el-date-picker>
            <el-button class="search-warp__submit" size="small" @click="getBillListinglist()">搜索</el-button>
          </div>
        </div>
      </div>
      <div>
        <el-table :data="billData" style="width: 100%;margin-top:10px;" stripe :default-sort="{prop: 'date', order: 'descending'}" :highlight-current-row="false" @sort-change="sortchange1">
          <el-table-column prop="possessor_name" label="持票人" min-width="180">
          </el-table-column>
          <el-table-column prop="face_amount" label="票据金额" min-width="180" class-name="companyname">
          </el-table-column>
          <el-table-column prop="acceptor" label="承兑人" width="180">
          </el-table-column>
          <el-table-column prop="financing_amount" label="融资金额（元）" sortable min-width="160">
          </el-table-column>
          <el-table-column prop="acceptance_at" label="到期日" sortable min-width="120">
          </el-table-column>
          <el-table-column prop="left_day" label="剩余日(天)" sortable min-width="180">
          </el-table-column>
          <el-table-column prop="wechselspesen" label="贴现费(元)" sortable min-width="160">
          </el-table-column>
          <el-table-column prop="annualized_rate" label="贴现率(%)" sortable min-width="120">
          </el-table-column>
          <el-table-column label="操作" width="180">
            <template scope="scope">
              <el-button v-if="billData[scope.$index].possessor != uid" type="primary" size="small" class="examine__submit" @click="showZpDialog(scope.$index,scope.row)">我要摘牌</el-button>
               <span v-if="billData[scope.$index].possessor == uid">我的票据</span>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination :page-size="5" :total="listTotal" class="table__page" layout="prev, pager, next" @current-change="getBillList">
        </el-pagination>
      </div>
    </div>
    <div class="clearfix tablemain">
      <div class="title">
        <div class="clearfix">
          <h2>我的交易</h2>
          <div class="search-warp">
            <el-button type="primary" style="width: 90px; margin-left:0" class="search-warp__all" :class='{btnActive:btnActive == 2}' size="small" @click="changeBtn(2)">交易中</el-button>
            <el-button type="primary" style="width: 90px; margin-left:0" class="search-warp__pri" :class='{btnActive:btnActive == 1}' size="small" @click="changeBtn(1)">持有中</el-button>
            <el-button type="primary" style="width: 90px; margin-left:0;margin-right:20px;" class="search-warp__pri" :class='{btnActive:btnActive == 3}' size="small" @click="changeBtn(3)">已完成</el-button>
            <el-input class="search-warp__title" placeholder="编号/承兑人/金额" v-model="myTradeListes.query"> </el-input>
            <el-date-picker class="search-warp__date" type="date" :editable="false" v-model="myTradeListes.startTime" placeholder="到期日-从">
            </el-date-picker>
            <el-date-picker class="search-warp__date" type="date" :editable="false" v-model="myTradeListes.endTime" placeholder="到期日-到">
            </el-date-picker>
            <el-button class="search-warp__submit" size="small" @click="getTradeList()">搜索</el-button>
          </div>
        </div>
      </div>
      <myTradeList :billData="tradeData" :tradeTotal="tradeTotal" :filterType="filter" v-on:reFlashBill="reFlashBill"></myTradeList>
    </div>
    <el-dialog title="摘牌" v-model="zpDialog" size="tiny" class="tradedialog examineDialog tinyD1">
      <el-form class="company-msg3" label-width='80px'>
        <el-row>
          <el-col :span="16" :offset='4'>
            <el-form-item label="票面金额">
              <el-input v-model = "zpInfo.face_amount" :disabled="true"></el-input>
            </el-form-item>
            <p class="moneybig">{{bigFace_amount}}</p>
            <el-form-item label="融资金额">
              <el-input v-model = "zpInfo.financing_amount" :disabled="true"></el-input>
            </el-form-item>
            <p class="moneybig">{{bigFinancing_amount}}</p>
            <el-form-item label="承兑人">
              <el-input v-model = "zpInfo.acceptor" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="到期日">
              <el-input v-model = "zpInfo.acceptance_at" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="剩余日">
              <el-input v-model = "zpInfo.left_day" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="贴现费">
              <el-input v-model = "zpInfo.wechselspesen" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="贴现率">
              <el-input v-model = "zpInfo.annualized_rate" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="交易密码">
              <el-input v-model = "zpInfo.tradPWD"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-row>
            <el-col :span='16' :offset='4'>
                <el-button class='sure-btn' type="primary" @click="createZp()">确认摘牌</el-button>
            </el-col>
        </el-row>
      </span>
    </el-dialog>
    <block v-if="iscontinuity==true" :open="show" :blocktype="blocktype"></block>
  </div>
</template>
<script>
import api from '../api'
import myTradeList from 'components/myTradeList'
import {smalltobig,fmoney,temptime} from '../../src/assets/js/common-js'
import block from 'components/block.vue'
export default {
  data() {
      return {
        uid: sessionStorage.uid,
        tradeTotal:0,
        btnActive: 2,
        canpay:false,
        zpDialog: false,
        billData: [],
        tradeData: [],
        starttradedata:[],
        listTotal:0,
        iscontinuity:true,//是否是连续动画
        show:false,//是否显示动画
        blocktype:"0",
        dialogData: {  // 摘牌弹框数据
          amount: '',
          bigamount:'',
          financingmoney: '',
          rate: '',
          annualized_rate:'',
          acceptor:'',
          acceptance_at:'',
          left_day:'',
          bigmoney:'',
          wechselspesen:'',
        },
        myTradeListes:{
          type:'',
          startTime:'',
          endTime:'',
          page:1,
          pageSize:10,
          filter:1,
          query:'',
          sort:'',
        },
        billListinglistParam:{
          page:1,
          pageSize:5,
          type:'',
          startTime:'',
          endTime:'',
          query:'',
          sort:'',
        },
        bigFinancing_amount:'',
        bigFace_amount:'',
        zpInfo: {
          face_amount:'',
          financing_amount:'',
          left_day:'',
          wechselspesen:'',
          annualized_rate:'',
          acceptance_at:'',
          tradPWD:'',
        },
        filter: 2,
      }
    },
    mounted: function() {
      this.$nextTick(() => {
        api.auth(this);
        this.getBillListinglist();
        this.getTradeList();
        window.satime=setInterval(()=>{
          this.getBillListinglist();
          this.getTradeList();
        }, 5000);
      })
    },
    destroyed:function(){
      clearInterval(satime);
    },
    components: {
      myTradeList,
      block
    },
    methods: {
      sortchange1(column){
        let sortType=column.order=='ascending'? '' : '-' ;
        console.log(column);
        var sort='';
        if(column.order=='descending'){
          sort=sortType+column.prop;
        }else{
          sort=column.prop;
        }
        this.billListinglistParam.sort=sort;
        this.getBillListinglist();
      },
      changeBtn(index) {
        this.btnActive = index;
        this.filter = index;
        this.getTradeList();
      },
      getBillList(val){
        this.billListinglistParam.page=val;
        this.getBillListinglist();
      },
      reFlashBill(rebilldata){
        // console.log('reflash');
        this.myTradeListes.page=rebilldata.page;
        this.myTradeListes.sort=rebilldata.sort;
        this.getBillListinglist();
        this.getTradeList();
      },
      // 我的交易列表
      async getTradeList() {
        this.myTradeListes.filter=this.filter;
        this.myTradeListes.startTime=temptime(this.myTradeListes.startTime);
        this.myTradeListes.endTime=temptime(this.myTradeListes.endTime);
        const data = await api.get(this, api.config.tradeList,this.myTradeListes);
        this.starttradedata=data.data;
        for (let x of data.data) {
          if(this.filter!=3){
            x.status = api.transBillStatus(x.status, sessionStorage.uid, x.possessor, x.investor);
          }
          x.annualized_rate=x.annualized_rate=='0.00'? '--':x.annualized_rate;
          x.wechselspesen=x.wechselspesen=='0.00'? '--':x.wechselspesen;
        }
        this.tradeData = data.data;
        this.tradeTotal= data.count;
        // console.log(data);
      },
      // 票据交易列表
      async getBillListinglist() {
        this.billListinglistParam.startTime=temptime(this.billListinglistParam.startTime);
        this.billListinglistParam.startTime=temptime(this.billListinglistParam.endTime);
        const data = await api.get(this, api.config.billListinglist,this.billListinglistParam);
        // console.log(data.code);
        this.billData = data.data;
        this.listTotal=data.count;
        // console.log(data);
      },
      // 摘牌弹框
      showZpDialog(index, row) {
        if(sessionStorage.enterprise_status!=8){
            this.$notify({
              title:"提示",
              message:"请先进行企业认证",
              type:'warning',
              duration:'2000'
            });
            setTimeout(()=>{
              this.$router.push({
                path: '/user/enterprise'
              });
            }, 2000);
        }else{
          this.zpDialog = true;
          this.zpInfo.tradPWD='';
          var samount=this.billData[index]['face_amount'].replace(/,/g,"");
          this.bigFace_amount=smalltobig(samount);
          this.zpInfo.face_amount=this.billData[index]['face_amount']+'(元)';
          this.zpInfo.financing_amount=this.billData[index]['financing_amount']+'(元)';
          this.zpInfo.left_day=this.billData[index]['left_day']+'(天)';
          this.zpInfo.wechselspesen=this.billData[index]['wechselspesen']+'(元)';
          this.zpInfo.annualized_rate=this.billData[index]['annualized_rate']+'%';
          var bsmount=this.billData[index]['financing_amount'].replace(/,/g,"");
          // bsmount=bsmount.replace(/,/g,"");
          this.bigFinancing_amount=smalltobig(bsmount);
          this.zpInfo.acceptance_at=this.billData[index]['acceptance_at'];
          this.zpInfo.acceptor=this.billData[index]['acceptor'];
          this.zpInfo.bill_number=this.billData[index]['bill_number'];
          this.zpInfo.id=this.billData[index]['id'];
          // this.zpInfo = this.billData[index];
        }
        
      },
      textnum:function(textnum){
        return textnum.replace(/[^\d.]/g,"").replace(/\.{2,}/g,".").replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');
      },
      // 摘牌
      async createZp() {
        let result =api.billNoteSigns().signOperateNote(api.userkey(),this.zpInfo.bill_number,'buy');
        const data=  await api.post(this, api.config.tradeDelist,{ ...{
          signature: result.signdata,
          instruction_id: result.sid
        },
        ...{
          id: this.zpInfo.id,
          trade_password:this.zpInfo.tradPWD,
        } 
        });
        // const data=  await api.post(this, api.config.tradeDelist, {
        //   id: this.zpInfo.id
        // });
        if(data.code == 200){
          this.zpDialog = false;
          this.show=true;
          this.blocktype="2";
          this.iscontinuity=true;
          setTimeout(()=>{
            this.show=false;
            this.blocktype="0";
            this.$notify({
              title:"提示",
              message:"操作成功",
              type:'success'
            });
            this.getTradeList();
            this.getBillListinglist();
          }, 3500);
        } else{
          this.$notify({
            title:"提示",
            message:data.message,
            type:'warning'
          });
        }
      }
    }
}
</script>
<style scoped>
.market{
  .btnActive{
    background: #207fb7;
    border-color: #207fb7;
  }
  .moneybig{
    text-align: right;
    color: #2fa4e7;
  }
}
.goexamine {
  cursor: pointer;
  &:hover {
    background: #2fa4e7;
  }
  ;
  &:visited {
    background: #258fcc;
  }
  ;
}

.fcpi__box {
  width: 50%;
  font-size: 16px;
  padding-right: 5px;
  margin-bottom: 10px;
}

.tablemain {
  background: #fff;
  border-top: #2fa4e7 3px solid;
  padding: 0 20px;
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
  width: 70%;
  padding-right: 4px;
}

.fcpi__rowR {
  width: 30%;
}

.fcpi__cell {
  height: 100%;
  line-height: 50px;
  background: #207fb7;
}

.fcpi__cell.bgwihte {
  line-height: normal;
  padding-left: 54px;
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
  /*background: red;*/
  padding-right: 2px;
}

.fcpi__tiny:nth-child(2) {
  padding: 0;
  padding-left: 2px;
}

.tiny__box {
  height: 50px;
  line-height: 50px;
  text-align: center;
  background: #207fb7;
}
</style>
