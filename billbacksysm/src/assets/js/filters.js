/*
 * 时间格式化
 */
exports.filterTime = (timestamp,format) => {
    let date = new Date(timestamp ? (parseInt(timestamp)*1000) : new Date().getTime());
    let FORMAT = new Object();
    FORMAT = {
        'Y': "date.getFullYear()",
        'M': "date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1",
        'D': "date.getDate() < 10 ? '0' + date.getDate() : date.getDate()",
        'h': "date.getHours() < 10 ? '0' + date.getHours() : date.getHours()",
        'm': "date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()",
        's': "date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds()"
    }
    for (let i in FORMAT) {
        if (format.indexOf(i) != -1) {
            format = format.replace(i, eval(FORMAT[i]));
        }
    }
    return format;
}

/*
 * 金额格式化
 */
exports.filterAmount = e => e == null ? '--' : e; 

/*
 * 票据类型格式化
 */
exports.filterBill = e => e == 1 ? '银行汇票' : '商业汇票'; 

/*
 * 操作格式化
 */
exports.filterType = e => {
    const map = {
        // '1':'审核成功',
        // '2':'审核失败',
        '1':'审核通过',
        '2':'审核不通过',
        '3':'打款成功',
        '4':'打款失败'
    }
    return map[e];
}; 

/*
 * 承兑人类型
 */
exports.acceptorType = e => {
    console.log(e);
    const map = {
        '0':'企业',
        '1':'国股',
        '2':'城商',
        '3':'农商',
        '4':'外资',
        '5':'农信',
        '6':'财务公司'
    }
    return map[e];
}; 