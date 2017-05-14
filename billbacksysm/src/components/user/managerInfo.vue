<template>
	<div class="tradelist">
		<el-row>
			<el-col :span="24" class="bill-list" style="height:860px;">
				<div class="top-title" style="">
                    <img src="../../assets/images/qiye.png" alt="">
                    经办人信息
                </div>
				<div class="table1" style="height:591px;">
					<table>
						<thead>
							<tr class="ttop">
								<!-- <th>编号</th> -->
								<th>名称</th>
								<th>身份证号</th>
								<th>手机号</th>
								<th>经办时间</th>
								<th>经办企业</th>
                                <th>项目编号</th>
                                <th>承兑人</th>
                                <th>到期日</th>
                                <th>票面金额</th>
                                <th>状态</th>
							</tr>
						</thead>
						<tbody>
							<tr class="ttbody" v-for="e in manageList">
								<td>{{e.agent_name}}</td>
                                <td>{{e.persion_id}}</td>
                                <td>{{e.mobile}}</td>
                                <td>--</td>
                                <td>--</td>
                                <td>{{e.bill_id}}</td>
                                <td>{{e.acceptor}}</td>
                                <td>{{e.acceptance_at}}</td>
                                <td>{{e.amount}}</td>
                                <td>{{e.bill_status}}</td>
							</tr>
						</tbody>
					</table>
                    <div class="notable-data" v-if="manageListShow">
                        暂无数据
                    </div>
				</div>
				<div class="block">
					<el-pagination
                      :current-page="page.currentPage"
                      :page-size="page.pageSize"
                      layout="total, prev, pager, next"
                      :total="page.total"
                       @current-change="handleCurrentChange">
                    </el-pagination>
				</div>
			</el-col>
        </el-row>
	</div>
</template>
<script>
	import { fetchUrl } from '../../assets/js/api'
	export default {
	    data() {
		    return {
                page:{
                    currentPage:1,
                    pageSize:10,
                    total:0
                },
                body:{
                    page:'1',
                    pageSize:'10',
                    query:'',
                },
		    	manageList:{},
                manageListShow:false,
		    }
		},
		mounted: function(){
            this.$nextTick(() => {
                this.getBill();
            })
        },
		methods: {
	    	getBill: function(){
                fetchUrl(`/agent/admin-list`).then(data => {
                    if(data.count == 0){
                        this.manageListShow = true;
                    }
                    this.page.total = parseInt(data.count);
                    this.manageList = data.data;
                })
	    	},
            handleCurrentChange:function(currentPage){
                this.body.page = currentPage;
                this.getBill();
            }
	    },
	    filters:{
            getDate:function(e){
                return getDataYear('Y-M-D h:m:s',e);
            },
            getStatus: function(e){
                return e == 0 ? '待审核' : '初审通过';
            }
        },
  	};
</script>
