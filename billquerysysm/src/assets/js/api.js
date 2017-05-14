// const host = 'http://192.168.50.109:82'; // dell
// const host = 'https://www.haipingx.com/&_&/8110';
const host ='http://120.132.21.167:8081';
// const host='http://10.5.13.180:8081';
import axios from 'axios';

export function fetch(url) {
    return new Promise((resolve, reject) => {
        axios.get(host + url)
            .then(response => {
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
        }).then(response => {
            resolve(response.data);
        });
    })
}

export function fetchTradeBlock(body) {
    return post(`/billinput/data/blocklist`, body);
}

export function fetchLatestTrade(body) {
    return post('/billinput/data/tranlist', body);
}

// meiyou
export function fetchTopInfo() {
    return fetch('/billinput/data/hinfo');
}

export function postblockDetail(body) {
    return post('/billinput/data/blockdetail', body);
}

export function postTradeDetail(body) {
    return post('/billinput/data/trandetail', body);
}

export function postTradeList(body) {
    return post('/billinput/data/addressdetail', body);
}