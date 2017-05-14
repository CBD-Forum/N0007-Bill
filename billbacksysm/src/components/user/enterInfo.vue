<template>
	<div class="tradelist">
		<el-row>
			<el-col :span="24" class="bill-list" style="height:860px;">
				<div class="top-title" style="">
                    <img src="../../assets/images/qiye.png" alt="">
                    用户信息
                </div>
				<div class="table1" style="height:591px;">
					<table>
						<thead>
							<tr class="ttop">
								<th>用户编号</th>
                                <th>用户名</th>
								<th>企业名称</th>
								<th>组织结构代码证</th>
								<th>城市</th>
								<th>授权人</th>
                                 <!--  人/手机 -->
								<th>公钥地址</th>
                                <th>实名认证</th>
                                <!-- 已通过 未通过 -->
								<th>开户状态</th>
                                <!-- 已开户 待开户 -->
                                <th>中信账户</th>
                                
							</tr>
						</thead>
						<tbody>
							<tr class="ttbody" v-for="item in enterInfoList">
								<td>{{item.id}}</td>
                                <td>{{item.username}}</td>
								<td>{{item.name}}</td>
								<td>{{item.business_license_id}}</td>
								<td>{{item.legal_person_name}}</td>
								<td>{{item.legal_person_id}}</td>
								<td>查看</td>
								<td>{{item.public_key}}</td>
                                <td>已认证</td>
                                <td>{{item.register_time| getDate}}</td>
							</tr>
						</tbody>
					</table>
                    <div class="notable-data" v-if="enterInfoListShow">
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
import {fetchUrl} from '../../../src/assets/js/api'
	export default {
	    data() {
		    return {
		    	token:sessionStorage.getItem('token'),
                uid:sessionStorage.getItem('userId'),
                invest: false,
                //登录ip
                enterInfoList:[],
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
                enterInfoListShow:false,
		    }
		},
		mounted: function () {
		    this.$nextTick(function () {
		    	this.enterInfolist();
		    })
		 },
		 filters:{
            getDate:function(e){
                return getDataYear('Y-M-D',e);
            }
        },
		 methods: {
	        //企业信息列表
	        enterInfolist:function(){
	        	fetchUrl('/enterprise/admin-list', this.body.page, this.body.pageSize).then(data => {
        		 	if(data.count == 0){
                        this.enterInfoListShow == true;
                    }
                    this.page.total = data.count;
                    this.enterInfoList=data.data;
	        	});
	        },
	        handleCurrentChange:function(currentPage){
                this.body.page = currentPage;
                this.enterInfolist();
            }
	      
	    }
  	};
</script>
