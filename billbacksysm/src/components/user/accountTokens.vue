<template>
    <div class="tradelist">
        <el-row class="fun-box" :gutter="30">
            <el-col :span="24" class="left-part mg-bt" :lg="{span:12}">
                <el-row class="num">
                    <el-col :span="8" class="title">币种</el-col>
                    <el-col :span="16" class="container">
                        <el-input v-model="value" value="人民币" disabled></el-input>
                    </el-col>
                </el-row>
                <el-row class="num">
                    <el-col :span="8" class="title">发行数量</el-col>
                    <el-col :span="16" class="container">
                        <el-input v-model="value"></el-input>
                    </el-col>
                </el-row>
                <el-row class="num">
                    <el-col :span="24">
                        <el-button type="primary" class="button">确认发行</el-button>
                    </el-col>
                </el-row>
            </el-col>
            <el-col :span="24" class="right-part mg-bt" :lg="{span:12}">
                <el-row class="num">
                    <el-col :span="10">
                        <div class="label ">
                            <span class="icon icon-qianbao iconfont"></span>
                            <div class="spanclass">
                                <span>币种</span>
                                <br>
                                <span>人民币</span>
                            </div>
                        </div>
                    </el-col>
                    <el-col :offset="4" :span="10">
                        <div class="label ">
                            <span class="icon icon-yue  iconfont"></span>
                            <div class="spanclass">
                                <span>发行数量</span>
                                <br>
                                <span>1,000,000,000</span>
                            </div>
                        </div>
                    </el-col>
                </el-row>
                <el-row class="num">
                    <el-col :span="24">
                        <div class="label ">
                            <span class="icon icon-dongjiezhong iconfont"></span>
                            <div class="spanclass">
                                <span>余额</span>
                                <br>
                                <span>1000,000,000</span>
                            </div>
                        </div>
                    </el-col>
                </el-row>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="24" class="bill-list" style="height:650px;">
                <div class="top-title" style="">
                    <img src="../../assets/images/faxing.png" alt=""> 发行结算代币
                </div>
                <div class="table1">
                    <table>
                        <thead>
                            <tr class="ttop">
                                <th>编号</th>
                                <th>币种</th>
                                <th>发行数量</th>
                                <th>发行时间</th>
                                <th>区块链查询</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="ttbody">
                                <td>1</td>
                                <td>美太投资(苏州)有限公司</td>
                                <td>2016-12-12</td>
                                <td>0x0b71b2ffd32a0af....</td>
                                <td><span class="Investment" @click="invest=true">查询</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </el-col>
        </el-row>
    </div>
</template>
<script>
export default {
    data() {
            return {

            }
        },
        methods: {


        }
};
</script>
<style>
.mg-bt{
    margin-bottom: 30px;
}
.fun-box {
    .left-part {
        .num {
            padding-top: 15px;
            height: 60px;
            background-color: #fff;
            .title {
                font-size: 14px;
                text-align: center;
                height: 36px;
                line-height: 36px;
            }
            .container {
                width: 50%;
            }
            .button {
                text-align: center;
                margin: auto;
                width: 50%;
                background-color: #3a61a2;
            }
        }
    }
    .right-part {
        .num {
            padding-top: 30px;
            height: 90px;
            background-color: #fff;
            .label {
                width: 115px;
                padding-left: 65px;
                text-align: left;
                .spanclass {
                    float: left;
                }
            }
        }
    }
    .num {
        text-align: center;
        font-size: 24px;
        color: #000;
        padding: 0 6%;
        .label {
            font-size: 14px;
        }
    }
    .content {
        height: 200px;
        background-color: #fff;
        padding: 0 6%;
        .line {
            height: 60px;
            line-height: 60px;
            border-bottom: 1px solid #e9e9e9;
            font-size: 14px;
        }
        .line1 {
            height: 75px;
            line-height: 75px;
            font-size: 14px;
            .el-button {
                width: 100%;
                background-color: #23b7e5;
                border-color: #23b7e5;
                color: #fff;
            }
        }
        .label {
            float: left;
            color: #9b9b9b;
        }
        .money-num {
            float: right;
            font-weight: bold;
        }
    }
}


.icon-qianbao {
    font-size: 32px;
    position: absolute;
    left: 8%;
    color: #03bfef;
}
.icon-yue {
    font-size: 32px;
    position: absolute;
    left: 60%;
    color: #00a65a;
}
.icon-dongjiezhong {
    font-size: 32px;
    position: absolute;
    left: 8%;
    color: #f39c11;
}

</style>
