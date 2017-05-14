<template>
  <div class="fcpAccount">
    <div class="pub__main height">
      <div class="pub__title">
        账户开立
      </div>
      <el-col :span="24">
        <div class="pub__icon iconfont icon-tuijiankaihu"></div>
      </el-col>
      <el-form label-width="100px" class="clearfix" style="width:550px;margin:0 auto;">
        <el-col :span="24">
            <el-form-item label="企业名称">
                <el-input v-model="body.enterprise_name" placeholder="请输入企业名称"></el-input>
            </el-form-item>
            <el-form-item label="企业账号">
                <el-input v-model="body.user_name" placeholder="请输入企业账号"></el-input>
            </el-form-item>
        </el-col>
        <el-col :span="24" class="pub_btnBox">
            <el-button type="primary" @click="addMember" :loading="btnActive">确认提交</el-button>
            <div class="info" v-show="show">
              <span>企业账号：{{info.email}}</span><span>企业初始密码：{{info.password}}</span>
            </div>
        </el-col>
    </el-form>
    </div>
    <div class="clearfix tablemain">
      <div class="title">
        <div class="clearfix">
          <h2>开户历史</h2>
          <div class="search-warp">
            <el-input class="search-warp__title" v-model="param.query"  placeholder="编号/企业名称"> </el-input>
            <el-date-picker
              class="search-warp__date"
              v-model="param.startTime"
              type="date"
              placeholder="到期日-从"
              :picker-options="pickerOptions0"  @change="changeStartTime">
            </el-date-picker>
            <el-date-picker
              class="search-warp__date"
              v-model="param.endTime"
              type="date"
              placeholder="到期日-到"
              :picker-options="pickerOptions0"  @change="changeEndTime">
            </el-date-picker>
            <el-button class="search-warp__submit" size="small" @click="search">搜索</el-button>
          </div>
        </div>
      </div>
      <div>
        <el-table
          :data="historyData"
          style="width: 100%;margin-top:10px;"
          stripe
          :default-sort = "{prop: 'date', order: 'descending'}"
          >
            <el-table-column
              prop="id"
              label="序号"
              width="80">
            </el-table-column>
            <el-table-column
              prop="enterprise_name"
              label="企业名称"
              min-width="180"
              class-name="companyname">
            </el-table-column>
            <el-table-column
              prop="email"
              label="企业账号"
              width="180">
            </el-table-column>
            <el-table-column
              prop="created_at"
              label="开户日期"
              sortable
              min-width="120">
            </el-table-column>
            <el-table-column
              label="状态"
              min-width="120">
              <template scope="scope">
                <el-button v-if="scope.row.status==0 || scope.row.status==1" type="primary" size="small"   @click="saddblockMember(historyData[scope.$index].public_key,historyData[scope.$index].id)">激活</el-button>
                <span v-else class="activebutton">{{scope.row.status_label}}</span>
              </template>
            </el-table-column>
          </el-table>
          <el-pagination class="table__page"
            layout="prev, pager, next"
            :total="total"
            :page-size="5"
             @current-change="changePage">
          </el-pagination>
      </div>
    </div>
  </div>

</template>
<script>
import api from '../../api'
export default {
  data() {
    return {
      body:{
        enterprise_name:'',
        user_name:''
      },
      blockBody:{
        user_id:'',
        signature:'',
        instruction_id:'',
      },
      show: false,
      info:'',
      historyData:[],
      btnActive:false,
      pickerOptions0: {},
      total:0,
      param:{
        page:1,
        pageSize:5,
        startTime: '',
        endTime: '',
        query: ''
      }
    }
  },
  mounted: function() {
    api.auth(this);
    this.addMemberHistory();
  },
  methods: {
    changePage(val) {
      this.param.page = val;
      this.addMemberHistory();
    },
    search(){
      this.addMemberHistory();
    },
     changeStartTime(events) {
        this.param.startTime = events;
      },
      changeEndTime(events) {
        this.param.endTime = events;
      },
      // //激活用户
      // async activeMem(row){
      //   console.log(row.id);
      //   let result=api.billNoteSigns().signUserRegister(api.userkey(),row.public_key,row.id,1);
      //   const data=await api.post(this,api.config.activemember);
      // },
    /**
     * [开户历史]
     */
    async addMemberHistory() {
      const data = await api.get(this, api.config.addMemberHistory, this.param);
      if(data.code == 200){
        this.total = data.count;
        this.historyData = data.data;
      }else{
        console.log(data.message);
      }
      console.log(data);
    },
    /**
     * [账户开立]
     */
    async addMember() {
      this.btnActive = true;
      const data = await api.post(this, api.config.addMember, this.body);
      if(data.code == 200){
        this.info = data;
        this.show = true;
        this.addBlockMember(data.public_key,data.user_id);
      }else{
        this.btnActive = false;
        this.$notify({
          title:'提示',
          message:data.message,
          type:'warning'
        });
      }
      console.log(data);
    },
    saddblockMember(public_key,user_id){
      this.show=false;
      this.addBlockMember(public_key,user_id);
    },
    async addBlockMember(public_key,user_id){
      let result=api.billNoteSigns().signUserRegister(api.userkey(),public_key,user_id,2);
      this.blockBody.signature=result.signdata;
      this.blockBody.instruction_id=result.sid;
      this.blockBody.user_id=user_id;
      const data = await api.post(this, api.config.activemember, this.blockBody);
      if(data.code==200){
        this.btnActive = false;
        this.addMemberHistory();
        this.$notify({
          title:'提示',
          message:'开户成功',
          type:'success'
        });
      }else{
        this.btnActive = false;
        this.show = false;
        this.$notify({
          title:'提示',
          message:data.message,
          type:'error'
        });
      }
    },
  }
}
</script>
<style scoped>
.pub__main{
  height: 400px;
}
.info{
  margin-top: 20px;
  color: #2fa4e7;
  font-weight: bold;
}
.info span:nth-child(1){
  margin-right: 40px;
}
</style>
