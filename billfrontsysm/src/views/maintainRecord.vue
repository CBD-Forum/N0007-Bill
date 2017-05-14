<template>
  <div class="trade">
    <div class="clearfix">
      <div class="title">
        <div class="clearfix">
          <h2>维护记录</h2>
          <!-- <a href="https://114.55.128.142:9082/alidata/www/haipiaohui_finance/api/v1/web/uploads/temp/2017022116/%E4%BA%A4%E6%98%93%E7%BA%AA%E5%BD%95.xlsx"></a> -->
          <el-button class="export__button export" size="small" @click="exportExcel">导出Excel文件</el-button>
          <div class="search-warp">
            <el-button type="primary" class="search-warp__all" :class='{btnActive:btnActive == 1}' size="small" @click="changeBtn(1)">全部</el-button>
            <el-button type="primary" class="search-warp__pri" :class='{btnActive:btnActive == 2}' size="small" @click="changeBtn(2)">已维护</el-button>
            <el-button type="primary" class="search-warp__pri" :class='{btnActive:btnActive == 3}' size="small" @click="changeBtn(3)">已背书</el-button>
            <el-select class="search-warp__type" v-model="searchDate.bill_type" placeholder="请选择">
              <el-option v-for="item in options" :label="item.label" :value="item.value">
              </el-option>
            </el-select>
            <el-date-picker class="search-warp__date" v-model="searchDate.startTime" type="date" :editable="false" placeholder="到期日-从" @change="changeStartTime" :picker-options="pickerOptions1">
            </el-date-picker>
            <el-date-picker class="search-warp__date" v-model="searchDate.endTime" type="date" :editable="false" placeholder="到期日-到" @change="changeEndTime" :picker-options="pickerOptions1">
            </el-date-picker>
            <el-button class="search-warp__submit" @click="serachSubmit" size="small">搜索</el-button>
          </div>
        </div>
      </div>
      <div>
        <el-table :data="tradeRecord" style="width: 100%;margin-top:10px;" stripe @sort-change="tradeRecordSort">
          <el-table-column prop="id" label="序号" width="80">
          </el-table-column>
          <el-table-column prop="user_name" label="转让申请人" min-width="180">
          </el-table-column>
          <el-table-column prop="bill_number" label="票据编号" min-width="180">
          </el-table-column>
          <el-table-column prop="drawer" label="出票人全称" min-width="150">
          </el-table-column>
          <el-table-column prop="taker" label="收票人全称" min-width="150">
          </el-table-column>
          <el-table-column prop="contract_number" label="转让合同编号" min-width="180">
          </el-table-column>
          <el-table-column prop="acceptor" label="承兑人全称" min-width="150">
          </el-table-column>
          <el-table-column prop="bill_type" label="汇票类型" sortable min-width="120">
          </el-table-column>
          <el-table-column prop="face_amount" label="票面金额（元）" sortable min-width="180">
          </el-table-column>
          <el-table-column prop="issue_at" label="出票日期" sortable min-width="120">
          </el-table-column>
          <el-table-column prop="acceptance_at" label="到期日期" sortable min-width="120">
          </el-table-column>
          <el-table-column prop="transfer_at" label="转让日期" sortable min-width="120">
          </el-table-column>
          <el-table-column prop="bearing_days" label="计息天数" sortable min-width="120">
          </el-table-column>
          <el-table-column v-if="btnActive != 3" prop="discount_applicant" label="贴现人" min-width="150">
          </el-table-column>
          <el-table-column v-if="btnActive != 2" prop="endorsor" label="被背书人" min-width="150">
          </el-table-column>          
          <el-table-column prop="annualized_rate" label="年贴现率%" sortable min-width="150">
          </el-table-column>
          <el-table-column prop="transfer_amount" label="贴现金额（元）" sortable min-width="160">
          </el-table-column>
          <el-table-column prop="" label="附件" min-width="120">
            <template scope="scope">
              <i class="iconfont icon-wenjian" @click="checkImg(scope.$index,scope.row)"></i>
            </template>
          </el-table-column>
      <!--     <el-table-column label="操作" width="80" >
            <template scope="scope"> -->
              <!-- <el-button v-if="type == 1" type="primary" size="small" class="examine__submit" @click="whDialogShow(scope.row)">修改</el-button> -->
              <!-- <span v-else>无</span> -->
          </el-table-column>
        </el-table>
        <el-pagination class="table__page" layout="prev, pager, next" :total="total" :page-size="15" @current-change="changePage">
        </el-pagination>
        
      </div>
      <show-img v-show="showImg" :swiperData="swiperSlides" v-on:close="closeShowImg"></show-img>
      <whDialog :whopen="whShow2" :whDate="whParentData2" :isAgain="true" v-on:close="closeWhDialog"></whDialog>
    </div>
  </div>
</template>
<script>
import api from "../api"
import showImg from 'src/components/showImg'
import whDialog from 'components/wh.vue'
import {temptime} from '../../src/assets/js/common-js'
export default {
  data() {
      return {
        total:0,
        whShow: false,
        whShow2: false,
        whParentData: [],
        whParentData2: [],
        showImg: false,
        swiperSlides: [],
        tradeRecord: '',
        type: sessionStorage.type,
        options: [{
          value: '',
          label: '请选择'
        }, {
          value: '1',
          label: '银行承兑'
        }, {
          value: '2',
          label: '商业承兑'
        }],
        value: '',
        value1: '',
        btnActive: 1,
        tradeRecord: [],
        searchDate: {
          startTime: "",
          endTime: "",
          bill_type: "",
          type: [],
          page: 1,
          pageSize:15,
          sort:'',
        },
        pickerOptions0: {

        },
        value2: '',
        pickerOptions1: {

        },
      }
    },
    mounted: function() {
      this.$nextTick(() => {
        api.auth(this);
        this.getTradeRecord();
      })
    },
    components: {
      showImg,
      whDialog
    },
    methods: {
      tradeRecordSort(column){
        let sortType=column.order=='ascending'? '' : '-' ;
        var sort='';
        if(column.order=='descending'){
          sort=sortType+column.prop;
        }else{
          sort=column.prop;
        }
        this.searchDate.sort=sort;
        this.getTradeRecord();
      },
      changePage(val) {
        this.searchDate.page = val;
        this.getTradeRecord();
      },
      async whDialogShow(data) {
        this.whParentData2 = data;
        this.whShow2 = true;
      },
      formatter(row, column) {
        return api.toThousands(row.face_amount);
      },
      formatter2(row, column) {
        return row.transfer_amount;
        // return api.toThousands(row.transfer_amount);
      },
      closeWhDialog() {
        this.getTradeRecord();
        this.whShow2 = false;
      },
      async getTradeRecord() {
        if(this.searchDate.bill_type == '请选择'){
          this.searchDate.bill_type = '';
        }
        const data = await api.get(this, api.config.maintainRecord, this.searchDate);
        if (data.code == 200) {
          this.total = data.count;
          for (let x of data.data){
            x.issue_at = x.issue_at == null ? '--' : x.issue_at;
            x.endorsor = x.endorsor == null ?　'--' : x.endorsor;
            x.discount_applicant = x.discount_applicant == null ? '--' : x.discount_applicant;
            x.face_amount = Number(x.face_amount.replace(/,/g,''));
            if(x.transfer_amount != null){
              x.transfer_amount = Number(x.transfer_amount.replace(/,/g,''));
            }
            if(x.annualized_rate != null){
              x.annualized_rate = Number(x.annualized_rate.replace(/,/g,''));
            }
            if(x.bearing_days != null){
              x.bearing_days = Number(x.bearing_days.replace(/,/g,''));
            }
            
          }
          // console.log(data)
         
          this.tradeRecord = data.data;
        } else {
          console.log(data.message);
        }
      },
      async exportExcel() {
        const data = await api.get(this, api.config.exportExcel, this.searchDate);
        if (data.code == 200) {
          location.href =data.url;
          this.$notify({
            title: '提示',
            message: '导出成功！',
            type: 'success'
          });
        } else {
          this.$notify({
            title: '提示',
            message: data.message,
            type: 'warning'
          });
        }
      },
      changeBtn(index) {
        this.btnActive = index;
        this.serachSubmit();
      },
      checkImg(index, rows) {
        console.log(rows);
        var swiperData = [{
          title: "票据正面",
          src: rows.bill_front_url
        }, {
          title: "票据背面",
          src: rows.bill_back_url
        }];
        if (rows.contract_file_url != null) {
          swiperData.push({
            title: "协议信息",
            src: rows.contract_file_url
          })
        }
        this.swiperSlides = swiperData;
        this.showImg = true;
      },
      closeShowImg() {
        this.showImg = false;
      },
      changeStartTime(events) {
        this.searchDate.startTime = events;
      },
      changeEndTime(events) {
        this.searchDate.endTime = events;
      },
      serachSubmit() {
        if (new Date(this.searchDate.startTime).getTime() > new Date(this.searchDate.endTime).getTime()) {
          this.$notify.error({
            title: '错误',
            message: '开始时间必须小于结束时间'
          });
          return false;
        } else {
          this.searchDate.startTime=temptime(this.searchDate.startTime);
          this.searchDate.endTime=temptime(this.searchDate.endTime);
          if (this.btnActive == 1) {
            this.searchDate.type = [];
          } else if (this.btnActive == 2) {
            this.searchDate.type[0] = 1;
            this.searchDate.type[1] = 2;
          } else {
            this.searchDate.type[0] = 3;
            this.searchDate.type[1] = 4;
          }
          this.getTradeRecord();
        }
      },
    }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.trade {
  &>div {
    bg: #fff;
    bdt: #2fa4e7 3px solid;
    p: 0px 20px;
    mb: 10px;
  }
  &__submit {
    width: 60px;
  }
  .table__page {
    margin: 14px auto;
    text-align: center;
  }
  .icon-remen-shenhe{
    color: rgb(161,161,180);
  }
  .export {
    color: #2fa4e7;
    border-color: #2fa4e7;
    margin-left: 30px;
  }
  button {
    height: 26px;
  }
  .btnActive {
    background: #207fb7;
    height: 26px;
  }
  .search-warp__all,
  .search-warp__pri {
    width:90px;
    margin-left: 0px;
    vertical-align: top
  }
  .search-warp__type {
    margin-left: 15px;
  }
}
</style>
