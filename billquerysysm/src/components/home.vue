<template>
  <div class="index">
    <div class="top-content clearfix">
      <div class="tbox">
        <span>累计成交额</span>
        <p>{{ topInfo.total_deal_amount }}</p>
      </div>
      <div class="tbox">
        <span>累计票据</span>
        <p>{{ topInfo.total_bill_amount }}</p>
      </div>
      <div class="tbox">
        <span>累计认证企业</span>
        <p>{{ topInfo.total_company_amount }}</p>
      </div>
    </div>
    <div class="table-box">
      <h2>
        <span>区块</span>
        <span class='tip1'>区块下的交易</span>
      </h2>
      <table class="table1">
        <thead>
          <tr>
            <th>区块高度</th>
            <th>区块哈希</th>
            <th>交易笔数</th>
            <th>生成时间</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="e in tradeBlock">
            <td>
              <router-link :to="{ name: 'block', params: { 'bid': e.block }}">{{e.block}}</router-link>
            </td>
            <td>{{e.hash}}</td>
            <td>{{e.tx_num}}</td>
            <td>{{e.time}}</td>
          </tr>
        </tbody>
      </table>
      <div class="block">
        <el-pagination :current-page="blockPage.page" :page-size="blockPage.pagesize" layout="total, prev, pager, next" :total="blockPage.total" @current-change="handleBlockChange"></el-pagination>
      </div>
    </div>
    <div class="table-box">
      <h2>
        <span>交易</span>
        <span class='tip1'>最新生成的交易</span>
      </h2>
      <div class='block-table'>
        <table class=''>
          <thead>
            <tr class='ttop'>
              <th>交易哈希</th>
              <th>所属区块</th>
              <th>交易类型</th>
              <th>来源</th>
              <th>去至</th>
              <th>时间</th>
          </tr>
        </thead>
        <tbody>
          <tr class='ttbody' v-for="e in latestBlock">
            <td class='link1'>
              <router-link :to="{ name: 'trade', params: { 'tid': e.instructionid }}">{{e.iid}}</router-link>
            </td>
            <td>{{e.height}}</td>
            <td>{{e.actionid}}</td>
            <td class='link1'>
              <p>{{e.from}}</p>
            </td>
          <!-- <td class='arrow'></td> -->
          <td class='link1'>
            <p>{{e.where}}</p>
          </td>
          <td>{{e.addtime}}</td>
        </tr>
      </tbody>
    </table>
    <div class="block">
      <el-pagination :current-page="tranPage.page" :page-size="tranPage.pagesize" layout="total, prev, pager, next" :total="tranPage.total" @current-change="handleTranChange"></el-pagination>
    </div>
  </div>
</div>
</div>
</template>
<script>
import {
  fetchTradeBlock,
  fetchLatestTrade,
  fetchTopInfo
} from '../assets/js/api.js'
// import pdfMake from 'pdfmake'
export default {
  name: 'index',
  data() {
    return {
      tradeBlock: [],
      latestBlock: [],
      topInfo: {
        total_bill_amount: '--',
        total_cert_company: '--',
        total_deal_amount: '--'
      },
      blockPage: {
        page: 1,
        pagesize: 10,
        total: 0
      },
      tranPage: {
        page: 1,
        pagesize: 10,
        total: 0
      },
    }
  },
  mounted: function() {
    this.$nextTick(function() {
      // console.log(pdfMake);
      this.getBlockTrade();
      this.getLatestTrade();
      this.getTopInfo();
    });
  },
  methods: {
    handleBlockChange(val) {
      this.blockPage.page = val;
      this.getBlockTrade();
    },
    handleTranChange(val) {
      this.tranPage.page = val;
      this.getLatestTrade();
    },
    goDetail() {
      this.$router.push({
        path: '/tradeHash'
      });
    },
    getBlockTrade() {
      fetchTradeBlock({
        page: this.blockPage.page,
        pagesize: this.blockPage.pagesize
      }).then((data) => {
        this.blockPage.total = Number(data.count);
        this.tradeBlock = data.list;
      });
    },
    getLatestTrade() {
      fetchLatestTrade({
        page: this.tranPage.page,
        pagesize: this.tranPage.pagesize
      }).then((data) => {
        console.log(data);
        this.tranPage.total = Number(data.count);
        this.latestBlock = data.list;
      });
    },
    getTopInfo() {
      fetchTopInfo().then((data) => {
        // console.log(data);
        this.topInfo = data.data;
        this.topInfo.total_deal_amount = this.fmoney(this.topInfo.total_deal_amount);
        console.log(this.topInfo)
      });
    },
  fmoney(ATR){
    let str = String(ATR);
    let count = 0, newStr = "";
    if(str == ""){
      return ""
    }
    if(str.indexOf(".")==-1){
        str = String(parseFloat(str.replace(/[^\d\.-]/g, "")));
        for(var i=str.length-1;i>=0;i--){
            if(count % 3 == 0 && count != 0){
                newStr = str.charAt(i) + "," + newStr;
            }else{
                newStr = str.charAt(i) + newStr;
            }
            count++;
        }
        str = newStr;;
    }
    else{
        str = String(parseFloat(str.replace(/[^\d\.-]/g, "")).toFixed(2));
        if(str.indexOf(".")-1 <0){
            newStr = ATR
        }else{
            for(var i = str.indexOf(".")-1;i>=0;i--){
                if(count % 3 == 0 && count != 0){
                    newStr = str.charAt(i) + "," + newStr;
                }else{
                    newStr = str.charAt(i) + newStr; //逐个字符相接起来
                }
                count++;
            }
        }
        let sint = (str + "00").substr((str + "00").indexOf("."),3);
        if(sint == "0"){
            str = newStr;
        }else{
            let strl = str.substr(str.indexOf("."),3);
            if(strl.length>3){
                str = newStr + (str + "00").substr((str + "00").indexOf("."),3);
            }else{
                str = newStr+strl;
            }
        }
     }
    return str;
}
  }

}
</script>
<style scoped>
.index {}

.table1 th:nth-child(2),
.table1 td:nth-child(2) {
  width: 50%;
  /*text-align: left;*/
}

.table1 th:nth-child(3),
.table1 td:nth-child(3) {
  width: 10%;
}

.table1 td:nth-child(2) {
  /*text-align: left;*/
}

.
/*table1 th:nth-child(2){
    padding-left: 200px;
}*/

.link1 {
  color: #39aae7;
  /*cursor: pointer;*/
}

.index .tbox {
  height: 140px;
  display: inline-block;
  width: 387px;
  margin-right: 19.5px;
  text-align: center;
  float: left;
  color: #fff;
}

.index .tbox span {
  font-size: 20px;
  line-height: 65px;
}

.index .tbox p {
  font-size: 40px;
}

.index .tbox:nth-child(1) {
  background: #28b1ae;
}

.index .tbox:nth-child(2) {
  background: #3891ed;
}

.index .tbox:nth-child(3) {
  background: #3258be;
  margin-right: 0;
}

.index .table-box {
  padding: 0 20px;
  background: #fff;
  margin-top: 20px;
  padding-bottom: 10px;
  /*font-size: 20px;*/
  /*line-height:40px;*/
}

.index h2 {
  font-size: 24px;
  height: 60px;
  line-height: 60px;
  /*padding: 20px 0;*/
}

.tip1 {
  font-size: 16px;
  color: #808080;
  margin-left: 20px;
}

.index table {
  width: 100%;
}

.index table tbody tr {
  /*cursor: pointer;*/
}

.index table tbody tr:hover {
  background: #f5f5f5;
}

.index table thead {
  background: #d3eef9;
  color: #4392ac;
}

.index th,
.index td {
  height: 40px;
  text-align: center;
  font-size: 14px;
}

.index .blue {
  color: #2ba7e4;
}

.block-table {
  clear: both;
  width: 100%;
  overflow-x: auto;
  table {
    width: 100%;
    thead {
      width: 100%;
      /*border-bottom: 1px solid #eee;*/
      .ttop {
        width: 100%;
        th {
          /* min-width: 80px;*/
          height: 40px;
          text-align: center;
          line-height: 40px;
          color: #4392ac;
          font-size: 14px;
          background: #d1effa;
        }
        .canclick {
          /*cursor: pointer;*/
        }
        th:nth-child(3) {
          /*min-width: 120px;*/
        }
      }
    }
    tbody {
      width: 100%;
      .ttbody {
        width: 100%;
        /*border-bottom: 1px solid #eee;*/
        &:hover {
          background: #f5f5f5;
        }
        td {
          padding: 0!important;
          /*min-width: 80px;*/
          height: 55px;
          text-align: center;
          font-size: 14px;
        }
        td:nth-child(3) {
          /* min-width: 120px;*/
        }
      }
    }
  }
}
</style>