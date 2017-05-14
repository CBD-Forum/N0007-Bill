// var basehostUrl = 'http://192.168.1.200';
var basehostUrl = 'http://114.55.128.142:81'
export default {
    // OAuth2
    authToken: basehostUrl + "/auth/token", //获取授权密钥
    authApply:basehostUrl+"/auth/apply",  //获取授权码

    // 用户
    userInfo: basehostUrl + "/user/user-info", // 获取用户信息
    signUp: basehostUrl + "/user/sign-up", // 注册
    authenticationEnterprise: basehostUrl + '/authentication/enterprise', // 企业认证
    getnterpriseInfo:basehostUrl+"/enterprise/enterprise-info", //获取用户绑定的企业信息

    //资金
    cashIn:basehostUrl+"/property/cash-in",    //充值
    getPropertyInfo:basehostUrl+"/property/property-info",      //资产信息
    getDaybookList:basehostUrl+"/property/daybook-list", //资金流水列表


    //代理人
    manageList:basehostUrl+"/agent/admin-list",

    //票据
    createBill:basehostUrl+"/bill/create",     //创建票据
    createItem:basehostUrl+"/item/create",      //挂牌
    billList:basehostUrl+"/bill/listing-list",   //获取挂牌票据列表
    pendBillList:basehostUrl+"/check/check-pending-list",  //获取待审核票据列表
    creatorlist:basehostUrl+'/bill/creator-list',

    // 我的担保
    collateralList:basehostUrl+'/guarantee/collateral-list', //
    ecdsList:basehostUrl+'/guarantee/ecds-list',
    confirmecds:basehostUrl+'/guarantee/guarantee',

    //获取银行列表
    dataBank: basehostUrl + '/data/bank',
    //票据录入
    billcreate:basehostUrl+'/bill/create',

    //地区接口
    getProvinceList:basehostUrl+"/data/province",         //获取省份列表
    getCityList:basehostUrl+"/data/city",                 //获取城市列表            
    getDistrictList:basehostUrl+"/data/district",         //获取区域列表
    getBankList:basehostUrl+"/data/bank",                //获取银行列表

    //其他
    uploadImage:basehostUrl+"/upload/image",   //图片上传

    //后台接口
    getEnterInfoList:basehostUrl+"/enterprise/admin-list",   //企业信息列表
    enterpriseInfo:basehostUrl+"/enterprise/admin-info", // 企业信息
    checkInfo:basehostUrl+"/check/bill-check-info", // 票据信息查看
    enterpriseAgentInfo:basehostUrl+"/check/enterprise-agent-info", // 企业信息+代理人信息
    checkField:basehostUrl+"/check/bill-check", // 票据字段审核
    checkPendingPass:basehostUrl+"/check/check-pending-pass", // 票据通过
    billTotal:basehostUrl+"/count/bill-total", // 首页票据金额信息 
    countRegistered:basehostUrl+"/count/registered", // 首页注册信息统计
    countIndex:basehostUrl+"/count/index", // 首页主要信息统计
    transactionProcess:basehostUrl+"/trade/transaction-process", // 交易
    checkHistory:basehostUrl + "/check/check-history", // 审核历史记录
    getConfigs: basehostUrl + "/config/get-configs" , // 获取系统配置
    setConfigs: basehostUrl + "/config/set-configs" , // 修改系统配置
    remitList:basehostUrl + "/authentication/remit-list", // 待打款列表
    
    remitConfirm:basehostUrl+"/authentication/remit-confirm", // 打款
    remitFailed:basehostUrl+"/authentication/remit-failed", // 打款失败

    waitingAuditList:basehostUrl+"/authentication/waiting-audit-list", // 认证管理
    waitingRemitList:basehostUrl+"/authentication/waiting-remit-list", // 验证码
    auditConfirm:basehostUrl+"/authentication/audit-confirm", // 认证管理通过
    auditFailed:basehostUrl+"/authentication/audit-failed",
    remitHistory:basehostUrl + "/authentication/authentication-history", // 历史

    ecdsInfo:basehostUrl+"/authentication/ecds-info", // ecdsinfo
    legalPersonInfo :basehostUrl+"/authentication/legal-person-info", // legalPersonInfo
    businessInfo: basehostUrl+"/authentication/business-info", // buinessInfo
    enterpriseInfo:basehostUrl+"/authentication/enterprise-info",
    bankInfo:basehostUrl+"/authentication/bank-info",
}
