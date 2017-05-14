<template>
	<div class="tradelist">
		<el-row>
			<el-col :span="24" class="bill-list" style="height:860px;">
				<div class="top-title" style="">
                    <img src="../../assets/images/qiye.png" alt="">
                    用户资产
                </div>
				<div class="table1">
					<table>
						<thead>
							<tr class="ttop">
								<th>用户编号</th>
								<th>用户名</th>
								<th>企业名</th>
								<th>可用人名币</th>
								<th>冻结人名币</th>
								<th>累计投资</th>
                                <th>累计收益</th>
                                <th>可用票据金额</th>
                                <th>冻结票据</th>
                                <th>累计融资</th>
                                <th>累计贴息</th>
							</tr>
						</thead>
						<tbody>
							<tr class="ttbody">
								<td>1</td>
								<td>admin</td>
								<td>杭州复杂美科技有限公司</td>
								<td>12000</td>
								<td>0</td>
								<td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- <div class="block">
					<el-pagination
				      :current-page="5"
				      :page-size="100"
				      layout="total, prev, pager, next"
				      :total="1000">
				    </el-pagination>
				</div> -->
			</el-col>
        </el-row>
	</div>
</template>
<script>

	export default {
	    data() {
		    return {
		    	manageList:{}
		    }
		},
		mounted: function(){
            this.$nextTick(() => {
           
            })
        },
		methods: {
	    
	    },
  	};
</script>
