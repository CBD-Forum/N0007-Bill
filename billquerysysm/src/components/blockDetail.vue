<template>
  <div class='block-box'>
    <el-breadcrumb separator="/" style="margin-bottom:20px;">
      <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>区块</el-breadcrumb-item>
    </el-breadcrumb>
    <div class='block-msg'>
      <h1>
        <span>区块高度</span>
        <span class='block-height'>{{height}}</span>
      </h1>
      <p>
        <span class='word1'>区块哈希</span>
        <span>{{blockData.blockhash}}</span>
      </p>
      <p>
        <span class='word1'>发现时间</span>
        <span>{{blockData.time}}</span>
      </p>
    </div>
    <div class='block-business'>
      <h2>
        <span>交易</span>
        <span class='tip1'>此区块下的交易</span>
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
          <tr class='ttbody' v-for="e in tranList">
            <td class='link1'>
              <router-link :to="{ name: 'trade', params: { 'tid': e.instructionid }}">{{e.iid}}</router-link>
            </td>
            <td>{{e.height}}</td>
            <td>{{e.actionid}}</td>
             <td>
            <p>{{e.from}}</p>
          </td>
          <!-- <td class='arrow'></td> -->
          <td>
            <p>{{e.where}}</p>
          </td>
         
          <td>{{e.addtime}}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="block" v-show="blockShow">
    <el-pagination
      :current-page="page.page"
      :page-size="page.pagesize"
      layout="total, prev, pager, next"
      :total="page.total"
       @current-change="handleCurrentChange"></el-pagination>
  </div>
</div>
</div>
</template>
<script>
import {postblockDetail} from '../assets/js/api';
export default{
  data() {
    return {
      blockData:{
        blockhash:'--',
        time:'--'
      },
      tranList:[],
      height:this.$route.params.bid,
      blockShow:false,
      page:{
        page:1,
        pagesize:10,
        total:0
      },
    }
  },
  watch: {
    '$route' (to, from) {
      this.getblockDetail();
      this.height = this.$route.params.bid;
    }
  },
  mounted: function() {
    this.$nextTick(function() {
      let bid = this.$route.params.bid;
      this.getblockDetail();
    })
  },
  methods:{
    handleCurrentChange:function(val){
      this.page.page = val;
      this.getblockDetail();
    },
    goaddress:function(){
      this.$router.push({path:'/addressDetail'});
    },
    getblockDetail: function(){
      postblockDetail({height : this.$route.params.bid}).then((data) => {
        data.count > 10 ? this.blockShow = true : this.blockShow = false;
        this.page.total = Number(data.count);
        this.blockData = data.block;
        this.tranList = data.tran;
      });
    }
  }
}
</script>

<style>
.block-box{
    .block-msg{
    padding:0 20px 20px;
    background-color: #fff; 
  }
  .block-msg h1{
    font-size: 24px;
    height: 60px;
    line-height: 60px;      
  }
  .block-height{
    margin-left: 20px;
  }
  .word1{
    width: 96px;
    display: inline-block;
    text-align: right;
  }
  .block-msg p{
    margin-top: 10px;
    font-size: 16px;
  }
  .block-msg p span:nth-child(1){
    color: #808080;
  }
  .block-msg p span:nth-child(2){
    color: #333;
    margin-left: 20px;  
  }
  .block-business{
    margin-top: 20px;
    background-color: #fff;
    padding:0 20px 20px;
  }
  .block-business h2{
    font-size: 24px;
    height: 60px;
    line-height: 60px;
  }
  .tip1{
    font-size: 16px;
    color: #808080;
    margin-left: 20px;
  }

  .link1{
    color: #39aae7;
    cursor: pointer;
  }
  .arrow{
    color: #39aae7;
  }
}

.block-table {
        clear: both;
        width: 100%;
        overflow-x: auto;
        table {
            width: 100%;
            thead {
                width: 100%;
                .ttop {
                    width: 100%;
                    th {
                        height: 40px;
                        text-align: center;
                        line-height: 40px;
                        color:#4392ac;
                        font-size: 14px;
                        background: #d1effa;
                        
                    }
                    .canclick{
                        cursor: pointer;
                    }
                    th:nth-child(3) {
                    }
                }
            }
            tbody {
                width: 100%;
                .ttbody {
                    width: 100%;
                    &:hover {
                        background: #f5f5f5;
                    }
                    ;
                    td {
                        padding: 0!important;
                        height: 55px;
                        text-align: center;
                        font-size: 14px;
                        
                    }
                }
            }
        }
    }
</style>