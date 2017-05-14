module.exports = {
  // api:'http://api.haipiaohui.com/',
  // api:'http://114.55.128.142:80/',
  api:'http://120.132.21.167:8080/',
  // api:'http://10.5.13.180:8080/',
  bankPublick:'4a246cd2a3f41b2bc1d071d2db159a388cd6f5c3547ea592d401f270073133d7',
  blockurl:'http://120.132.21.167:82/#/tradeHash/',
  // blockurl:'http://10.5.13.180:81/#/tradeHash/',
  timeout: 30000,
  headers: {
      Authorization: 'Bearer ' + sessionStorage.getItem('token'),
      'X-Requested-With': 'XMLhttpRequest',
      'Content-Type': 'application/json; charset=UTF-8'
  },
  authToken:'auth/token',
  userInfo: 'user/info', 
  addMember: 'user/add-member', // 开户
  addMemberHistory: 'user/add-member-history', // 开户历史
  billStatistics:'bill/statistics', // 获取票据统计信息
  checkWaitingList:'check/bill-waiting-list', // 审核列表
  billChecked:'check/bill-history', // 获取成员公司审核历史列表
  billSubstitute:'maintenance/substitute-list', // 获取代维护列表
  changePassword:'security/change-password', // 密码修改
  sendCaptcha:'message/send-captcha', // 短信发送
  authCer:'auth/certification', // 四要素验证
  maintainRecord: 'maintenance/history',//维护历史
  tradeRecord:'trade/history',//交易历史
  getBankInfo:'enterprise/bank-info',//获取银行信息
  getAllEnterInfo:'enterprise/info',//获取企业完整信息

  exportExcel: 'maintenance/export-history',
  waitingMainList: 'maintenance/self-list', // 票据待维护列表
  waitingCheckList: 'bill/waiting-check-list', // 票据待审核列表 , 待self是自己的，不带的是审别人的
  billCreate:'bill/create', //录入票据
  billCreatedList: 'bill/creator-bill-list', //票据录入历史
  checkRecordList: 'check/bill-history', // 历史审核列表
  billExamine: 'check/bill',//票据审核
  billInfo: 'bill/info', // 票据信息 
  histroyBillInfo:'check/bill-history-info',//查看审核历史记录
  billUpdate: 'bill/update' , // 修改票据
  tradeRecordMaintenance: 'maintenance/discount', // 贴现
  tradeRecordEndorse: 'maintenance/endorsed', // 票据背书
  billDelete: 'bill/delete', // 票据删除
  secondaryMaintenance: 'trade-record/secondary-maintenance', // 票据再维护
  secondaryEndorsed: 'trade-record/secondary-endorsed', // 票据再背书
  tradeRecordInfo: 'trade-record/info', // 票据交易详细信息
  // getPublicKey: 'user/get-public-key', // 根据企业名称获取公钥
  getPublicKey:'enterprise/public-key-by-enterprise-name',// 根据企业名称获取公钥
  agentList: 'agent/list',           // 操作人

  getEcdsInfo:'enterprise/ecds-info',//获取企业ESDS信息

  billMylist:'bill/my-list', // 我的票据列表 
  billListinglist:'bill/listing-list', // 票据交易列表
  tradeList:'trade/list', // 我的交易列表
  tradeListing:'trade/listing', // 挂牌
  possessorRevoke:'trade/possessor-revoke', // 拥有者撤单
  tradeDelist: 'trade/delist', // 摘牌

  enterpriseSubmit:'enterprise/enterprise-submit', // 企业信息提交
  bankSubmit:'enterprise/bank-submit', // 银行信息提交
  enterpriseApply:'auth/enterprise-apply', // 申请企业认证
  authEnterprise:'auth/enterprise', // 验证企业打款额
  propertyPayment:'property/payment', // 付款
  investorRevoke:'trade/investor-revoke', // 投资者撤单(付款hou)
  investorCancelRevoke:'trade/investor-cancel-revoke', // 投资者关闭撤单
  possessorConfirmRevoke:'trade/possessor-confirm-revoke', // 投资者同意撤单
  possessorRefuseRevoke: 'trade/possessor-refuse-revoke', // 投资者拒绝撤单
  possessorAssignment: 'trade/possessor-assignment', // 拥有者转让票据  
  tradeInvestorConfirm: 'trade/investor-confirm', // 投资者确认收票
  propertyCollection:'property/collection', // 收款
  securitySetTradePassword:'security/set-trade-password', // 设置交易密码
  securityChangeTradePassword:'security/change-trade-password', // 修改交易密码
  daybookList:'property/daybook-list',//资金流水
  uploadpcf:'trade/possessor-contract-file',//拥有人上传协议
  uploadIcf:'trade/investor-contract-file',//投资人上传协议

  getprovice:'data/province',//获取省份
  getcity:'data/city',//获取城市
  getDistrict:'data/district',//获取区域列表
  getbank:'data/bank',//获取银行列表
  getbankbanch:'data/bank-branch',//获取银行支行

  activemember:'user/activate-member',//激活成员公司
  getmoney:'property/cash-out',//提款
  getAssetInfo:'property/balance',//获取余额
  realTime:'statistics/real-time',//事实统计

  refund:'property/refund',//退款
}