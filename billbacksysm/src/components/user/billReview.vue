<template>
    <div class="tradelist2" v-loading.body="listLoading">
        <el-row>
            <el-col :span="24" class="bill-list">
                <div class="top-title" style="">
                    <img src="../../assets/images/ren.png" alt=""> 录入审核
                </div>
                <div class="table1">
                    <table >
                        <thead>
                            <tr class="ttop">
                                <th>申请企业</th>
                                <th>经办人/电话</th>
                                <th>申请时间</th>
                                <th>状态</th>
                                <th>票据审核</th>
                            </tr>
                        </thead>
                        <tbody v-loading.body="listReviewLoading">
                            <tr class="ttbody" v-for="e in bill">
                                <td>{{e.enterprise_name}}</td>
                                <td>
                                    {{e.agent_name}}
                                    <p>{{e.agent_mobile}}</p>
                                </td>
                                <td>{{e.apply_at}}</td>
                                <td>待初审</td>
                                <td><a style="cursor:pointer;color:#3c8dbe;" @click="getListInfo(e.id)">查看</a></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="notable-data" v-if="reviewListNodatashow">
                        暂无数据
                    </div>
                </div>
                <div class="block">
                    <el-pagination :current-page="reviewListPage.page" :page-size="reviewListPage.pageSize" layout="total, prev, pager, next" :total="reviewListPage.total" @current-change="handleListCurrentChange">
                    </el-pagination>
                </div>
            </el-col>
        </el-row>
        <el-row style="margin-top:40px;">
            <el-col :span="24" class="bill-list">
                <div class="top-title" style="">
                    <img src="../../assets/images/ren.png" alt=""> 审核历史
                </div>
                <div class="table1" style="">
                    <table>
                        <thead>
                            <tr class="ttop">
                                <th>序号</th>
                                <th>申请企业</th>
                                <th>申请时间</th>
                                <th>审核时间</th>
                                <th>状态</th>
                                <th>票据审核</th>
                            </tr>
                        </thead>
                        <tbody v-loading.body="listHistoryLoading">
                            <tr class="ttbody" v-for="e in history">
                                <td>{{e.id}}</td>
                                <td>{{e.applicant}}</td>
                                <!-- <td>
                                    {{e.agent_name}}
                                    <p>{{e.agent_mobile}}</p>
                                </td> -->
                                <!-- <td>{{e.checker_name}}</td> -->
                                <td>{{e.apply_at}}</td>
                                <td>{{e.created_at}}</td>
                                <td>{{e.status}}</td>
                                <td><a style="cursor:pointer;color:#3c8dbe;" @click="getCheckInfo2(e.id)">查看</a></td>
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
        <el-dialog title="企业信息" v-model="dialogTableVisible">
            <el-form :model="form">
                <el-row>
                    <el-col :span='12'>
                        <el-form-item label="企业名称：">
                            <el-col :span="16">
                                <el-input v-model="enterInfo.orgName" disabled></el-input>
                            </el-col>
                        </el-form-item>
                    </el-col>
                    <el-col :span='12'>
                        <el-form-item label="经办人姓名：">
                            <el-col :span="16">
                                <el-input v-model="enterInfo.agent_name" disabled></el-input>
                            </el-col>
                        </el-form-item>
                    </el-col>
                    <el-col :span='12'>
                        <el-form-item label="企业所在地：">
                            <el-col :span="16">
                                <el-input v-model="enterInfo.address" disabled></el-input>
                            </el-col>
                        </el-form-item>
                    </el-col>
                    <el-col :span='12'>
                        <el-form-item label="经办身份证号：">
                            <el-col :span="16">
                                <el-input v-model="enterInfo.agent_persion_id" disabled></el-input>
                            </el-col>
                        </el-form-item>
                    </el-col>
                    <el-col :span='12'>
                        <el-form-item label="营业执照：">
                            <el-col :span="16">
                                <el-input v-model="enterInfo.orgCode" disabled></el-input>
                            </el-col>
                        </el-form-item>
                    </el-col>
                    <el-col :span='12'>
                        <el-form-item label="经办人手机号：">
                            <el-col :span="16">
                                <el-input v-model="enterInfo.agent_mobile" disabled></el-input>
                            </el-col>
                        </el-form-item>
                    </el-col>
                    <el-col :span='12'>
                        <el-form-item label="开户银行：">
                            <el-col :span="16">
                                <el-input v-model="enterInfo.openningBank" disabled></el-input>
                            </el-col>
                        </el-form-item>
                    </el-col>
                    <el-col :span='12'>
                        <el-form-item label="银行卡号：">
                            <el-col :span="16">
                                <el-input v-model="enterInfo.agent_bank_card_number" disabled></el-input>
                            </el-col>
                        </el-form-item>
                    </el-col>
                    <el-col :span='12'>
                        <el-form-item label="法人姓名：">
                            <el-col :span="16">
                                <el-input v-model="enterInfo.legalPerson" disabled></el-input>
                            </el-col>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
        </el-dialog>
        <el-dialog title="票据信息审核" v-model="dialogTableVisible1">
            <el-form :model="form">
                <el-row>
                    <el-col :push="16" :span='8'>
                        <span @click="checkAll" style="cursor:pointer;">全选</span>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        票据类型
                    </el-col>
                    <el-col :span='8'>
                        {{billReviewInfo.type}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox  v-model="checked.checked6"></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        票据编号
                    </el-col>
                    <el-col :span='8'>
                        {{billReviewInfo.bill_number}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox  v-model="checked.checked8"></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        出票人
                    </el-col>
                    <el-col :span='8'>
                        {{billReviewInfo.drawer}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox v-model="checked.checked1"></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        收票人
                    </el-col>
                    <el-col :span='8'>
                        {{billReviewInfo.taker}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox  v-model="checked.checked7"></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        承兑人
                    </el-col>
                    <el-col :span='8'>
                        {{billReviewInfo.acceptor}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox v-model="checked.checked3"></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        承兑人类型
                    </el-col>
                    <el-col :span='8'>
                        {{billReviewInfo.acceptor_type}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox v-model="checked.checked5"></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        出票日
                    </el-col>
                    <el-col :span='8'>
                        {{billReviewInfo.issue_at}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox  v-model="checked.checked9"></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        到期日
                    </el-col>
                    <el-col :span='8'>
                        {{billReviewInfo.acceptance_at}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox v-model="checked.checked4"></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        票据金额
                    </el-col>
                    <el-col :span='8'>
                        {{billReviewInfo.face_amount}}元
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox v-model="checked.checked2"></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='22' :push="1">
                        <!-- <div class="" style="">
                            <img style="max-width:100%;max-height:400px;" :src="billReviewInfo.bill_front_path" alt="图片出错">
                        </div> -->
                        <swiper :options="swiperOption">
                            <swiper-slide v-for="slide in swiperSlides2">
                                <div>
                                    <img :src="slide.img" alt="" style="max-width:100%;max-height:400px;margin-top:20px;">
                                </div>
                            </swiper-slide>
                              <div class="swiper-button-prev" slot="button-prev"></div>
                              <div class="swiper-button-next" slot="button-next"></div>
                        </swiper>
                    </el-col>
                </el-row>
            </el-form>
            <div slot="footer">
                <el-button style="background-color:#3c619f;border:0" type="primary" @click="submitCheckedInfo(billReviewInfo)">提交</el-button>
            </div>
        </el-dialog>
        <el-dialog title="票据信息审核" v-model="dialogTableVisible2">
            <el-form :model="form">
                <el-row>
                    <el-col :push="16" :span='8'>
                    </el-col>
                </el-row>
                  <el-row class="rowb">
                    <el-col :span='8'>
                        票据类型
                    </el-col>
                    <el-col :span='8'>
                        {{billHistoryInfo.type}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox v-model="checked2.checked6" disabled></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        票据编号
                </el-col>
                    <el-col :span='8'>
                        {{billHistoryInfo.bill_number == null ? '--':billHistoryInfo.bill_number}}
                    </el-col>
                    <el-col :push="4" :span='8'><el-checkbox v-model="checked2.checked8" disabled></el-checkbox></el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        出票人
                    </el-col>
                    <el-col :span='8'>
                        {{billHistoryInfo.drawer}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox v-model="checked2.checked1" disabled></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        收票人
                    </el-col>
                    <el-col :span='8'>
                        {{billHistoryInfo.taker}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox v-model="checked2.checked7" disabled></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        承兑人
                    </el-col>
                    <el-col :span='8'>
                        {{billHistoryInfo.acceptor}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox v-model="checked2.checked3" disabled></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        承兑人类型
                    </el-col>
                    <el-col :span='8'>
                        {{billHistoryInfo.acceptor_type}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox v-model="checked2.checked5" disabled></el-checkbox>
                    </el-col>
                </el-row>
                <el-row class="rowb">
                    <el-col :span='8'>
                        出票日
                </el-col>
                    <el-col :span='8'>
                        {{billHistoryInfo.issue_at}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox v-model="checked2.checked9" disabled></el-checkbox>
                    </el-col>
                </el-row>
                 <el-row class="rowb">
                    <el-col :span='8'>
                        到期日
                </el-col>
                    <el-col :span='8'>
                        {{billHistoryInfo.issue_at}}
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox v-model="checked2.checked4" disabled></el-checkbox>
                    </el-col>
                </el-row>
                 <el-row class="rowb">
                    <el-col :span='8'>
                        票据金额
                    </el-col>
                    <el-col :span='8'>
                        {{billHistoryInfo.face_amount}}元
                    </el-col>
                    <el-col :push="4" :span='8'>
                        <el-checkbox v-model="checked2.checked2" disabled></el-checkbox>
                    </el-col>
                </el-row>
              
                <el-row class="rowb img">
                    <!-- <el-col :span='22' :push="1">
                        <div class="" style="">
                            <img  style="max-width:100%;max-height:400px;" :src="billHistoryInfo.electronic_bill" alt="图片出错">
                        </div>
                    </el-col> -->
                    <swiper :options="swiperOption">
                        <swiper-slide v-for="slide in swiperSlides2">
                            <div>
                                <img :src="slide.img" alt="" style="max-width:100%;max-height:400px;margin-top:20px;">
                            </div>
                        </swiper-slide>
                          <div class="swiper-button-prev" slot="button-prev"></div>
                          <div class="swiper-button-next" slot="button-next"></div>
                    </swiper>
                </el-row>
            </el-form>
            <div slot="footer">
            </div>
        </el-dialog>
    </div>
</template>
<script>
import { fetchReviewHistory, fetchReviewList, postListInfo, postCheckedInfo, postHistoryInfo, postUrl, fetch,billNoteSigns,userkey } from '../../../src/assets/js/api'
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
                var billface_amount=e.face_amount*100;
                e.possessor=e.possessor.toString();
                var billtype=e.type == '银行汇票' ? 1 : 2;
                console.log(userkey());
                console.log(e.possessor_address);
                console.log(typeof(e.possessor));
                console.log(billtype);
                console.log(e.bill_number);
                console.log(e.drawer);
                console.log(e.taker);
                console.log(e.acceptor);
                console.log(e.acceptance_at);
                console.log(e.face_amount);
                console.log(billface_amount);
                let result = billNoteSigns().signCreateNote(userkey(), e.possessor_address,e.possessor, billtype, e.bill_number,e.drawer, e.taker, e.acceptor, Date.parse(new Date(e.acceptance_at)) / 1000, billface_amount,'无');
                postUrl(`/check/bill`, {bill_id: e.id, field_status: this.checkList,instruction_id:result.sid,signature:result.signdata}).then(data => {
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
