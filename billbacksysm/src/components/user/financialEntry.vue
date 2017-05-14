<template>
    <div class="tradelist2" v-loading.body="listLoading">
        <el-row>
            <el-col :span="24" class="bill-list">
                <div class="top-title" style="">
                    <img src="../../assets/images/ren.png" alt=""> 财务录入
                </div>
                <div class="Enterform">
                    <img src="../../assets/images/tjzh.png" alt="">
                    <div class="group"><span>企业名称</span><input type="text" placeholder="请输入企业名称" /></div>
                    <div class="group"><span>财务录入账号</span><input type="text" placeholder="请输入企业账号"></div>
                    <div class="okbutton">确认提交</div>
                </div>
            </el-col>
        </el-row>
        <el-row style="margin-top:40px;">
            <el-col :span="24" class="bill-list">
                <div class="top-title" style="">
                    <img src="../../assets/images/ren.png" alt=""> 开户历史
                </div>
                <div class="table1" style="">
                    <table>
                        <thead>
                            <tr class="ttop">
                                <th>序号</th>
                                <th>申请企业</th>
                                <th>企业账号</th>
                                <th>开户日期</th>
                            </tr>
                        </thead>
                        <tbody v-loading.body="listHistoryLoading">
                            <tr class="ttbody" v-for="e in history">
                                <td>111</td>
                                <td>11111</td>
                                <td>11111</td>
                                <td>111111</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="notable-data" v-if="reviewHisNodateShow">
                        暂无数据
                    </div>
                </div>
                <div class="block">
                    <el-pagination :current-page="reviewHistoryPage.page" :page-size="reviewHistoryPage.pageSize" layout="total, prev, pager, next" :total="reviewHistoryPage.total" @current-change="handleHistoryCurrentChange">
                    </el-pagination>
                </div>
            </el-col>
        </el-row>
    </div>
</template>
<script>
import { fetchReviewHistory, fetchReviewList, postListInfo, postCheckedInfo, postHistoryInfo, postUrl, fetch } from '../../../src/assets/js/api'
export default {
    data() {
            return {
                // 票据信息审核里的票据编号
                bill_number:'',
                // listLoading: true,
                listLoading: false,
                // 录入审核分页
                reviewListPage: {
                    page: 1,
                    pageSize: 10,
                    total: 0,
                },
                swiperOption:{
                    pagination: '.swiper-pagination',
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                    slidesPerView: 1,
                    slidesPerColumn: 1,
                    paginationClickable: true,
                },
                swiperSlides2: [],
                // 审核历史分页
                reviewHistoryPage: {
                    page: 1,
                    pageSize: 5,
                    total: 0
                },
                // 录入审核列表
                bill: {},
                // 审核历史列表
                history: {},
                // 录入审核-票据信息checkbox集合
                checked: {
                    checked1: false,
                    checked2: false,
                    checked3: false,
                    checked4: false,
                    checked5: false,
                    checked6: false,
                    checked7: false,
                    checked8: false,
                    checked9: false,
                },
                // 审核历史-票据信息checkbox集合
                checked2: {
                    checked1: false,
                    checked2: false,
                    checked3: false,
                    checked4: false,
                    checked5: false,
                    checked6: false,
                    checked7: false,
                    checked8: false,
                    checked9: false,
                },
                // 企业信息， 暂无接口
                enterInfo: {},
                // 录入审核-票据信息弹框数据                 
                billReviewInfo: {},
                // 审核历史-票据信息弹框数据 
                billHistoryInfo: {},
                // 录入审核暂无数据显示
                reviewListNodatashow: false,
                // 审核历史暂无数据显示
                reviewHisNodateShow: false,
                // 票据信息审核字段勾选状态
                checkList: {
                    // drawer: 0,
                    // face_amount: 0,
                    // acceptor: 0,
                    // acceptance_at: 0,
                },
                // 弹框显示
                dialogTableVisible: false,
                dialogTableVisible1: false,
                dialogTableVisible2: false,
                // 分页加载动画
                listReviewLoading: false,
                listHistoryLoading: false,
            }
        },
        mounted: function() { 
            this.$nextTick(() => {
                this.initData();
            })
        },
        methods: {
            initData() {
                // 为了在最慢的接口调用完成后再消失动画，用了promise.all方法并行执行ajax
                Promise.all([fetchReviewHistory(), fetchReviewList()]).then(data => {
                    this.listLoading = false;
                    this.reviewHisNodateShow = data[0].count == 0 ? true : false;
                    this.reviewHistoryPage.total = data[0].count;
                    this.history = data[0].data;
                    this.reviewListNodatashow = data[1].count == 0 ? true : false;
                    this.reviewListPage.total = data[1].count;
                    this.bill = data[1].data;
                });
                // this.getReviewList();
            },
            /**
             * [获取审核历史列表]
             */
            getReviewHistory() {
                this.listHistoryLoading = true;
                fetchReviewHistory(this.reviewHistoryPage.page, this.reviewHistoryPage.pageSize).then(data => {
                    this.reviewHisNodateShow = data.count == 0 ? true : false;
                    this.reviewHistoryPage.total = data.count;
                    this.history = data.data;
                    this.listHistoryLoading = false;
                });
            },
            /**
             * [获取录入审核列表]
             */
            getReviewList() {
                this.listReviewLoading = true;
                fetchReviewList(this.reviewListPage.page, this.reviewListPage.pageSize).then(data => {
                    this.reviewListNodatashow = data.count == 0 ? true : false;
                    this.reviewListPage.total = data.count;
                    this.bill = data.data;
                    this.listReviewLoading = false;
                });
            },
            /**
             * [点击录入审核列表的票据信息查看]
             */
            getListInfo(e) {
                this.billReviewInfo.electronic_bill = 'http://ofn881vu0.bkt.clouddn.com/imgload.gif';
                this.dialogTableVisible1 = true;
                fetch(`/bill/info?id=${e}`).then(data => {
                    this.billReviewInfo = data;
                    this.swiperSlides2 = [{img:data.bill_front_path},{img:data.bill_back_path}];
                    this.checked.checked1 = data.drawer_status == 1 ? true : false;
                    this.checked.checked2 = data.face_amount_status == 1 ? true : false;
                    this.checked.checked3 = data.acceptor_status == 1 ? true : false;
                    this.checked.checked4 = data.acceptance_at_status == 1 ? true : false;
                    this.checked.checked5 = data.acceptor_type_status == 1 ? true : false;
                    this.checked.checked6 = data.type_status == 1 ? true : false; 
                    this.checked.checked7 = data.taker_status == 1 ? true : false;
                    this.checked.checked8 = data.bill_number_status == 1 ? true : false;
                    this.checked.checked9 = data.issue_at_status == 1 ? true : false;  
                });
            },
            /**
             * [点击审核历史列表的票据信息查看]
             */
            getCheckInfo2(e) {
                this.billHistoryInfo.electronic_bill = 'http://ofn881vu0.bkt.clouddn.com/imgload.gif';
                this.dialogTableVisible2 = true;
                fetch(`/check/bill-history-info?id=${e}`).then(data => {
                    this.billHistoryInfo = data;
                    this.swiperSlides2 = [{img:data.bill_front_path},{img:data.bill_back_path}];
                    this.checked2.checked1 = data.drawer_status == 1 ? true : false;
                    this.checked2.checked2 = data.face_amount_status == 1 ? true : false;
                    this.checked2.checked3 = data.acceptor_status == 1 ? true : false;
                    this.checked2.checked4 = data.acceptance_at_status == 1 ? true : false; 
                    this.checked2.checked5 = data.acceptor_type_status == 1 ? true : false;
                    this.checked2.checked6 = data.type_status == 1 ? true : false;   
                    this.checked2.checked7 = data.taker_status == 1 ? true : false;
                    this.checked2.checked8 = data.bill_number_status == 1 ? true : false;
                    this.checked2.checked9 = data.issue_at_status == 1 ? true : false;  
                });
            },
            /**
             * [提交票据审查信息]
             */
            submitCheckedInfo(e) {
                this.checkList.drawer = this.checked.checked1 == false ? 0 : 1;
                this.checkList.face_amount = this.checked.checked2 == false ? 0 : 1;
                this.checkList.acceptor = this.checked.checked3 == false ? 0 : 1;
                this.checkList.acceptance_at = this.checked.checked4 == false ? 0 : 1;
                this.checkList.acceptor_type = this.checked.checked5 == false ? 0 : 1;
                this.checkList.type = this.checked.checked6 == false ? 0 : 1;
                this.checkList.taker = this.checked.checked7 == false ? 0 : 1;
                this.checkList.bill_number = this.checked.checked8 == false ? 0 : 1;
                this.checkList.issue_at = this.checked.checked9 == false ? 0 : 1;
                // if(type = 1) this.checkList.type = 1;
                postUrl(`/check/bill`, {bill_id: e, field_status: this.checkList}).then(data => {
                    if (data.code == 200) {
                        this.$notify({
                            title: '提示',
                            message: '操作成功',
                            type: 'success'
                        });
                        this.getReviewList();
                        this.getReviewHistory();
                        this.dialogTableVisible1 = false;
                    } else {
                        this.$notify({
                            title: '提示',
                            message: data.message,
                            type: 'warning'
                        });
                    }
                });
            },
            /**
             * [全选审核信息]
             */
            checkAll() {
                this.checked = {
                    checked1: true,
                    checked2: true,
                    checked3: true,
                    checked4: true,
                    checked5: true,
                    checked6: true,
                    checked7: true,
                    checked8: true,
                    checked9: true,
                }
            },
            /**
             * [录入审核分页]
             */
            handleListCurrentChange(currentPage) {
                this.reviewListPage.page = currentPage;
                this.getReviewList();
            },
            /**
             * [审核历史分页]
             */
            handleHistoryCurrentChange(currentPage) {
                this.reviewHistoryPage.page = currentPage;
                this.getReviewHistory();
            }
        }
};
</script>
<style>

.tradelist2 {
    .Enterform{
        width: 378px;
        height: 400px;
        margin: 0 auto;
        img{
            width: 78px;
            height: 77px;
            margin: 30px 0 0 150px;
        }
        .group{
            margin-top: 20px;
            span{
                display: inline-block;
                width: 100px;
                text-align: right;
                font-size: 14px;
            }
            input{
                width: 256px;
                border: 1px solid #eee;
                border-radius: 4px;
                height: 36px;
                line-height: 36px;
                margin-left: 15px;
                padding-left: 10px;
            }
        }
        .okbutton{
            width: 256px;
            margin-top: 20px;
            border-radius: 4px;
            background: rgb(57,96,161);
            color: #fff;
            font-size: 14px;
            text-align: center;
            line-height: 36px;
            height: 36px;
            margin-left: 115px;
        }
    }
    .el-form-item__label {
        width: 120px !important;
    }
    .el-dialog {
        top: 5% !important;
        z-index: 3000 !important;
    }
    @media (max-width: 1000px) {
        .el-dialog {
            top: 20% !important;
        }
    }
    .rowb {
        padding: 20px 0;
        border-bottom: 1px solid #ececec;
    }

    .el-dialog__body {
        text-align: center;
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
}
</style>
