<template>
    <div>
        <el-col :span="24" class="bill-list" style="height:860px;">
            <div class="top-title" style="">
                <img src="../../assets/images/faxing.png" alt=""> 系统配置
            </div>
            <div class="form_container">
                <el-form  :model="body" label-width="80px">
                    <el-form-item label="贴息率">
                        <el-col :span="8">
                            <el-input v-model="body.annualized_rate_min"></el-input>
                        </el-col>
                        <el-col :span="2">
                            %
                        </el-col>
                        <el-col :span="2">-</el-col>
                        <el-col :span="8">
                            <el-input v-model="body.annualized_rate_max"></el-input>
                        </el-col>
                        <el-col :span="2">
                            %
                        </el-col>
                    </el-form-item>
                    <el-form-item label="到期日前">
                        <el-col :span="8">
                            <el-input v-model="body.listing_reserve_day"></el-input>
                        </el-col>
                        <el-col :span="16">
                            日内
                        </el-col>
                    </el-form-item>
                    <el-form-item>
                        <el-button @click="setConfigs">保存</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-col>
    </div>
</template>
<script>
import {fetch, postUrl} from '../../../src/assets/js/api'
export default {
    data() {
            return {
                body:{
                    annualized_rate_min:'--',
                    annualized_rate_max:'--',
                    listing_reserve_day:'--'
                }
            }
        },
        mounted: function() {
            this.$nextTick(() => {
                this.getConfigs();
            })
        },
        methods: {
            getConfigs: function( ) {
                fetch(`/config/get-configs`).then(data => {
                    this.body = data;
                    this.body.annualized_rate_min = (this.body.annualized_rate_min*100).toFixed(2);
                    this.body.annualized_rate_max = (this.body.annualized_rate_max*100).toFixed(2);
                });
            },
            setConfigs: function( ) {
                this.body = {
                    annualized_rate_min:(this.body.annualized_rate_min/100).toFixed(4),
                    annualized_rate_max:(this.body.annualized_rate_max/100).toFixed(4),
                    listing_reserve_day: this.body.listing_reserve_day,
                }
                postUrl(`/config/set-configs`, this.body).then(data => {
                    this.body = {
                        annualized_rate_min:'--',
                        annualized_rate_max:'--',
                        listing_reserve_day:'--',
                    }
                    if(data.code == 200){
                        this.$notify({
                            title:'提示',
                            message:'修改成功',
                            type:'success'
                        });
                        this.getConfigs();
                    }else{
                        this.$notify({
                            title:'提示',
                            message:data.message,
                            type:'warning'
                        });
                    }
                });
            }
        }
};
</script>
<style scoped>   
.form_container {
    padding-top: 40px;
    width: 400px;
    margin: 0 auto;
}
</style>
