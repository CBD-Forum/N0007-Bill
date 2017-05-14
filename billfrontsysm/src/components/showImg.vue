<template>
  <div class="showImg">
    <i class="el-icon-close showImg__close" @click="closeShowImg"></i>
    <h1>附件</h1>
    <swiper :options="swiperOption" ref="mySwiperA" class="swiperImg">
      <!-- 幻灯内容 -->
      <swiper-slide v-for="slide in swiperData">

        <span>{{slide.title}}</span><a class="download" download :href="slide.src" @click="fuck(3)">(下载)</a>
        <img v-show="slide.title != '转让协议'" :src="slide.src"/>
      </swiper-slide>
    </swiper>
    <div class="swiper-pagination" slot="pagination"></div>
    <div class="swiper__prev" slot="button-prev"></div>
    <div class="swiper__next" slot="button-next"></div>
    <div class="swiper-scrollbar"   slot="scrollbar"></div>
  </div>
</template>
<script>

export default {
  props:['swiperData','showImg'],
  data() {
    return {
      swiperOption: {
        notNextTick: true,
        autoplay: false,
        scrollbarHide:true,
        pagination : '.swiper-pagination',
        paginationClickable :true,
        prevButton:'.swiper__prev',
        nextButton:'.swiper__next',
        mousewheelControl : false,
        observeParents:true,
        debugger: true
      }
    }
  },
  mounted: function() {
    this.$nextTick(() => {
    })
  },
  methods: {
    closeShowImg(){
      this.$emit('close');
    },
    fuck(){
      console.log(324);
    }
  },
  computed:{
    swiper() {
      return this.$refs.mySwiperA.swiper
    }
  }
}
</script>

<style>
a.download{
  display: inline-block;
  color:#fff;
  margin-left: 10px;
}
.showImg{
  width: 1000px!important;
  height:540px!important;
  position: absolute!important;
  left:50%!important;
  top:15%!important;
  margin-left:-500px!important;
  background:rgba(0, 0, 0, 0.7)!important;
  border-radius: 8px!important;
  z-index:999!important;
  h1{
    line-height:50px;
    text-align:center;
    color:#fff;
    font-size:18px;
  }
  .swiperImg{
    height:440px;
    width:860px;
    margin:0px auto;
    .swiper-wrapper{
      img{
        height:400px;
        width:100%;
      }
    }
    .swiper-slide{
      span{
        display: inline-block;
        padding:7px 0px;
        font-size:16px;
        color:#fff;
      }
    }
  }
  .swiper__prev,.swiper__next{
    position: absolute;
    top: 50%;
    width: 32px;
    height: 55px;
    margin-top: -27.5px;
    z-index: 10;
    cursor: pointer;
    -moz-background-size: 34px 55px;
    -webkit-background-size: 34px 55px;
    background-size: 34px 55px;
    background-position: center;
    background-repeat: no-repeat;
  }
  .swiper__prev{
    background-image:url("../assets/img/pre.png");
    left:20px;
  }
  .swiper__next{
    background-image:url("../assets/img/next.png");
    right:20px;
  }
  .swiper-pagination{
    width:100%;
    margin-top:18px;
    .swiper-pagination-bullet{
      width:12px;
      height:12px;
      margin:0px 5px;
      background:#9b999c;
    }
    .swiper-pagination-bullet-active{
      background:#fff;
    }
  }
  .showImg__close{
    position: absolute;
    right:20px;
    top:20px;
    font-size:22px;
    color:#fff;
    cursor: pointer;
  }
}
</style>
