<template>
    <div class="tradelist">
        <el-row>
            <el-col :span="24" class="bill-list" style="height:860px;">
                <div class="top-title" style="">
                    <img src="../../assets/images/faxing.png" alt=""> 解冻码管理
                </div>
                <div class="table1">
                    <table>
                        <thead>
                            <tr class="ttop">
                                <th>账号</th>
                                <th>摘牌时间</th>
                                <th>确认收票时间</th>
                                <th>票据信息</th>
                                <th>解冻金额</th>
                                <th>解冻码</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="ttbody">
                                <td>美太投资(苏州)有限公司</td>
                                <td>2016-12-13</td>
                                <td>2016-12-14</td>
                                <td><a style="cursor:pointer;color:#3c8dbe;" @click="dialogTableVisible = true;">查看</a></td>
                                <td>100万</td>
                                <td>ab932</td>
                                <td><a style="cursor:pointer;color:#3c8dbe;" @click="dialogTableVisible2 = true;">未解冻</a></td>
                                <td>选择解冻码</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--   <div class="block">
                    <el-pagination
                      :current-page="5"
                      :page-size="100"
                      layout="total, prev, pager, next"
                      :total="1000">
                    </el-pagination>
                </div> -->
            </el-col>
            <el-dialog title="票据详情" v-model="dialogTableVisible" size="tiny">
                <el-form :model="form">
                    <el-row>
                        <el-col :span='24' :offset="2">
                            <el-form-item label="发票人：">
                                <el-col :span="16">
                                    <el-input value="复杂美"></el-input>
                                </el-col>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-col :span='24' :offset="2">
                            <el-form-item label="承兑方：">
                                <el-col :span="16">
                                    <el-input value="张家口银行"></el-input>
                                </el-col>
                            </el-form-item>
                        </el-col>
                        <el-col :span='24' :offset="2">
                            <el-form-item label="票面金额：">
                                <el-col :span="16">
                                    <el-input value="110万"></el-input>
                                </el-col>
                            </el-form-item>
                        </el-col>
                        <el-col :span='24' :offset="2">
                            <el-form-item label="交易金额：">
                                <el-col :span="16">
                                    <el-input value="100万"></el-input>
                                </el-col>
                            </el-form-item>
                        </el-col>
                </el-form>
            </el-dialog>
                        <el-dialog title="选择解冻码" v-model="dialogTableVisible2" size="tiny">
                <el-form :model="form">
                    <el-row>
                        <el-col :span='12'>
                            解冻金额
                        </el-col>
                        <el-col :span='12'>
                            解冻码
                        </el-col>
                    </el-row>
                    <el-row style='margin-top:20px;'>
                        <el-col :span='12'>
                           <el-radio class="radio" v-model="radio" label="1">备选项</el-radio>
                        </el-col>
                        <el-col :span='12'>
                            100万
                        </el-col>
                    </el-row>
                    <el-row style='margin-top:20px;'>
                        <el-col :span='12'>
                           <el-radio class="radio" v-model="radio" label="1">备选项</el-radio>
                        </el-col>
                        <el-col :span='12'>
                            100万
                        </el-col>
                    </el-row>
                    <el-row style='margin-top:20px;'>
                        <el-col :span='12'>
                           <el-radio class="radio" v-model="radio" label="1">备选项</el-radio>
                        </el-col>
                        <el-col :span='12'>
                            100万
                        </el-col>
                    </el-row>
                </el-form>
            </el-dialog>
        </el-row>
    </div>
</template>
<script>
export default {
    data() {
            return {
                dialogTableVisible: false,
                dialogTableVisible2: false,
            }
        },
        methods: {


        }
};
</script>
<style>
.tradelist {
    width: 100%;
    height: auto;
    overflow: hidden;
    .el-dialog__body {
        text-align: center;
        .el-form-item__label{
            width: 90px;
        }
        .el-checkbox__label {
            color: #e01c1c !important;
        }
        .el-checkbox__inner.is-checked {
            border-color: #1fbae6 !important;
            background-color: #1fbae6 !important;
        }
    }
    .el-dialog--tiny {
        width: 500px;
    }
    .el-dialog__header {
        text-align: center;
    }
    .el-dialog__title {
        font-weight: normal;
    }
    .el-dialog__footer {
        text-align: center;
        button {
            width: 48%;
        }
        .sure-btn {
            background-color: #1fbae6;
            border-color: #1fbae6;
            color: #fff;
        }
        .cancel-btn {
            background-color: transparent;
            color: #333;
            border-color: #C0CCDA;
        }
        .cancel-btn2 {
            background-color: #f25537;
            border-color: #f25537;
            color: #fff;
        }
    }
    .cancel-pp {
        .el-dialog__body {
            /*padding: 4px 0;*/
        }
    }
    .block {
        padding-top: 40px;
        padding-bottom: 20px;
    }
    .el-pagination {
        text-align: right;
    }
    .icon-piao-copy {
        font-size: 36px;
        position: absolute;
        left: 6%;
    }
    .icon-zichan {
        font-size: 24px;
        color: #29b6df;
    }
    .icon-qianbao {
        font-size: 32px;
        position: absolute;
        left: 6%;
    }
    .icon-yue {
        color: #f8bc3b;
    }
    .icon-dongjiezhong {
        color: #de5b42;
    }
    .search-box {
        .el-button {
            background-color: #7166bd;
            border-color: #7166bd;
            color: #fff;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
    }
    .date-box {
        float: right;
        /*.el-icon-time:before{
            content:'';
        }
        .el-date-editor__editor{
            text-align: center;
        }*/
    }
    .el-date-editor__trigger.el-icon {
        color: #1fbae6;
    }
    .block {
        padding-top: 55px;
        padding-bottom: 20px;
    }
    .el-pagination {
        text-align: right;
    }
    .el-pager li.active {
        border-color: #22b8e5;
        background: #22b8e5;
    }
    .recode {
        padding: 30px 20px;
        background: #fff;
    }
    .mylist {
        padding: 30px 20px;
        margin-top: 30px;
        background: #fff;
    }
    .title {
        img {
            float: left;
        }
        p {
            font-size: 16px;
            color: #000;
            margin-left: 30px;
        }
    }
    .seach {
        height: auto;
        overflow: hidden;
        margin-top: 45px;
        margin-bottom: 35px;
        .date {
            float: left;
        }
        .select {
            /*margin-left: 90px;*/
            float: left;
        }
    }
    .nseach {
        max-width: 335px;
        float: right;
    }
    .table1 {
        clear: both;
        width: 100%;
        overflow-x: auto;
        table {
            width: 100%;
            thead {
                width: 100%;
                border-bottom: 1px solid #eee;
                .ttop {
                    width: 100%;
                    th {
                        min-width: 80px;
                        height: 55px;
                        text-align: center;
                        line-height: 55px;
                        color: #000;
                        font-size: 15px;
                        background: #f5f8f7;
                    }
                    th:nth-child(3) {
                        min-width: 120px;
                    }
                }
            }
            tbody {
                width: 100%;
                .ttbody {
                    width: 100%;
                    border-bottom: 1px solid #eee;
                    &:hover {
                        background: #f5f5f5;
                    }
                    ;
                    td {
                        padding: 0!important;
                        min-width: 80px;
                        height: 55px;
                        text-align: center;
                        /*line-height: 55px;*/
                        font-size: 14px;
                        .Investment {
                            cursor: pointer;
                            padding: 5px 10px;
                            background: #22b8e5;
                            color: #fff;
                            border-radius: 4px;
                        }
                    }
                    td:nth-child(3) {
                        min-width: 120px;
                    }
                }
            }
        }
    }
    .bill-list {
        background-color: #fff;
        /*margin-top:30px; */
        padding: 0 30px;
        h2 {
            /*padding:24px 30px 20px;*/
            padding: 24px 0 20px;
            font-size: 16px;
        }
        .el-form {
            /* padding: 0 30px;*/
        }
        .selectedTab {
            background-color: #1fbae6;
            border-color: #1fbae6;
            color: #fff;
        }
    }
    .table2 {
        clear: both;
        width: 100%;
        overflow-x: auto;
        table {
            width: 100%;
            thead {
                width: 100%;
                border-bottom: 1px solid #eee;
                .ttop {
                    width: 100%;
                    th {
                        min-width: 90px;
                        height: 55px;
                        text-align: center;
                        line-height: 55px;
                        color: #000;
                        font-size: 15px;
                        background: #f5f8f7;
                    }
                    th:nth-child(4),
                    th:nth-child(7),
                    th:nth-child(8),
                    th:nth-child(9),
                    th:nth-child(10) {
                        min-width: 70px;
                    }
                    th:nth-child(2),
                    th:nth-child(3),
                    th:nth-child(5) {
                        min-width: 85px;
                    }
                    th:nth-child(1) {
                        min-width: 115px;
                    }
                    th:nth-child(11) {
                        min-width: 200px;
                    }
                }
            }
            tbody {
                width: 100%;
                .ttbody {
                    width: 100%;
                    border-bottom: 1px solid #eee;
                    &:hover {
                        background: #f5f5f5;
                    }
                    ;
                    i {
                        font-style: normal;
                    }
                    td {
                        /*padding: 0!important;*/
                        padding: 0 4px;
                        min-width: 98px;
                        height: 55px;
                        text-align: center;
                        /*line-height: 55px;*/
                        font-size: 14px;
                        span {
                            display: inline-block;
                            width: 88px;
                            cursor: pointer;
                        }
                        span:last-child {
                            margin-left: 10px;
                        }
                        .anniu {
                            padding: 3px 5px;
                            border-radius: 4px;
                            color: #fff;
                        }
                        .get {
                            background: #1fbae6;
                        }
                        .Revoke {
                            display: inline-block;
                            background: #f25537;
                        }
                    }
                    td:nth-child(4),
                    td:nth-child(7),
                    td:nth-child(8),
                    td:nth-child(9),
                    td:nth-child(10) {
                        min-width: 70px;
                    }
                    td:nth-child(2),
                    td:nth-child(3),
                    td:nth-child(5) {
                        min-width: 85px;
                    }
                    td:nth-child(1) {
                        min-width: 115px;
                    }
                    td:nth-child(11) {
                        min-width: 200px;
                    }
                }
            }
        }
    }
}
</style>
