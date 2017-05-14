<template>
    <div class="tradelist">
        <el-row>
            <el-col :span="24" class="bill-list">
                <div class="top-title" style="">
                    <img src="../../assets/images/ren.png" alt="">
                    交易进程
                </div>
                <div class="table1" style="height:591px;">
                    <table>
                        <thead>
                            <tr class="ttop">
                                <th>编号/项目</th>
                                <th>承兑企业</th>
                                <th>到期日</th>
                                
                                <th>票面金额</th>
                                <th>挂牌企业</th>
                                <th>转让金额</th>
                                <th>贴息率</th>
                                <th>摘牌企业</th>
                                <th>当前状态</th>
                                <th>协议</th>
                                <th>联系方式</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="ttbody" v-for="e in process">
                                <td>{{e.id}}/{{e.name}}</td>
                                <td>{{e.acceptance_at | getDate}}</td>
                                <td>{{e.left_day}}天</td>
                                <td>{{e.amount}}</td>
                                <td>{{e.creator}}</td>
                                <td>{{e.financing_amount}}</td>
                                <td>{{e.annualized_rate | filterAnnulizedRate}}</td>
                                <td>{{e.investor}}</td>
                                <td>{{e.status | filterStatus}}</td>
                                <td>无</td>
                                <td><a style = "color:#3c8dbe">查看</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="block">
                    <el-pagination
                      :current-page="body.page"
                      :page-size="body.pageSize"
                      layout="total, prev, pager, next"
                      :total="body.count"
                       @current-change="handleCurrentChange">
                    </el-pagination>
                </div>
            </el-col>
        </el-row>
    </div>
</template>
<script>
    import { fetchUrl } from '../../../src/assets/js/api'
    export default {
        data() {
            return {
                body:{
                    page:'1',
                    pageSize:'10',
                    count:0,
                    query:'',
                },
                process:{}
            }
        },
        mounted: function(){
            this.$nextTick(() => {
                this.getprocess();
            })
        },
        filters:{
            getDate:function(e){
                return getDataYear('Y-M-D',e);
            },
            filterStatus:function(e){
                return getItemStatus(e);
            }
        },
         methods: {
            getprocess: function(){
                fetchUrl(`/trade/transaction-process`).then(data => {
                    this.body.count = data.count;
                    this.process = data.data; 
                });
            },
            handleCurrentChange:function(currentPage){
                this.body.page = currentPage;
                this.getprocess();
            }
        }
    };
</script>
