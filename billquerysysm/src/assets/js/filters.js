
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
 * 截断hash
 */
exports.filterHash = e => e.substr(0, 4) + '......' + e.substr(-6);
