<template>
	<div class="tradelist">
		<el-row>
			<el-col :span="24" class="bill-list" style="height:860px;">
				<div class="top-title" style="">
                    <img src="../../assets/images/qiye.png" alt="">
                    用户权限
                </div>
				<div class="table1">
					<table>
						<thead>
							<tr class="ttop">
								<th>用户编号</th>
								<th>用户名</th>
								<th>企业名</th>
								<th>登录</th>
								<th>交易</th>
								<th>提款</th>
                                <th>保证金</th>
                                <th>备注</th>
                                <th>权限调整记录</th>
							</tr>
						</thead>
						<tbody>
							<tr class="ttbody">
				                <td>1</td>
                                <td>ling</td>
                                <td>杭州复杂美科技有限公司</td>
                                <td><span class="warning">冻结</span></td>
                                <td><span class="warning">冻结</span></td>
                                <td><span class="warning">冻结</span></td>
                                <td>0</td>
                                <td>---</td>
                                <td><a style="color:#3c8dbe;">查看</a></td>
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
	</div>
</template>
<script>
	// import { ajax,getDataYear } from '../../assets/js/common-js'

	// export default {
	//     data() {
	// 	    return {
	// 	    	manageList:{}
	// 	    }
	// 	},
	// 	mounted: function(){
 //            this.$nextTick(() => {

 //            })
 //        },
	// 	methods: {
	    
	//     },
	//     filters:{
 //            getDate:function(e){
 //                return getDataYear('Y-M-D h:m:s',e);
 //            },
 //            getStatus: function(e){
 //                return e == 0 ? '待审核' : '初审通过';
 //            }
 //        },
 //  	};
</script>
<style>
@keyframes fullexpand {
    0% {
        width: 0px;
    }
    100% {
        width: 90%;
    }
}
 #fcontent {
        z-index: 101;
        height: 14px;
        background: #fff;
        border-radius: 4px;
        text-align: left !important;
    }
    
	.tradelist{
		width: 100%;
		height: auto;
		overflow: hidden;
        .warning{
            cursor: pointer;
            padding: 5px 10px;
            background: red;
            color: #fff;
            border-radius: 4px;
        }
		
    }
    
    
    .block{
		padding-top: 40px;
		padding-bottom: 20px;
	}
	.el-pagination{
		text-align: right;
	}
    .icon-piao-copy{
     	font-size: 36px;
     	position: absolute;
     	left: 6%;
    }
    .icon-zichan{
    	font-size: 24px;
    	color: #29b6df;
    }
	.icon-qianbao{
     	font-size: 32px;
     	position: absolute;
     	left: 6%;
             }
    .icon-yue{
    	color:#f8bc3b;
    }
    .icon-dongjiezhong{
    	color:#de5b42;
    }
    .search-box{
    	.el-button{
    		background-color: #7166bd;
    		border-color: #7166bd;
    		color: #fff;
    		border-top-left-radius: 0;
    		border-bottom-left-radius: 0;
    	}
    }
    .date-box{
    	float: right;
    }
	.el-date-editor__trigger.el-icon{
		color: #1fbae6;
	}
	.block{
		padding-top: 55px;
		padding-bottom: 20px;
	}
	.el-pagination{
		text-align: right;
	}
	.el-pager li.active{
		border-color: #22b8e5;
		background: #22b8e5;
	}
	.recode{
		padding: 30px 20px;
		background: #fff;
	}
	.mylist{
		padding: 30px 20px;
		margin-top: 30px;
		background: #fff;
	}
	.title{
		img{
			float:left;
		}
		p{
			font-size:16px;
			color:#000;
			margin-left:30px;
		}
	}
	.seach{
		height: auto;
		overflow: hidden;
		margin-top:45px;
		margin-bottom: 35px;
		.date{
			float: left;
		}
		.select{
			/*margin-left: 90px;*/
			float: left;
		}
	}
	.nseach{
		max-width: 335px;
		float: right;
	}
	.table1{
		clear: both;
		width: 100%;
		overflow-x: auto;
		table{
			width: 100%;
			thead{
				width: 100%;
				border-bottom: 1px solid #eee;
				.ttop{
					width: 100%;
					th{
						min-width: 80px;
						height: 80px;
						text-align: center;
						line-height: 80px;
						color: #000;
						font-size: 15px;
						background: #f5f8f7;
						/*&:nth-child(3){
							min-width: 160px;
						}*/
					}
				}
			}
			tbody{
				width: 100%;
				.ttbody{
					width: 100%;
					border-bottom: 1px solid #eee;
					&:hover{
						background:#f5f5f5;
					};
					td{
						padding: 0!important;
						h: 55px;
						/*lh:80px;*/
						text-align: center;
						font-size: 14px;
						.lockbtn{
							cursor: pointer;
							h:20px;
							lh:20px;
							w:65px;
							d: block;
							bg: #00a95a;
							c: #fff;
							border-radius: 4px;
							.icon-dongjiezhong{
								c:#fff;
								fz:10px;
							}
						}
					}
				}
			}
		}
	}
	.bill-list{
        background-color: #fff;
     	padding: 0 30px;
        h2{
            padding:24px 0 20px;
            font-size: 16px;
        }
        .el-form{
        }
    }
</style>