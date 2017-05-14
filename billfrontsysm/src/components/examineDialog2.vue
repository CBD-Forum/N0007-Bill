<template>
  <div>
    <el-dialog title="票据审核" v-model="whopenTemp" @close="closeD" class="whdialog examineDialog tinyD1">
      <div class="whcontent">
        <div class="whcontent__info" >
          <el-form label-width="130px" class="demo-ruleForm">
            <div v-show="whDate.agent_name != null" style="position:absolute;left:150px;top:58px;">
              代理人<span class="sbcolor" style="margin-left:18px;margin-right:20px;">{{whDate.agent_name}}</span>联系电话<span style="margin-left:20px;" class="sbcolor">{{whDate.agent_mobile}}</span>
            </div>
            <!-- <span style="position:absolute;left:455px;top:58px;cursor:pointer;" @click="selectAll">全选</span> -->
            <el-form-item v-show="whDate.enterprise_name != null" label="企业名称" style="margin-bottom:0 !important;">
              <span class="examineInfo">{{whDate.enterprise_name}}</span>
            </el-form-item>
            <el-form-item label="票据类型">
              <span class="examineInfo">{{whDate.type}}</span>
              <el-checkbox v-model="checked.checked1" style="float:right" disabled></el-checkbox>
            </el-form-item>
            <el-form-item label="票据编号">
              <span class="examineInfo">{{whDate.bill_number}}</span>
              <el-checkbox v-model="checked.checked2" style="float:right" disabled></el-checkbox>
            </el-form-item>
            <el-form-item label="出票人">
              <span class="examineInfo">{{whDate.drawer}}</span>
              <el-checkbox v-model="checked.checked3" style="float:right" disabled></el-checkbox>
            </el-form-item>
            <el-form-item label="承兑人">
              <span class="examineInfo">{{whDate.acceptor}}</span>
              <el-checkbox v-model="checked.checked4" style="float:right" disabled></el-checkbox>
            </el-form-item>
            <el-form-item label="收票人">
              <span class="examineInfo">{{whDate.taker}}</span>
              <el-checkbox v-model="checked.checked5" style="float:right" disabled></el-checkbox>
            </el-form-item>
            <el-form-item label="承兑人类型">
              <span class="examineInfo">{{whDate.acceptor_type}}</span>
              <el-checkbox v-model="checked.checked6" style="float:right" disabled></el-checkbox>
            </el-form-item>
            <el-form-item label="出票日" prop="name">
              <span class="examineInfo">{{whDate.issue_at}}</span>
              <el-checkbox v-model="checked.checked7" style="float:right" disabled></el-checkbox>
            </el-form-item>
            <el-form-item label="票据到期日" prop="name">
              <span class="examineInfo">{{whDate.acceptance_at}}</span>
              <el-checkbox v-model="checked.checked8" style="float:right" disabled></el-checkbox>
            </el-form-item>
            <el-form-item label="票据金额" prop="name">
              <span class="examineInfo">{{whDate.face_amount}}</span>
              <el-checkbox v-model="checked.checked9" style="float:right" disabled></el-checkbox>
            </el-form-item>
            <!-- <el-form-item label="审核利率" prop="name">
              <span>≤</span>
              <el-input-number placeholder="贴现利率" v-model="annualized_rate_suggest" :controls="false" style="width: 235px;margin-left: 20px;vertical-align: middle;">
                <template slot="append">%</template>
              </el-input-number>
            </el-form-item> -->
          </el-form>
        </div>
        <div class="whcontent__img">
          <span>票面信息</span> <a :href="whDate.bill_front_path" download>下载</a>
          <img :src="whDate.bill_front_path" style="margin-bottom:43px;margin-top:7px;" @click="showDetail(1)" />
          <span>背书信息</span> <a :href="whDate.bill_back_path" download>下载</a>
          <img :src="whDate.bill_back_path" style="margin-top:7px;" @click="showDetail(2)" />
        </div>
      </div>
      <!-- <div slot="footer" class="dialog-footer" style="padding-top:0px;">
        <el-button type="primary" @click="examineSubmit(whDate.id)" :loading="btnActive">确认提交</el-button>
      </div> -->
    </el-dialog>
    <div v-show="showPic" class="showPic" id="oDrag">
      <i class="el-icon-close showImg__close" @click="closeShowPic"></i>
      <h1 id="oTitle">图片</h1>
      <div class="imgbox">
        <img :src="showPicSrc" />
      </div>
    </div>
    </div>
</template>
<script>
import api from '../api'
export default {
  props: ['whopen', 'whDate', 'isSelf'],
  data() {
    return {
      btnActive: false,
      showPic: false,
      showPicSrc: '',
      examineInfo: [],
      annualized_rate_suggest: '',
      checked:{
        checked1:false,
        checked2:false,
        checked3:false,
        checked4:false,
        checked5:false,
        checked6:false,
        checked7:false,
        checked8:false,
        checked9:false,
      },
      checkList: {
        bill_number: false,
        drawer: false,
        acceptor: false,
        face_amount: false,
        issue_at:false,
        acceptance_at: false,
        acceptor_type: false,
        taker: false,
        type: false,

      },
    }
  },
  watch: {

    whopen: function(val) {
      if (val == true) {
        setTimeout(()=>{
          this.checked.checked1=this.whDate.type_status==1?true:false;
          this.checked.checked2=this.whDate.bill_number_status==1?true:false;
          this.checked.checked3=this.whDate.drawer_status==1?true:false;
          this.checked.checked4=this.whDate.acceptor_status==1?true:false;
          this.checked.checked5=this.whDate.taker_status==1?true:false;
          this.checked.checked6=this.whDate.acceptor_type_status==1?true:false;
          this.checked.checked7=this.whDate.issue_at_status==1?true:false;
          this.checked.checked8=this.whDate.acceptance_at_status==1?true:false;
          this.checked.checked9=this.whDate.face_amount_status==1?true:false;
        }, 500);
      }
    },
  },
  mounted: function() {
    this.$nextTick(() => {
      console.log(111111);
      var oTitle = document.getElementById('oTitle');
      var oDrag = document.getElementById('oDrag')
      oTitle.onmousedown = function(event) {
        // console.log('3240');
        // return;
        // oTitle.style.cursor = "move";
        var event = event || window.event;
        console.log(oDrag.offsetLeft);
        console.log(oDrag.offsetTop);
        var disX = event.clientX - (oDrag.offsetLeft + 500);
        var disY = event.clientY - oDrag.offsetTop;
        //鼠标移动，窗口随之移动     onmousemove在有物体移动是才执行alert事件；
        document.onmousemove = function(event) {
            var event = event || window.event;
            var maxW = document.documentElement.clientWidth - oDrag.offsetWidth;
            var maxH = document.documentElement.clientHeight - oDrag.offsetHeight;
            var posX = event.clientX - disX;
            var posY = event.clientY - disY;
            if (posX < 500) {
              // posX = 500;
            } else if (posX > maxW + 500) {
              // posX = maxW + 500;
            }
            if (posY < 0) {
              // posY = 0;
            } else if (posY > maxH) {
              // posY = maxH;
            }
            console.log('posX' + posX);
            console.log('posY' + posY);
            oDrag.style.left = posX + 'px';
            oDrag.style.top = posY + 'px';
          }
          //鼠标松开，窗口将不再移动
        document.onmouseup = function() {
          document.onmousemove = null;
          document.onmouseup = null;
        }
      }
    })
  },
  computed: {
    // 由于子组件不能修改props的值，因此添加一个clone
    whopenTemp: function() {
      return this.whopen;
    },
    headers: function() {
      return {
        Authorization: "Bearer " + sessionStorage.getItem('token'),
        Accept: "application/json; charset=utf-8"
      }
    }
  },
  methods: {
    selectAll(){
      this.checkList =  {
        bill_number: true,
        drawer: true,
        acceptor: true,
        face_amount: true,
        issue_at:true,
        acceptance_at: true,
        acceptor_type: true,
        taker: true,
        type: true,
      }
    },
    closeD() {
      this.$emit('close');
    },
    showDetail: function(type) {
      this.showPic = true;
      this.showPicSrc = type == 1 ? this.whDate.bill_front_path : this.whDate.bill_back_path;
    },
    closeShowPic() {
      this.showPic = false;
    },
    async examineSubmit(id) {
      this.btnActive = true;
      let checkdata = {};
      for (var checkI in this.checkList) {
        if (this.checkList[checkI]) {
          checkdata[checkI] = 1
        } else {
          checkdata[checkI] = 0
        }
      }

      // let result = api.billNoteSigns().signCreateNote(api.userkey(), this.whDate.possessor_address, api.transBillType(this.whDate.type), this.whDate.bill_number, this.whDate.drawer, this.whDate.taker, this.whDate.acceptor, Date.parse(new Date(this.whDate.acceptance_at)) / 1000, this.whDate.face_amount,Date.parse(new Date(this.whDate.issue_at)) / 1000);
      // if (result.flag != 1) {
      //   this.$notify({
      //     title: '提示',
      //     message: result.error,
      //     type: 'warning'
      //   });
      //   this.btnActive = false;
      //   return;
      // }
      // const data = await api.post(this, api.config.billExamine, { ...{
      //     signature: result.signdata,
      //     instructionId: result.sid
      //   },
      //   ...{
      //     id: id,
      //     annualized_rate_suggest: this.annualized_rate_suggest,
      //     field: checkdata
      //   }
      // });
      const data = await api.post(this, api.config.billExamine, {
          bill_id: id,
          annualized_rate_suggest: this.annualized_rate_suggest,
          field_status: checkdata
        }
      );
      console.log(data);
      this.btnActive = false;
      if (data.code == 200) {
        this.$notify({
          title: '成功',
          message: '票据审核成功',
          type: 'success'
        });
        this.$emit('close');
      } else {
        this.$notify({
          title: '提示',
          message: data.message,
          type: 'warning'
        });
      }
    },
  }
}
</script>
<style>
.showPic {
  width: 1000px!important;
  height: 540px!important;
  position: absolute!important;
  left: 50%;
  top: 15%;
  margin-left: -500px!important;
  background: rgba(0, 0, 0, 0.7)!important;
  border-radius: 8px!important;
  z-index: 9999999!important;
}
  .whcontent__info{
    margin-bottom: 10px;
  }
.showPic .imgbox {
  height: 450px;
  width: 90%;
  margin: 0 auto;
}

.showPic .imgbox img {
  width: 100%;
  height: 100%;
}

.showPic h1 {
  cursor: move;
  line-height: 50px;
  text-align: center;
  color: #fff;
  font-size: 18px;
}

.showImg__close {
  position: absolute;
  right: 20px;
  top: 20px;
  font-size: 22px;
  color: #fff;
  cursor: pointer;
}

span.sbcolor {
  color: #2fa4e7;
}

.whButton {
  background-color: #94d7f4;
  color: #fff;
  border-color: #94d7f4;
  padding: 8px 20px;
}

.whButton:hover {
  border-color: #94d7f4;
  color: #fff;
}

.whButton.active {
  background: #2fa4e7;
  color: #fff;
  border-color: #2fa4e7;
}

.whcontent_tab {
  position: absolute;
  left: 0;
  width: 100%;
  top: 60px;
  a {
    cursor: pointer;
    display: block;
    float: left;
    width: 450px;
    text-align: center;
    font-size: 18px;
    color: #808080;
    padding-bottom: 10px;
    border-bottom: 1px solid #808080;
  }
  a.active {
    color: #2fa4e7;
    border-bottom: 4px solid #2fa4e7;
  }
}

.tinyD1 {
  .el-dialog {
    height: auto !important;
  }
}

.whdialog {
  .recordData {
    width: 100%;
  }
  .el-dialog {
    width: 900px;
    border-radius: 6px;
    &__header {
      text-align: center;
      padding-bottom: 20px;
    }
    &__title {
      font-weight: 400;
      text-align: center;
    }
    &__body {
      padding: 0px 80px;
      .whcontent.mt {
        margin-top: 50px;
      }
      wo .whcontent {
        /*margin-top: 50px;*/
        display: flex;
        &__info {
          flex: 1;
          margin-top: 23px;
        }
        &__img {
          padding-left: 40px;
          flex: 0.75;
          &>P {
            font-size: 14px;
            color: #333333;
            margin-bottom: 7px;
          }
          &>img {
            width: 300px;
            height: 140px;
            border-radius: 6px;
            border: #2fa4e7 1px solid;
            margin-bottom: 4px;
          }
          .el-upload {
            width: 300px;
          }
          .el-dragger {
            width: 300px;
            height: 140px;
            background: none!important;
            border: #2fa4e7 1px solid;
          }
        }
      }
    }
    .dialog-footer {
      /*padding-top: 0px !important;*/
      text-align: center;
      button {
        width: 300px;
        &:hover {
          background: #2fa4e7;
          border-color: #2fa4e7;
        }
        &:nth-child(2) {
          margin-left: 40px;
          background: #10b597;
          color: #fff;
          border-color: #10b597;
          &:hover {
            border-color: #10b597;
          }
        }
        ;
      }
    }
  }
}

.whcontent__img a {
  color: #2fa4e7 !important;
}
</style>
