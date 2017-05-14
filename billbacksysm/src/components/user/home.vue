<template>
    <div class="userhome-box">
        <el-row class="fun-box" :gutter="20">
            <el-col :span="6">
                <div class="home-tab">
                    <span class="info-box-icon bg-aqua"></span>
                    <div class="info-box-content">
                        <span class="info-box-text">实名用户（人）</span>
                        <span class="info-box-number">{{index.realNname}}</span>
                    </div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="home-tab">
                    <span class="info-box-icon bg-green"></span>
                    <div class="info-box-content">
                        <span class="info-box-text">票据数量（张）</span>
                        <span class="info-box-number" style="color:#00a65a">{{index.billNum}}</span>
                    </div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="home-tab">
                    <span class="info-box-icon bg-yellow"></span>
                    <div class="info-box-content">
                        <span class="info-box-text">票据金额（万元）</span>
                        <span class="info-box-number" style="color:#f39c12">{{index.billAmount}}</span>
                    </div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="home-tab">
                    <span class="info-box-icon bg-red"></span>
                    <div class="info-box-content">
                        <span class="info-box-text">累计收益（万元）</span>
                        <span class="info-box-number" style="color:#dd4b39">{{index.profit}}</span>
                    </div>
                </div>
            </el-col>
        </el-row>
        <el-row class="info-box">
            <el-col :span="6">
                <span style="font-size:36px;color:#666;">{{tjdata.ticke_cs}}</span>
                <p style="color:#666;margin-top:10px;">票据初审</p>
            </el-col>
            <el-col :span="6">
                <span style="font-size:36px;color:red;">{{tjdata.ptcc}}</span>
                <p style="color:#666;margin-top:10px;">平台催促</p>
            </el-col>
            <el-col :span="6">
                <span style="font-size:36px;color:green;">{{tjdata.jfcl}}</span>
                <p style="color:#666;margin-top:10px;">纠纷处理</p>
            </el-col>
            <el-col :span="6">
                <span style="font-size:36px;color:green;">{{tjdata.xytz}}</span>
                <p style="color:#666;margin-top:10px;">信用调整</p>
            </el-col>
        </el-row>
        <el-row :gutter="30">
            <el-col :span="12">
                <div style="margin-top:45px">
                    <el-card class="box-card">
                        <div id="dtt" style="width:100%;height:300px">
                        </div>
                    </el-card>
                </div>
            </el-col>
            <el-col :span="12">
                <div style="margin-top:45px">
                    <el-card class="box-card">
                        <div id="zx" style="width:100%;height:300px">
                        </div>
                    </el-card>
                </div>
            </el-col>
        </el-row>
    </div>
</template>
<script>
import echarts from "echarts"
import {fetch} from '../../assets/js/api'
export default {
    data() {
            return {
                index:{},
                tjdata: {
                    ticke_cs: "10",
                    ptcc: "10",
                    jfcl: "1",
                    xytx: "1",
                    xytz: '2'
                }
            }
        },
        mounted: function() {
            this.$nextTick(() => {
                // this.billTotal();
                // this.registeredTotal();
                // this.countIndex();
            })
        },
        methods: {
            /* 主要信息统计 */
            countIndex: function() {
                fetch(`/count/index`).then(data => {
                    this.index = data;
                });
            },
            /* 获取票据金额信息 */
            billTotal: function() {
                fetch(`/count/bill-total`).then(data => {
                    let time = [];
                    let count = [];
                    for (let x in data.data) {
                        time.push(x);
                        count.push(data.data[x]);
                    }
                });
            },
            /* 获取注册信息统计 */
            registeredTotal: function() {
                fetch(`/count/bill-total`).then(data => {
                    let time = [];
                    let count = [];
                    for (let x in data.data) {
                        time.push(x);
                        count.push(data.data[x]);
                    }
                    this.initRegisterChart(time, count);
                });
            },
            /* 初始化票据金额图表 */
            initCountChart: function(time, count) {
                var myChart = echarts.init(document.getElementById('zx'));
                myChart.setOption({
                    color: ['#3c8dbc'],
                    title: {
                        text: '票据金额'
                    },
                    tooltip: {
                        trigger: 'axis'
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: time
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [{
                        name: '票据金额',
                        type: 'line',
                        stack: '总量',
                        data: count
                    }]
                });
            },
            /* 初始化注册人数图表 */
            initRegisterChart: function(time,count){
                var myChart = echarts.init(document.getElementById('dtt'));
                myChart.setOption({
                    color: ["#00c0ef"],
                    title: {
                        text: '注册人数'
                    },
                    tooltip: {},
                    legend: {
                        borderColor: '#fefefe',
                        shadowBlur: '0'
                    },
                    grid: {
                        top: '50px',
                        left: '35px',
                        bottom: '40px',
                        right: '20px',
                        borderColor: '#fefefe',
                        shadowBlur: '0'
                    },
                    xAxis: {
                        data: time,
                        position: 'bottom',
                        axisLine: {
                            lineStyle: {
                                color: '#ccc'
                            }
                        }
                    },
                    yAxis: [{
                        position: 'left',
                        axisLine: {
                            lineStyle: {
                                color: '#ccc'
                            }
                        }
                    }],
                    series: [ {
                        name: 'Digital Goods',
                        type: 'bar',
                        data: count
                    }]
                });
            }
        }
}
</script>
<style>

.fun-box {
    bg: #f3f3f3;
    .home-tab {
        bg: #fff;
        h: 140px;
        p: 25px;
        box-shadow: #999 0.5px 0.5px 2px;
        .info-box-icon {
            float: left;
            height: 80px;
            width: 80px;
        }
        .bg-aqua {
            background-color: #00c0ef !important;
            background: url(../../assets/images/userHome/shiming.png) no-repeat center center;
        }
        .bg-green {
            background-color: #00a65a !important;
            background: url(../../assets/images/userHome/piao.png) no-repeat center center;
        }
        .bg-yellow {
            background-color: #f39c12 !important;
            background: url(../../assets/images/userHome/jinge.png) no-repeat center center;
        }
        .bg-red {
            background-color: #dd4b39!important;
            background: url(../../assets/images/userHome/shouyi.png) no-repeat center center;
        }
        .info-box-content {
            padding: 5px 10px;
            margin-left: 90px;
            .info-box-text {
                display: block;
                font-size: 14px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                c: #666;
            }
            .info-box-number {
                display: block;
                c: #00c0ef;
                pt: 25px;
                fz: 20px;
                fw: 700;
            }
        }
    }
}

.info-box {
    p: 25px;
    bg: #fff;
    mt: 45px;
    .el-col {
        h: 90px;
        border-right: #e6e6e6 1px solid;
        text-align: center;
        P {
            font-size: 14px;
        }
    }
}

.box-card {
    border-top: 3px #00c0ef solid;
    .el-card__body {
        p: 0px;
    }
}
</style>
