<template>
  <div class="tabs-content">
    <div>
      <el-table :data="billData" style="width: 100%;margin-top:10px;" stripe :default-sort="{prop: 'date', order: 'descending'}"@sort-change="tradechange">
        <el-table-column prop="drawer" label="出票人" min-width="180">
        </el-table-column>
        <el-table-column prop="face_amount" label="票据金额（元）" min-width="160" class-name="companyname">
        </el-table-column>
        <el-table-column prop="acceptor_type" label="承兑人" width="180">
        </el-table-column>
        <el-table-column prop="acceptance_at" label="到期日" min-width="180">
        </el-table-column>
        <el-table-column v-if="filterType!=3" prop="left_day" label="剩余日" sortable width="120">
        </el-table-column>
        <el-table-column prop="wechselspesen" label="贴现费（元）" sortable width="160">
        </el-table-column>
        <el-table-column prop="annualized_rate" label="贴现率(%)" sortable min-width="180">
        </el-table-column>
        <el-table-column prop="status" label="状态" sortable min-width="160">
        </el-table-column>
        <el-table-column  v-if="filterType!=3" label="操作" min-width="220">
          <template scope="scope">
            <!-- 等待审核 -->
            <span v-if="billData[scope.$index].status == '待审核' &&  billData[scope.$index].possessor == uid">等待审核</span>
            <!-- <span v-if="billData[scope.$index].status == '审核失败' &&  billData[scope.$index].possessor == uid">审核未通过</span> -->
            <el-button v-if="billData[scope.$index].status == '审核失败' &&  billData[scope.$index].possessor == uid" type="primary" size="small" class="examine__submit" @click="updatebill2(billData[scope.$index].id)">修改</el-button>
            <!-- 挂牌/撤销挂牌 -->
            <el-button v-if="billData[scope.$index].status == '持有中' &&  billData[scope.$index].possessor == uid" type="primary" size="small" class="examine__submit" @click="showGpDialog(scope.$index,scope.row)">挂  牌</el-button>
            <el-button v-if="billData[scope.$index].status == '挂牌中' &&  billData[scope.$index].possessor == uid" type="primary" size="small" class="examine__submit btnRed" @click="showCxgpDialog(scope.$index,scope.row)">撤销挂牌</el-button>
            <!-- 付款/等待投资人付款 -->
            <el-button v-if="billData[scope.$index].status == '待付款' &&  billData[scope.$index].investor == uid" type="primary" size="small" class="examine__submit" @click="showQfkDialog(scope.$index,scope.row)">去付款</el-button>
            <span v-if="billData[scope.$index].status == '待投资人付款' &&  billData[scope.$index].possessor == uid">等待投资人付款</span>
            <!-- 拥有者ECDS转让，查看/投资者等待 -->
            <el-button v-if="billData[scope.$index].status == '待拥有人转让' &&  billData[scope.$index].possessor == uid" type="primary" size="small" class="examine__submit" @click="showEcdsDialog(scope.$index,scope.row)">ECDS已转让</el-button>
            <el-button v-if="billData[scope.$index].status == '待拥有人转让' &&  billData[scope.$index].possessor == uid" type="primary" size="small" class="examine__submit" @click="checkECDS(billData[scope.$index].investor)">查看</el-button>
            <el-button v-if="billData[scope.$index].status == '待拥有人转让' &&  billData[scope.$index].investor == uid" type="primary" size="small" class="examine__submit" @click="showCxgp2Dialog(scope.$index,scope.row)">撤销交易</el-button>
            <!-- 投资者取消撤单/拥有者同意撤单，拒绝撤单 -->
            <el-button v-if="(billData[scope.$index].status == '待拥有人同意撤销' || billData[scope.$index].status =='拥有者拒绝撤单') &&  billData[scope.$index].investor == uid" type="primary" size="small" class="examine__submit btnRed" @click="showQxcdDialog(scope.$index,scope.row)">取消撤单</el-button>
            <span v-if="billData[scope.$index].status =='拒绝撤单' &&  billData[scope.$index].possessor == uid">拒绝撤单</span>
            <el-button v-if="billData[scope.$index].status == '待拥有人同意撤销' &&  billData[scope.$index].possessor == uid" type="primary" size="small" class="examine__submit"  @click="showTycdDialog(scope.$index,scope.row)">同意撤单</el-button>
            <el-button v-if="billData[scope.$index].status == '待拥有人同意撤销' &&  billData[scope.$index].possessor == uid" type="primary" size="small" class="examine__submit btnRed" @click="showJjcdDialog(scope.$index,scope.row)">拒绝撤单</el-button>
            <!-- 投资者等待收票/拥有者确认收票，撤单 -->
            <span v-if="billData[scope.$index].status == '待投资人确认收到票据' &&  billData[scope.$index].possessor == uid">待投资人确认收到票据</span>
            <el-button v-if="billData[scope.$index].status == '待投资人确认收到票据' &&  billData[scope.$index].investor == uid" type="primary" size="small" class="examine__submit" @click="showQrspDialog(scope.$index,scope.row)">确认收票</el-button>
            <el-button v-if="billData[scope.$index].status == '待投资人确认收到票据' &&  billData[scope.$index].investor == uid" type="primary" size="small" class="examine__submit" @click="showCxgp2Dialog(scope.$index,scope.row)">撤销交易</el-button>

            <span v-if="billData[scope.$index].status == '待拥有人收款' &&  billData[scope.$index].investor == uid">待拥有人收款</span>
            <el-button v-if="billData[scope.$index].status == '待收款' &&  billData[scope.$index].possessor == uid" type="primary" size="small" class="examine__submit" @click="showSqDialog(scope.$index,scope.row)">收款</el-button>
            <span v-if="billData[scope.$index].status == '待退款' &&  billData[scope.$index].investor == uid">待拥有人退款</span>
            <el-button v-if="billData[scope.$index].status == '待退款' &&  billData[scope.$index].possessor == uid" type="primary" size="small" class="examine__submit" @click="showtkDialog(scope.$index,scope.row)">退款</el-button>
          </template>
        </el-table-column>
        <el-table-column prop="state" label="协议" width="100" v-if="filterType!=3">
          <template scope="scope">
            <i v-if="billData[scope.$index].status =='待拥有人转让' ||
               billData[scope.$index].status =='待投资人确认收到票据' || 
               billData[scope.$index].status =='待收款'||
               billData[scope.$index].status =='待拥有人同意撤销' || 
               billData[scope.$index].status =='待投资人确认收到票据' ||
               billData[scope.$index].status =='待退款' || 
               billData[scope.$index].status =='待拥有人同意撤销' || 
               billData[scope.$index].status =='待投资人确认收到票据' || 
               billData[scope.$index].status =='待拥有人收款'" 
               class="iconfont icon-shangchuanxieyi1"
               @click="uploadAgreement(billData[scope.$index].id,billData[scope.$index].possessor,billData[scope.$index].investor)">
               </i>
            <a v-if="billData[scope.$index].investor == uid && (billData[scope.$index].possessor_contract_url!=null )" download :href="billData[scope.$index].possessor_contract_url"><i  class="iconfont icon-xiazaixieyi1"></i></a>
            <a v-if="billData[scope.$index].possessor == uid && (billData[scope.$index].investor_contract_url!=null )" download :href="billData[scope.$index].investor_contract_url"><i  class="iconfont icon-xiazaixieyi1"></i></a>
            
          </template>
        </el-table-column>
        <el-table-column prop="contract_url" label="协议" width="100" v-if="filterType==3">
          <template scope="scope">
            <a v-if="(billData[scope.$index].contract_url!=null )" download :href="billData[scope.$index].contract_url"><i  class="iconfont icon-xiazaixieyi1"></i></a>
          </template>
        </el-table-column>
      </el-table>
      <el-pagination class="table__page" layout="prev, pager, next" :total="tradeTotal" :page-size="10" @current-change="changePage">
      </el-pagination>
    </div>
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
    <el-dialog class="tradedialogss" title="投资人ECDS信息" v-model="ECDSDialog" size="tiny">
    <el-form label-width="80px">
      <el-form-item label="企业名称">
        <p class="info">{{ECDSInfo.name}}</p>
      </el-form-item>
      <el-form-item label="银行账号">
        <p class="info">{{ECDSInfo.bank_card_number}}</p>
      </el-form-item>
      <el-form-item label="银行行号">
        <p class="info">{{ECDSInfo.bank_code}}</p>
      </el-form-item>
    </el-form>
     <!--  <p class="EcdsInfo"><span>企业名称</span></p>
      <p class="EcdsInfo"><span>银行账号</span><span>66666666666666</span></p>
      <p class="EcdsInfo"><span>银行行号</span><span>145525225</span></p> -->
    </el-dialog>
    <el-dialog class="tradedialog" title="提示" v-model="cxgpDialog" size="tiny">
      <span>是否确认撤销挂牌?</span>
      <span slot="footer" class="dialog-footer" align="center">
          <el-button class='cancel-btn2' type="primary" @click="createCxgp()">确认撤单</el-button>
          <el-button class="cancel-btn" @click="cxgpDialog = false">考虑一下</el-button>
      </span>
    </el-dialog>
    <el-dialog class="tradedialog" title="提示" v-model="cxgp2Dialog" size="tiny">
      <span>是否确认撤单?</span>
      <span slot="footer" class="dialog-footer" align="center">
          <el-button class='cancel-btn2' type="primary" @click="createCxgp2()">确认撤单</el-button>
          <el-button class="cancel-btn" @click="cxgp2Dialog = false">考虑一下</el-button>
      </span>
    </el-dialog>
    <el-dialog class="tradedialog" title="提示" v-model="qxcdDialog" size="tiny">
      <span>是否确认取消撤单?</span>
      <span slot="footer" class="dialog-footer" align="center">
          <el-button class='cancel-btn2' type="primary" @click="createQxcd()">确认</el-button>
          <el-button class="cancel-btn" @click="qxcdDialog = false">考虑一下</el-button>
      </span>
    </el-dialog>
    <el-dialog class="tradedialog" title="提示" v-model="tycdDialog" size="tiny">
      <span class="querentk">是否确认同意撤单?</span>
      <span>交易密码</span>
      <el-input v-model="skTradePassword" placeholder="交易密码" class="inputtpwd"></el-input>
      <span slot="footer" class="dialog-footer" align="center">
          <el-button class='cancel-btn2' type="primary" @click="createTycd()">确认</el-button>
          <el-button class="cancel-btn" @click="tycdDialog = false">考虑一下</el-button>
      </span>
    </el-dialog>
    <el-dialog class="tradedialog" title="提示" v-model="jjcdDialog" size="tiny">
      <span>是否确认拒绝撤单?</span>
      <span slot="footer" class="dialog-footer" align="center">
          <el-button class='cancel-btn2' type="primary" @click="createJjcd()">确认</el-button>
          <el-button class="cancel-btn" @click="jjcdDialog = false">考虑一下</el-button>
      </span>
    </el-dialog>
    <el-dialog class="tradedialog" title="提示" v-model="ecdsDialog" size="tiny">
      <span>是否确认ECDS转让?</span>
      <span slot="footer" class="dialog-footer" align="center">
          <el-button class='cancel-btn2' type="primary" @click="createEcds()">确认</el-button>
          <el-button class="cancel-btn" @click="ecdsDialog = false">考虑一下</el-button>
      </span>
    </el-dialog>
    <el-dialog class="tradedialog" title="提示" v-model="qrspDialog" size="tiny">
      <span>是否确认收票?</span>
      <span slot="footer" class="dialog-footer" align="center">
          <el-button class='cancel-btn2' type="primary" @click="createQrsp()">确认</el-button>
          <el-button class="cancel-btn" @click="qrspDialog = false">考虑一下</el-button>
      </span>
    </el-dialog>
    <el-dialog class="tradedialog" title="提示" v-model="sqDialog" size="tiny">
      <span class="querentk">是否确认收款?</span>
      <span>交易密码</span>
      <el-input v-model="skTradePassword" placeholder="交易密码" class="inputtpwd"></el-input>
      <span slot="footer" class="dialog-footer" align="center">
          <el-button class='cancel-btn2' type="primary" @click="createSq()">确认</el-button>
          <el-button class="cancel-btn" @click="sqDialog = false">考虑一下</el-button>
      </span>
    </el-dialog>
    <el-dialog class="tradedialog" title="提示" v-model="tkDialog" size="tiny">
      <span class="querentk">是否确认退款?</span>
      <span>交易密码</span>
      <el-input v-model="skTradePassword" placeholder="交易密码" class="inputtpwd"></el-input>
      <span slot="footer" class="dialog-footer" align="center">
          <el-button class='cancel-btn2' type="primary" @click="createtk()">确认</el-button>
          <el-button class="cancel-btn" @click="tkDialog = false">考虑一下</el-button>
      </span>
    </el-dialog>
    <el-dialog title="付款" v-model="fkDialog" size="tiny" class="tradedialog examineDialog tinyD1">
      <el-form class="company-msg3" label-width='80px'>
        <el-row>
          <el-col :span="16" :offset='4'>
            <el-form-item label="票面金额">
              <el-input v-model="fkData.face_amount"  :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="融资金额">
              <el-input v-model="fkData.financing_amount"  :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="承兑人">
              <el-input v-model="fkData.acceptor"  :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="到期日">
              <el-input v-model="fkData.acceptance_at"  :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="剩余日">
              <el-input v-model="fkData.left_day"  :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="贴现费">
              <el-input v-model="fkData.wechselspesen"  :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="贴现率">
              <el-input v-model="fkData.annualized_rate"  :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="交易密码">
              <el-input v-model="tradePassword"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-row>
            <el-col :span='16' :offset='4'>
                <el-button class='sure-btn' type="primary" @click="createQfk">确认付款</el-button>
            </el-col>
        </el-row>
      </span>
    </el-dialog>
    <el-dialog class="cancel-pp uploadAgree" title="上传协议" v-model="dialogAgreement" size="tiny">
      <el-form>
        <el-form-item>
          <el-col :span="24">
              <el-upload  :action="uploadFile.uploadUrl" :data="uploaddata" :thumbnail-mode="true" type="drag" :name="fillname"  :on-success="handleSuccess" :default-file-list="fileList" :headers="headers">
                  <i class="el-icon-upload"></i>
              </el-upload>
          </el-col>
          <el-button type="primary" class="imgbutton" @click="dialogAgreement = false">确 定</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <block v-if="iscontinuity==true" :open="show" :blocktype="blocktype"></block>
    <block v-if="iscontinuity==false" :open="show" :single="true" :singletype="singletype"></block>
  </div>
</template>
<script>
import api from '../api'
import {fmoney,smalltobig} from '../../src/assets/js/common-js'
import block from './block.vue'
export default {
  props: ['billData','tradeTotal','filterType'],
  data() {
    return {
      total: 0,
      rebilldata:{
        page:1,//分页使用
        sort:'',
      },
     
      uid: sessionStorage.uid,
      gpDialog: false,
      cxgpDialog: false,
      cxgp2Dialog: false,
      qxcdDialog: false,
      tycdDialog: false,
      jjcdDialog: false,
      fkDialog:false,
      qrspDialog:false,
      ecdsDialog: false,
      sqDialog: false,
      ECDSDialog:false,
      tkDialog:false,
      dialogAgreement:false,//上传协议
      bigFinancingmoneyAmount:'',
      skTradePassword:'',
      iscontinuity:true,//判断是否是连续动画
      show: false, // 控制区块链动画
      blocktype:"0", // 控制区块链动画
      sshow:false, // 控制区块链动画
      singtype:"0", // 控制区块链动画
      ECDSInfo:{
        name:'',
        bank_card_number:'',
        bank_code:'',
      },
      financingmoney: {         // 挂牌自动计算数据
        financing_amount: '', // 融资金额
        id:'',
        amount: '',           // 票面金额
        lossmoney: '',        // 帖限额
        lossrate: '',         // 贴现率
        left_day:'', 
        bill_number:'',       
      },
      tradePassword:'',
      fkData:{},
      uploadFile: {
          uploadUrl: '',//上传的文件路径
          filetype: ""
      },
      fillname:'',//上传文件的字段名
      uploaddata:{
          id:'',//票据名称
      },
      fileList:[],
      // headers:{},
    }
  },
  computed: {
    headers: function() {
      return {
        Authorization: "Bearer " + sessionStorage.token,
        Accept: "application/json; charset=utf-8"
      }
    }
  },
  mounted: function() {
    this.$nextTick(() => {

    })
  },
  components: {
    block,
  },
  methods: {
    changePage(val){
      this.rebilldata.page=val;
      this.$emit('reFlashBill',this.rebilldata);
    },
    tradechange(column){
      let sortType=column.order=='ascending'? '' : '-' ;
       if(column.prop=='left_day'){
        column.prop='acceptance_at';
       }
        var sort='';
        if(column.order=='descending'){
          sort=sortType+column.prop;
        }else{
          sort=column.prop;
        }
        this.rebilldata.sort=sort;
        this.$emit('reFlashBill',this.rebilldata);
    },
    updatebill2(billid){
      this.$router.push({
        path: '/user/billRecord?id='+billid,
      });
    },
    async checkECDS(enID){
      this.ECDSDialog=true;
      const data=await api.get(this, api.config.getEcdsInfo,{'enterprise_id':enID});
      if(data.code==200){
        this.ECDSInfo=data;
      }
    },
    handleSuccess:function(response){
      console.log(response);
      if(response.code==200){
        this.$notify({
          title: '成功',
          message: '上传成功',
          type: 'success'
        });
        this.dialogAgreement = false
      }else{
        this.$notify({
          title: '成功',
          message: '上传失败,请重新上传',
          type: 'warning'
        });
        this.fileList=[];
      }
    },
    uploadAgreement(id,proess,invoited){
      this.dialogAgreement=true;
      this.uploaddata.id=id;
      if(proess==this.uid){
        this.fillname='possessor_contract_file';
        this.uploadFile.uploadUrl=api.config.api+api.config.uploadpcf;
        console.log(this.uploadFile.uploadUrl);
      }else if(invoited==this.uid){
        this.fillname='investor_contract_file';
        this.uploadFile.uploadUrl=api.config.api+api.config.uploadIcf;
      }
    },
    // 挂牌弹框
    showGpDialog(index, row) {
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
        this.gpDialog = true;
        this.financingmoney.financing_amount='';
        this.financingmoney.lossmoney='';
        this.financingmoney.lossrate='';
        this.financingmoney.amount = this.billData[index].face_amount;
        var tobig=this.financingmoney.amount.replace(/,/g,"");
        this.bigFinancingmoneyAmount=smalltobig(tobig);
        this.financingmoney.id = this.billData[index].id;
        this.financingmoney.left_day=this.billData[index].left_day;
        this.financingmoney.bill_number=this.billData[index].bill_number;
      }
    },
    // 挂牌
    async createGp(){
      this.financingmoney.financing_amount=this.financingmoney.financing_amount.replace(/,/g,"");
      var amount_fian=this.financingmoney.financing_amount*100;
      console.log(api.userkey());
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
          // this.canclick=0;
          this.$emit('reFlashBill',this.rebilldata);
        }, 3500);
        this.gpDialog = false;
        // this.$emit('reFlashBill');
      } else{
        this.$notify({
          title:"提示",
          message:data.message,
          type:'warning'
        });
      }
    },
    // 撤销挂牌弹框
    showCxgpDialog(index, row){
      this.cxgpDialog = true;
      this.financingmoney.id = this.billData[index].id;
      this.financingmoney.bill_number=this.billData[index].bill_number;
    },
    // 撤销挂牌
    async createCxgp(){
      console.log(this.financingmoney.bill_number);
      let result=api.billNoteSigns().signOperateNote(api.userkey(),this.financingmoney.bill_number,'cancelSell');
      const data = await api.post(this, api.config.possessorRevoke, {
        id: this.financingmoney.id,
        signature: result.signdata,
        instruction_id: result.sid
      });
      if(data.code == 200){
        this.iscontinuity=false;
        this.show=true;
        this.single=true;
        this.singletype="3"
        this.cxgpDialog = false;
        setTimeout(()=>{
          this.show = false;
          this.single=false;
          this.singletype="0"
          this.$notify({
            title: '提示',
            message: '撤销成功，已录入区块链',
            type: 'success'
          });
         this.$emit('reFlashBill',this.rebilldata);
        }, 3500);
      } else{
        this.$notify({
          title:"提示",
          message:data.message,
          type:'warning'
        });
      }
    },
    // 撤销付款
    showCxgp2Dialog(index, row){
      this.cxgp2Dialog = true;
      this.financingmoney.id = this.billData[index].id;
      this.financingmoney.bill_number=this.billData[index].bill_number;
    },
    // 撤销付款
    async createCxgp2(){
      console.log(this.financingmoney.bill_number);
      let result=api.billNoteSigns().signOperateNote(api.userkey(),this.financingmoney.bill_number,'cancelBuyByBuyer');
      const data = await api.post(this, api.config.investorRevoke, {
        id: this.financingmoney.id,
        signature: result.signdata,
        instruction_id: result.sid
      });
      if(data.code == 200){
        this.iscontinuity=false;
        this.show=true;
        this.single=true;
        this.singletype="3"
        setTimeout(()=>{
          this.show = false;
          this.single=false;
          this.singletype="0"
          this.$notify({
            title: '提示',
            message: '撤销成功，已录入区块链',
            type: 'success'
          });
         this.$emit('reFlashBill',this.rebilldata);
        }, 3500);
        this.cxgp2Dialog = false;
        // this.$emit('reFlashBill',this.rebilldata);
      } else{
        this.$notify({
          title:"提示",
          message:data.message,
          type:'warning'
        });
      }
    },
    // 取消撤单
    showQxcdDialog(index, row){
      this.qxcdDialog = true;
      this.financingmoney.id = this.billData[index].id;
      this.financingmoney.bill_number=this.billData[index].bill_number;
    },
    // 取消撤单
    async createQxcd(){
      let result=api.billNoteSigns().signOperateNote(api.userkey(),this.financingmoney.bill_number,'cancelCancelByBuyer');
      const data = await api.post(this, api.config.investorCancelRevoke, {
        id: this.financingmoney.id,
        signature: result.signdata,
        instruction_id: result.sid
      });
      if(data.code == 200){
        this.$notify({
          title:"提示",
          message:"操作成功",
          type:'success'
        });
        this.qxcdDialog = false;
        this.$emit('reFlashBill',this.rebilldata);
      } else{
        this.$notify({
          title:"提示",
          message:data.message,
          type:'warning'
        });
      }
    },
     // 投资人同意撤单
    showTycdDialog(index, row){
      this.tycdDialog = true;
      this.skTradePassword='';
      this.financingmoney.id = this.billData[index].id;
      this.financingmoney.bill_number = this.billData[index].bill_number
    },
    // 投资人同意撤单
    async createTycd(){
      let result=api.billNoteSigns().signOperateNote(api.userkey(),this.financingmoney.bill_number,'cancelBuyBySeller');
      const data = await api.post(this, api.config.possessorConfirmRevoke, {
        id: this.financingmoney.id,
        signature: result.signdata,
        instruction_id: result.sid,
        trade_password:this.skTradePassword,
      });
      if(data.code == 200){
        this.iscontinuity=false;
        this.show=true;
        this.single=true;
        this.singletype="3"
        setTimeout(()=>{
          this.show = false;
          this.single=false;
          this.singletype="0"
          this.$notify({
            title: '提示',
            message: '撤销成功，已录入区块链',
            type: 'success'
          });
         this.$emit('reFlashBill',this.rebilldata);
        }, 3500);
        this.tycdDialog = false;
        // this.$emit('reFlashBill',this.rebilldata);
      } else{
        this.$notify({
          title:"提示",
          message:data.message,
          type:'warning'
        });
      }
    },
     // 投资人拒绝撤单
    showJjcdDialog(index, row){
      this.jjcdDialog = true;
      this.financingmoney.id = this.billData[index].id;
    },
    // 投资人拒绝撤单
    async createJjcd(){
      const data = await api.post(this, api.config.possessorRefuseRevoke, {
        id: this.financingmoney.id
      });
      if(data.code == 200){
        this.$notify({
          title:"提示",
          message:"操作成功",
          type:'success'
        });
        this.jjcdDialog = false;
        this.$emit('reFlashBill',this.rebilldata);
      } else{
        this.$notify({
          title:"提示",
          message:data.message,
          type:'warning'
        });
      }
    },
    // ecds转让
    showEcdsDialog(index, row){
      this.ecdsDialog = true;
      this.financingmoney.id = this.billData[index].id;
      this.financingmoney.bill_number=this.billData[index].bill_number;
    },
    // ecds转让
    async createEcds(){
      let result=api.billNoteSigns().signOperateNote(api.userkey(),this.financingmoney.bill_number,'confirmSend')
      const data = await api.post(this, api.config.possessorAssignment, {
        id: this.financingmoney.id,
        signature: result.signdata,
        instruction_id: result.sid
      });
      if(data.code == 200){
        this.show=true;
        this.iscontinuity=true;
        this.blocktype="3";
        setTimeout(()=>{
          this.show=false;
          this.blocktype="0";
          this.$notify({
            title:"提示",
            message:"操作成功",
            type:'success'
          });
          this.$emit('reFlashBill',this.rebilldata);
        }, 3500);
        this.ecdsDialog = false;
      } else{
        this.$notify({
          title:"提示",
          message:data.message,
          type:'warning'
        });
      }
    },
    // 确认收票
    showQrspDialog(index, row){
      this.qrspDialog = true;
      this.financingmoney.id = this.billData[index].id;
      this.financingmoney.bill_number=this.billData[index].bill_number;
    },
    // 确认收票
    async createQrsp(){
      let result=api.billNoteSigns().signOperateNote(api.userkey(),this.financingmoney.bill_number,'confirmReceive')
      const data = await api.post(this, api.config.tradeInvestorConfirm, {
        id: this.financingmoney.id,
        signature: result.signdata,
        instruction_id: result.sid
      });
      if(data.code == 200){
        this.show=true;
        this.iscontinuity=true;
        this.blocktype="4";
        setTimeout(()=>{
          this.show=false;
          this.blocktype="0";
          this.$notify({
            title:"提示",
            message:"操作成功",
            type:'success'
          });
          this.$emit('reFlashBill',this.rebilldata);
        }, 3500);
        this.qrspDialog = false;
        
      } else{
        this.$notify({
          title:"提示",
          message:data.message,
          type:'warning'
        });
      }
    },
    // 付款弹框
    showQfkDialog(index, row) {
      this.fkDialog = true;
      this.fkData.face_amount=this.billData[index]['face_amount']+'(元)';
      this.fkData.financing_amount=this.billData[index]['financing_amount']+'(元)';
      this.fkData.left_day=this.billData[index]['left_day']+'(天)';
      this.fkData.wechselspesen=this.billData[index]['wechselspesen']+'(元)';
      this.fkData.annualized_rate=this.billData[index]['annualized_rate']+'%';
      // var bsmount=this.textnum(this.billData[index]['financing_amount']);
      // this.bigFinancing_amount=smalltobig(bsmount);
      this.fkData.acceptance_at=this.billData[index]['acceptance_at'];
      this.fkData.acceptor=this.billData[index]['acceptor'];
      this.fkData.bill_number=this.billData[index]['bill_number'];
      this.fkData.id=this.billData[index]['id'];
    },
    // 付款
    async createQfk() {
      const data = await api.post(this, api.config.propertyPayment, {
        id: this.fkData.id,
        trade_password: this.tradePassword
      });
      if(data.code == 200){
        this.$notify({
          title:"提示",
          message:"操作成功",
          type:'success'
        });
        this.fkDialog = false;
        this.$emit('reFlashBill',this.rebilldata);
      } else{
        this.$notify({
          title:"提示",
          message:data.message,
          type:'warning'
        });
      }
    },
    showtkDialog(index, row){
      this.skTradePassword='',
      this.tkDialog = true;
      this.financingmoney.id = this.billData[index].id;
    },
    async createtk(){
      const data = await api.post(this, api.config.refund, {
        id: this.financingmoney.id,
        trade_password: this.skTradePassword
      });
      if(data.code == 200){
        this.$notify({
          title:"提示",
          message:"操作成功",
          type:'success'
        });
        this.tkDialog = false;
        this.$emit('reFlashBill',this.rebilldata);
      } else{
        this.$notify({
          title:"提示",
          message:data.message,
          type:'warning'
        });
      }
    },
    // 收款弹框
    showSqDialog(index, row) {
      this.skTradePassword='',
      this.sqDialog = true;
      this.financingmoney.id = this.billData[index].id;
    },
    // 收款
    async createSq() {
      const data = await api.post(this, api.config.propertyCollection, {
        id: this.financingmoney.id,
        trade_password: this.skTradePassword
      });
      if(data.code == 200){
        this.$notify({
          title:"提示",
          message:"操作成功",
          type:'success'
        });
        this.sqDialog = false;
        this.$emit('reFlashBill',this.rebilldata);
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
    textnum:function(textnum){
      return textnum.replace(/[^\d.]/g,"").replace(/\.{2,}/g,".").replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');
    },
  }
}
</script>
<style>
.tabs-content{
  .bigs{
    text-align: right;
   color: #2fa4e7;
  }
  .icon-shangchuanxieyi1,.icon-xiazaixieyi1{
    font-size: 24px!important;
  }
  .icon-xiazaixieyi1{
    color:#18a918; 
  }
  .examine__submit{
    width: 85px;
  }
}
.btnRed{
  background:#f25537 !important;
  border-color: #f25537 !important;
}
.icon-xiazaixieyi1,.icon-shangchuanxieyi1{
  color: #2fa4e7;
}
.uploadAgree{
    .el-dragger{
      width: 100%!important;
    }
    .el-upload{
      width: 100%!important;
    }
    .el-dialog__header{
      text-align: center;
    }
    .el-dialog__title{
       color: #2fa4e7;
    }
    .imgbutton{
      width: 100%;
    }
  }
.tradedialogss{
   .el-form{
    width: 300px;
    margin-left: 180px;
  }
  .info{
    text-align: left;
  }
  .el-dialog__header{
    text-align: center;
  }
  .el-dialog__title{
     color: #2fa4e7;
  }
}
.tradedialog {
  .inputtpwd{
    width: 300px;
  }
  .querentk{
    display: block;
    width: 100%;
    line-height: 35px;
    text-align: center;
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
