import axios from 'axios'
import config from './config'

/**
 * [checkCode deal with data.code]
 * @param  {[object]} response
 * @return {[object]} response
 */
function checkStatus(response) {
  // console.log(response);
  if (response.status == 200) {
    return response.data;
  } else {
    return false;
  }
}

export default {
  /**
   * [config some public options about ajax]
   * @type {[object]}
   */
  config: config,
  /**
   * [get]
   * @param  {[string]} url    
   * @param  {[object]} params [get params]
   * @return {[promise object]} axios [a promise object]
   */
  get(that, url, params,callback) {
    return axios({
      method: 'get',
      url: config.api + url,
      params: params,
      timeout: config.timeout,
      headers: {
        Authorization: 'Bearer ' + sessionStorage.getItem('token'),
        'X-Requested-With': 'XMLhttpRequest',
        'Content-Type': 'application/json; charset=UTF-8'
      },
    }).then(response => {
      if (response.status == 200) {
        if(callback){
          callback(response.data);
        }
        // 需要重新登陆
        if (response.data.code == 10017) {
          // location.href="https://www.haipingx.com/login.html";
          that.$router.push({
            path: '/index'
          });
          // 成员公司需要重新认证
        } else if (response.data.code == 401) {
          sessionStorage.isAuth = '0';
          that.$router.push({
            path: '/user/auth'
          });

          // sessionStorage.change = true;
        } else {
          return response.data;
        }
      }
    })
  },
  /**
   * [post]
   * @param  {[string]} url    
   * @param  {[object]} params [post body]
   * @return {[promise object]} axios [a promise object]
   */
  post(that, url, data,callback) {
    return axios({
      method: 'post',
      url: config.api + url,
      data: data,
      timeout: config.timeout,
      headers: {
        Authorization: 'Bearer ' + sessionStorage.getItem('token'),
        'X-Requested-With': 'XMLhttpRequest',
        'Content-Type': 'application/json; charset=UTF-8'
      },
    }).then(response => {
      if (response.status == 200) {
        if(callback){
          callback(response.data);
        }
        // 需要重新登陆
        if (response.data.code == 10017) {
          // location.href="https://www.haipingx.com/login.html";
          that.$router.push({
            path: '/index'
          });
          // 成员公司需要重新认证
        } else if (response.data.code == 401) {
          sessionStorage.isAuth = '0';


          that.$router.push({
            path: '/user/auth'
          });
        } else {
          return response.data;
        }
      }
    })
  },
  /**
   * [权限验证]
   * @param  {[obj]} _this [this指针]
   */
  auth(_this) {
    // return true;

    // console.log(_this.$route.path);
    var path = _this.$route.path;

    if (sessionStorage.token == null) {
      if(path!='/index'){
        _this.$alert(
          '请先登录',
          '提示',
        ).then(() => {
          // location.href="https://www.haipingx.com/login.html";
          _this.$router.push({
            path: '/index '
          });
        });
      }
      return;
    }
    if (sessionStorage.status == '4' && sessionStorage.type == '3') {
      if(path!='/user/password'){
         _this.$alert(
          '请先修改登录密码',
          '提示',
        ).then(() => {
          _this.$router.push({
            path: '/user/password'
          });
        });
      }
      return;
    }
    // if(){

    // }
    if (sessionStorage.isAuth != '1' && sessionStorage.type == '3') {
      if(path!='/user/mcrIndex'){
        _this.$alert(
          '请先进行操作员认证',
          '提示',
        ).then(() => {
          _this.$router.push({
            path: '/user/auth'
          });
        });
      }
      return;
    }
    if(sessionStorage.type == '4' && sessionStorage.getItem('enterprise_status')!=8){
      _this.$alert(
        '请先进行企业认证',
        '提示',
      ).then(() => {
        _this.$router.push({
          path: '/user/enterprise'
        });
      });
      return;
    }
    return true;
  },
  /**
   * [转换票据]
   */
  transBillType(str) {
    if (str == 1 || str == 2) {
      return str;
    }
    return str == '银行汇票' ? 1 : 2;
  },
  /**
   * [转换承兑]
   */
  transAcceType(str) {
    if (str == 1 || str == 2 || str == 3 || str == 4 || str == 5 || str == 6 || str == 7 || str == 8) {
      return str;
    }
    let trans = {
      '企业': '1',
      '国股': '2',
      '城商': '3',
      '农商': '4',
      '外资': '5',
      '农信': '6',
      '财务公司': '7',
      '其他':'8'
    }
    return trans[str];
  },

  // operationTrade(str) {
  //   let trans = {
  //     '': '1',
  //     '国股': '2',
  //     '城商': '3',
  //     '农商': '4',
  //     '外资': '5',
  //     '农信': '6',
  //     '财务公司': '7',
  //     '其他':'8'
  //   }
  //   return trans[str];
  // },
  /**
   * [转换票据状态]
   */
  transBillStatus(status, uid, p, i) {
    // $STATUS_LISTING = 3;
    // $STATUS_LISTING_LABEL = '挂牌中';
    
    if(uid == p){
      let trans = {
        '0': '待审核',
        '1': '审核失败',
        '2': '持有中',
        '3': '挂牌中',
        '4': '待投资人付款',
        '5': '待拥有人转让',
        '6': '待投资人确认收到票据',
        '7': '待收款',
        '8': '待拥有人同意撤销',
        '9': '待拥有人同意撤销',
        // '8': '待拥有人确认',
        // '9': '待拥有人确认',
        '10': '待退款',
        '11':'拒绝撤单',
        '12':'拒绝撤单',
      }
      return trans[status];
    }else{
      let trans = {
        '4': '待付款',
        '5': '待拥有人转让',
        // '6': '待确认',
        '6': '待投资人确认收到票据',
        '7': '待拥有人收款',
        '8': '待拥有人同意撤销',
        '9': '待拥有人同意撤销',
        '10': '待退款',
        '11':'拥有者拒绝撤单',
        '12':'拥有者拒绝撤单',
      }
      return trans[status];
    }
  },
  /**
   * [获取billNoteSign]
   */
  billNoteSigns(){
    return new billSign();
  },
  /**
   * [获取私钥]
   * @param  {[str]} password [密码]
   * @param  {[str]} random   [随机数]
   * @return {[str]}          [私钥]
   */
  getPrivatekey(password, random) {
    // console.log(password);
    // console.log(random);
    return this.billNoteSigns().getPrivatekey(password, random);
  },
  /**
   * [获取公钥]
   * @param  {[str]} privatekey [私钥]
   * @return {[str]}            [公钥]
   */
  getPublicKey(privatekey) {
    let privkey_buff = new Buffers(privatekey, "hex");
    return this.billNoteSigns().getPublicKey(privkey_buff);
  },
  /**
   * [获取混合公私钥]
   * @return {[Buffers]} [混合公私钥]
   */
  userkey() {
    // console.log(sessionStorage.privateKey);
    // console.log(sessionStorage.publicKey);
    // console.log(sessionStorage.privateKey + sessionStorage.publicKey);
    return {
      'privateKey':sessionStorage.privateKey,
      'publicKey':sessionStorage.publicKey
    }
  },
  /**
   * [删除票据]
   * @param  {[number]}   id       [票据id]
   * @param  {[obj]}   _this    [this指针]
   * @param  {Function} callback [回调函数]
   */
  async billDelete(that, possessor_address, id,bill_number, _this, callback) {
    let result = this.billNoteSigns().signDeleteNote(this.userkey(), possessor_address, bill_number);
    const data = await this.post(that, this.config.billDelete, {... { id: id }, ... { signature: result.signdata, instruction_id: result.sid } });
    if (data.code == 200) {
      _this.$notify({
        title: '提示',
        message: data.message,
        type: 'success'
      });
      callback();
    } else {
      _this.$notify({
        title: '提示',
        message: data.message,
        type: 'warning'
      });
    }
  },
  /**
   * [toThousands description]
   * @param  {[type]} num [description]
   * @return {[type]}     [description]
   */
  toThousands(num) {
    var num = (num || 0).toString(),
      result = '';
    while (num.length > 3) {
      result = ',' + num.slice(-3) + result;
      num = num.slice(0, num.length - 3);
    }
    if (num) { result = num + result; }
    return result;
  },

  async getAssetsInfo(callback){
    const data=await this.post(this,this.config.getAssetInfo);
    if(data.code==200){
      callback(data);
    }else{
      console.log('获取余额失败');
    }
  },
  async getrealTime(callback){
    const data=await this.post(this,this.config.realTime);
    if(data.code==200){
     callback(data)
    }else{
      console.log('失败');
    }
  }
}
