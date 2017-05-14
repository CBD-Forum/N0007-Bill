<template>
	<div class="login-containerNew" >
		<div class="loginNew" v-show="showType == -1">
			<div class="title">
				已登录
			</div>
			<button v-on:click="returnUserCenter">返回用户中心</button>
			<button v-on:click="exitLogin">退出</button>
		</div>

		<div class="loginNew" v-show="showType == 0">
			<div v-show="loginData.showTitle == 0" class="title">
				登录
			</div>
			<div v-show="loginData.showTitle == 1" class="error">
				{{loginData.errMsg}}
			</div>
			<input v-on:keyup.enter="login" type="text" placeholder="请输入邮箱或手机" v-model="loginBody.email" />
			<input v-on:keyup.enter="login" type="password" placeholder="请输入登录密码" v-model="loginBody.password"  />
			<div class="row-contorl clearfix">
			</div>
			<el-button type="primary" :loading="!loginData.loginBtn" @click.native.prevent="login">{{ loginData.loginBtn? '登录':'登录中...'}}</el-button>
		</div>
	</div>
</template>
<script>
	import {postUrl,getPublicKey,getPrivatekey,billNoteSigns} from '../../../src/assets/js/api'
	export default {
		data(){
			return{
				showType: 0,
				loginData: {
					loginBtn:true,
					showTitle: 0,
					errMsg: '',
				},
				loginBody:{
					email : "",
					password : "",
				},
			}
		},
		mounted: function(){
			this.$nextTick(() => {
				// setTimeout(()=>{
				// 	this.billNoteSigns=new billSign();
				// }, 200);
				
				sessionStorage.getItem('token') == null ? this.showType = 0 : this.showType = -1;
				let that = this;
			})
  		},
		methods: {
			returnUserCenter:function(){
				this.$router.push({ path: '/user/home' });
			},
			exitLogin:function(){
				sessionStorage.clear();
				this.showType = 0;
			},
			login : function(){
				if(this.loginBody.email == ''){
					this.loginData.showTitle = 1;
					this.loginData.errMsg = '请输入用户名！';
				} else if(this.loginBody.password == ''){
					this.loginData.showTitle = 1;
					this.loginData.errMsg = '请输入密码！';
				}else{
					this.loginData.loginBtn = false;
					postUrl(`/auth/token?grant_type=user_credentials`, this.loginBody).then(data => {
						if(data.code == 200){
								var random=data.random;
								console.log(random);
								var privateKey=getPrivatekey(this.loginBody.password,random);
								var publick=getPublicKey(privateKey);
								sessionStorage.setItem('privateKey',privateKey);
								sessionStorage.setItem('publicKey',publick);
			    			sessionStorage.setItem('token',data.access_token);
			    			sessionStorage.setItem('refreshToken',data.refresh_token);
			    			this.$router.push({ path: '/user/home' });
			    		} else{
			    			this.loginData.showTitle = 1;
			    			this.loginData.errMsg =data.message;
			    			this.loginData.loginBtn = true;
			    		}
					});
				}
			},
		}
	}
</script>
<style>
	@import '../../assets/css/loginNew.css';
	.el-checkbox{
		font-size: 12px;
		float: left;
		color:#fff !important;
	}
	.el-checkbox__label {
		font-size: 12px !important;
	}
	.el-checkbox__inner.is-checked{
		border-color: #3bd0bd !important;
		background-color: #3bd0bd !important;
	}
	.reg{
		float:left !important;
	}
</style>
