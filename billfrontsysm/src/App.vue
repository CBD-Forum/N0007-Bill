<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'app'
}
</script>
<style>
.el-dragger__cover__interact{
  /*display: none !important;*/
}
#app{
  height:100%;
}
.title {
  h2 {
    display: inline-block;
    font-size: 18px;
    /*font-weight: bold;*/
    color: #2fa4e7;
    padding-top: 15px;
  }
  a {
    fl: left;
    w: 160px;
    h: 40px;
    lh: 40px;
    bdrs: 0px 0px 6px 6px;
    c: #fff;
    fz: 16px;
    ta: center;
    bg: #88cdf6;
    cursor: pointer;
    &.active {
      bg: #2fa4e7;
    }
  }
  .search-warp {
    fl: right;
    mt: 16px;
    input {
      bd: #2fa4e7 1px solid;
      bdrs: 6px;
      fz: 12px;
      c: #808080;
      h: 26px;
    }
    &__title {
      w: 240px;
      mr: 17px;
    }
    &__type {
      w: 130px;
      mr: 17px;
    }
    &__date {
      w: 130px!important;
      mr: 1px;
    }
    &__submit {
      vertical-align: top;
      w: 50px;
      c: #2fa4e7;
      bdc: #2fa4e7;
      h: 26px;
      pt: 6px;
      ml: 16px;
      fz: 12px;
    }
    
  }
}

.el-table th {
  height: 36px!important;
}

.el-table td,
{
  height: 44px!important;
}

.el-table {
  border-radius: 6px 6px 0px 0px;
}


/* add in 2017-2-13 */

.pub__main {
  background: #fff;
  border-top: #2fa4e7 3px solid;
  padding: 0px 20px;
  margin-bottom: 10px;
  /*min-height: 870px;*/
}

.pub__title {
  font-size: 16px;
  height: 40px;
  line-height: 40px;
  color: #2fa4e7;
}

.pub__icon {
  text-align: center;
  font-size: 70px !important;
  color: #2fa4e7;
  margin-bottom: 20px;
  margin-top: 50px;
}

/*.el-input__inner {
  height: 32px !important;
}*/

.el-form-item {
  margin-bottom: 6px !important;
}

.pub_btnBox {
  padding-left: 20px;
  margin-top: 20px;
}

.pub_btnBox .el-button {
  width: 100%;
}

.table__page {
  margin: 14px auto;
  text-align: center;
}

.tablemain {
  background: #fff;
  border-top: #2fa4e7 3px solid;
  padding: 0 20px;
  margin-bottom: 10px;
}

.pub_iconOran {
  color: #e96345;
  position: absolute;
  left: 12px;
  top:3px;
  font-size: 40px !important;
}
.icon_green{
  color: #18a918;
}
.icon_blue{
  color: #207fb7;
}
/*::-webkit-input-placeholder {
  letter-spacing:3px;
  font-size:12px;
  color:#808080;
}*/
.el-form-item .el-input__inner{
  height: 32px !important;
}

.el-input.is-disabled .el-input__inner{
  border:#badef3 1px solid!important;
  background: #f2fbff!important;
  font-size:14px!important;
  color:#2fa4e7!important;
}
.icon-wenjian{
  color:#75a0b3;
  font-size:25px!important;
  cursor: pointer;
}
</style>
