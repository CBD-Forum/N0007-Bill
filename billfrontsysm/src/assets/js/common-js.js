/* 绑定拖拽事件 */
var dragEvent = function() {
    let oTitle = document.getElementsByClassName('el-dialog');
    for (let i = 0; i < oTitle.length; i++) {
        oTitle[i].children[0].onmousedown = function(event) {
            // oTitle[i].children[0].style.cursor = "move";
            // 此w用于消除element弹框translateX(-50%)带来的offset计算偏差
            let w = oTitle[i].clientWidth / 2;
            var event = event || window.event;
            var disX = event.clientX - (oTitle[i].offsetLeft - w);
            var disY = event.clientY - oTitle[i].offsetTop;
            // 鼠标移动，窗口随之移动     onmousemove在有物体移动是才执行alert事件；
            document.onmousemove = function(event) {
                var event = event || window.event;
                let maxW = document.documentElement.clientWidth - oTitle[i].offsetWidth;
                let maxH = document.documentElement.clientHeight - oTitle[i].offsetHeight;
                let posX = event.clientX - disX + w;
                let posY = event.clientY - disY;
                if (posX < w) {
                    posX = w;
                } else if (posX > maxW + w) {
                    posX = maxW + w;
                }
                if (posY < 0) {
                    posY = 0;
                } else if (posY > maxH) {
                    posY = maxH;
                }
                oTitle[i].style.left = posX + 'px';
                oTitle[i].style.top = posY + 'px';
            }
            //鼠标松开，窗口将不再移动
            document.onmouseup = function() {
                // oTitle[i].children[0].style.cursor = "";
                document.onmousemove = null;
                document.onmouseup = null;
            }
        }
    }
}

//组件时间格式转换
var temptime=function(temptime){
    if (temptime != '' && (typeof temptime == 'object')) {
        temptime = temptime.getFullYear() + '-' + (temptime.getMonth() + 1) + '-' + temptime.getDate();
    }
    return temptime;
}

//金额小写转大写
var smalltobig = function(num) {
    console.log(num);
    var strOutput = "";
    var strUnit = '仟佰拾亿仟佰拾万仟佰拾元角分';
    num += "00";
    var intPos = num.indexOf('.');
    if (intPos >= 0) {
        num = num.substring(0, intPos) + num.substr(intPos + 1, 2);
    }
    strUnit = strUnit.substr(strUnit.length - num.length);
    for (var i = 0; i < num.length; i++) {
        strOutput += '零壹贰叁肆伍陆柒捌玖'.substr(num.substr(i, 1), 1) + strUnit.substr(i, 1);
    }
    return strOutput.replace(/零角零分$/, '整').replace(/零[仟佰拾]/g, '零').replace(/零{2,}/g, '零').replace(/零([亿|万])/g, '$1').replace(/零+元/, '元').replace(/亿零{0,3}万/, '亿').replace(/^元/, "零元");
}

/* 时间戳转换函数 */
var getDataYear = function(format, timestamp) {
    var date = new Date(timestamp ? (parseInt(timestamp) * 1000) : new Date().getTime());
    var FORMAT = new Object();
    FORMAT = {
        'Y': "date.getFullYear()",
        'M': "date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1",
        'D': "date.getDate() < 10 ? '0' + date.getDate() : date.getDate()",
        'h': "date.getHours() < 10 ? '0' + date.getHours() : date.getHours()",
        'm': "date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()",
        's': "date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds()"
    }
    for (var i in FORMAT) {
        if (format.indexOf(i) != -1) {
            format = format.replace(i, eval(FORMAT[i]));
        }
    }
    return format;
}

// /* 区块链签名函数 */
// var signtx = function(tx, hash, key) {
//     var sig = secp256k1.sign(hash, key);
//     tx.r = sig.signature.slice(0, 32);
//     tx.s = sig.signature.slice(32, 64);
//     tx.v = sig.recovery + 27;
//     return tx;
// }

// /* 获取tx对象 */
// var getTx = function(rHash) {
//     var hash = new Buffer(rHash, 'hex');
//     var myprivatekey = new Buffer(sessionStorage.privkey, 'hex');
//     var stx = signtx({}, hash, myprivatekey);
//     var tx = {};
//     tx['r'] = stx['r'].toString("hex");
//     tx['s'] = stx['s'].toString("hex");
//     var tmp = stx['v'].toString(16);
//     if (tmp.length == 1) {
//         tmp = "0" + tmp;
//     }
//     tx['v'] = tmp.toString("hex");
//     return tx;
// }

/* ajax请求 */
// var ajax = function(that, url, body, success, method, params) {
    
//     var token = sessionStorage.getItem('token');
//     var openId = sessionStorage.getItem('openId');
//     // var params = params || { open_id: openId };
//     that.$http({
//         method: method || "POST",
//         url: url,
//         headers: {
//             Authorization: 'Bearer ' + token
//         },
//         params: params,
//         body: body
//     }).then((response) => {
//         if(response.code==10016 || response.code==10017){
//             sessionStorage.clear();
//             that.$router.push({
//                 path: '/index'
//             });
//         }else{
//             success(response.data);
//         }
        
//     }), (response) => {
//         console.error(response);
//     }
// }

/* 获取交易历史状态 */
var getHistoryStatus = function(e) {
    let itemObj = {
        '0':'录入',
        '1': '挂牌',
        '2': '摘牌',
        '3': 'ECDS转让',
        '4': '已撤销',
        '5': 'ECDS收票',
        '6': '已回款',
    }
    return itemObj[e];
}



/* 获取银行图片*/
var getBankImg = function(bankName) {
    var bankMap = {
        "中国银行": 'zgyh.jpg',
        "中国工商银行": 'gsyh.jpg',
        '中国农业银行': 'nyyh.jpg',
        "中国建设银行": 'jsyh.jpg',
        "中国邮政储蓄银行": 'yzcxyh.jpg',
        "交通银行": 'jtyh.jpg',
        "招商银行": 'zsyh.jpg',
        "浦发银行": 'pdfzyh.jpg',
        "华夏银行": 'hxyh.jpg',
        "中国民生银行": 'msyh.jpg',
        "兴业银行": 'xyyh.jpg',
        "广东发展银行": 'gfyh.jpg',
        "中信银行": 'zxyh.jpg',
        "深圳发展银行": 'szfzyh.jpg',
        "中国光大银行": 'gdyh.jpg',
        "平安银行": 'payh.jpg',
        "浙商银行": 'zhesyh.jpg',
        "渤海银行": '',
        "恒生银行": '',
        "温州银行": '',
    }
    return bankMap[bankName];
}

 /*
 * idCard(str idCard, boolen strict)
 * 校验是否是合法的身份证号
 * return 合法的身份证号 => true;不合法的身份证号 => false;
 * @author Verdient。
 */
var isIdCard = function(idCard) {
    var reg = /^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/;
    if (reg.test(idCard)) {
        if (idCard.length == 18) {
            var idCardWi = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            var idCardY = new Array(1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2);
            var idCardWiSum = 0;
            for (var i = 0; i < 17; i++) {
                idCardWiSum += idCard.substring(i, i + 1) * idCardWi[i];
            }
            var idCardMod = idCardWiSum % 11;
            var idCardLast = idCard.substring(17);
            if (idCardMod == 2) {
                if (idCardLast == "X" || idCardLast == "x") {
                    return true;
                } else {
                    return false;
                }
            } else {
                if (idCardLast == idCardY[idCardMod]) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    } else {
        return false;
    }
}

var isPhone = function(phone) {
    phone = phone.toString();
    var reg = /^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/;      
    if (phone.length != 11) {
        return false;
    } else if (!reg.test(phone)) {
        return false;
    } else {
        return true;
    }
}

  /*
 * bankCard(str idCard)
 * 校验是否是合法的银行卡号
 * return 合法的银行卡号 => true;不合法的银行卡号 => false;
 * @author Verdient。
 */
var isBankCard= function(bankCard) {
    var reg = /^(\d{16}|\d{19})$/;
    return reg.test(bankCard);
}

/*
 * bankCard(str idCard)
 * 校验是否是合法的银行卡号
 * return 合法的银行卡号 => true;不合法的银行卡号 => false;
 * @author Verdient。
 */
var isbuscode= function(buscode) {
    var reg = /^(\d{13}|\d{15})$/;
    return reg.test(buscode);
}
/*
* email(str email)
* 校验是否是合法的电子邮箱
* return 合法的电子邮箱 => true;不合法的电子邮箱 => false;
* @author Verdient。
*/
var isemail=function(email) {
var reg = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
if (!reg.test(email)) {
    return false;
}
return true;
}

var isnumber=function(textnum){
    var isnum=textnum.replace(/\D/g,'');
    return isnum;
}

//保留小数点2位
var textfix=function(nnumber){
    var newnumber=nnumber.replace(/[^\d.]/g, ""). replace(/^\./g, "").replace(/\.{2,}/g, ".").replace(".", "$#$").replace(/\./g, "").replace("$#$", ".").replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3');
    return newnumber;
}
//给金额加逗号

var fmoney=function(ATR){
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

// var auth = function(_this){
//     if(sessionStorage.getItem('token') == null){
//         _this.$alert(
//             '请先登录',
//             '提示',
//         ).then(() => {
//             window.location.href="/login.html"
//             // _this.$router.push({
//             //     path: '/'
//             // });
//         });
//         return true;
//     }
//     if(sessionStorage.getItem('authentication_status')!=8){
        
//           _this.$alert(
//             '请先进行企业认证',
//             '提示',
//         ).then(() => {
//             _this.$router.push({
//                 path: '/user/enterprise'
//             });
//         });
//         return true;
//     }

// }

export {
    ajax,
    getDataYear,
    getItemStatus,
    getBankImg,
    dragEvent,
    getHistoryStatus,
    smalltobig,
    temptime,
    textfix,
    isIdCard,
    isPhone,
    isBankCard,
    isemail,
    isbuscode,
    fmoney,
    auth,
    isnumber
};
