// const host = 'http://114.55.128.142:81';
// const host = 'http://120.132.124.86:8097';
// const host = 'http://y154u80784.imwork.net:8081/api'
const host = 'http://120.132.21.167:8080'
// const host = 'http://10.5.13.180:8080'
// const host = 'http://api.haipiaohui.com'

import axios from 'axios';


export function fetch(url) {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: host + url,
            headers: {
                Authorization: 'Bearer ' + sessionStorage.getItem('token')
            },
        }).then(response => {
            resolve(response.data);
        });
    })
}

export function post(url, body) {
    return new Promise((resolve, reject) => {
        axios({
            method: 'post',
            url: host + url,
            data: body,
            headers: {
                Authorization: 'Bearer ' + sessionStorage.getItem('token')
            },
        }).then(response => {
            resolve(response.data);
        });
    })
}

//................................................................................
export function fetchRemitList(page = 1, pageSize = 5) {
    return fetch(`/check/enterprise-waiting-list?page=${page}&pageSize=${pageSize}`);
}

export function fetchRemitCode(page = 1, pageSize = 5) {
    return fetch(`/property/waiting-auth-remit-list?page=${page}&pageSize=${pageSize}`);
}

export function fetchRemitHistory(page = 1, pageSize = 5) {
    return fetch(`/check/enterprise-history?page=${page}&pageSize=${pageSize}`);
}
export function activeHistory(page = 1, pageSize = 5) {
    return fetch(`/user/waiting-activate-list?page=${page}&pageSize=${pageSize}`);
}

// .........................................................................

export function fetchReviewHistory(page = 1, pageSize = 5) {
    return fetch(`/check/bill-history?page=${page}&pageSize=${pageSize}`);
}

export function fetchReviewList(page = 1, pageSize = 5) {
    return fetch(`/check/bill-waiting-list?page=${page}&pageSize=${pageSize}`);
}


// .................................................................................

export function fetchUrl(url, page = 1, pageSize = 5){
    return fetch(`${url}?page=${page}&pageSize=${pageSize}`);
}

export function fetchUrl2(url){
    return fetch(`${url}?companyId=${param}`);
}

export function postUrl(url, body){
    return post(`${url}`, body);
}

export function billNoteSigns(){
    return new billSign();
  }
/**
* [获取私钥]
* @param  {[str]} password [密码]
* @param  {[str]} random   [随机数]
* @return {[str]}          [私钥]
*/
export function getPrivatekey(password, random) {
    // console.log(password);
    // console.log(random);
    return billNoteSigns().getPrivatekey(password, random);
}
/**
* [获取公钥]
* @param  {[str]} privatekey [私钥]
* @return {[str]}            [公钥]
*/
export function getPublicKey(privatekey) {
    let privkey_buff = new Buffers(privatekey, "hex");
    return billNoteSigns().getPublicKey(privkey_buff);
}
 /**
   * [获取混合公私钥]
   * @return {[Buffers]} [混合公私钥]
   */
 export function userkey() {
    // console.log(sessionStorage.privateKey);
    // console.log(sessionStorage.publicKey);
    // console.log(sessionStorage.privateKey + sessionStorage.publicKey);
    return new Buffers(sessionStorage.privateKey + sessionStorage.publicKey, 'hex');
  }