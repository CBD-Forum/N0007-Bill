<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\components\bank\Cash;
use common\components\bank\CashInAccount;
use common\components\bank\CashInRecord;
use common\models\Daybook;
use common\models\User;

/**
 * Property Controller
 * 资产控制器
 * -------------------
 * @version 1.0.0
 * @author CGA
 */
class PropertyController extends Controller
{
	/**
	 * actionUpdateCashInRecord()
	 * 更新用户充值入金记录
	 * --------------------------
	 * @author Verdient。
	 */
	public function actionUpdateCashInRecord(){
		set_time_limit(0);
		if(Yii::$app->cache->get(__METHOD__) == 1){
			exit(1);
		}
		Yii::$app->cache->set(__METHOD__, 1);
		if($users = CashInAccount::find()->select('custNo')->where(['status' => '0'])->distinct('custNo')->asArray()->all()){
			foreach($users as $key => $user){
				$custNo = $user['custNo'];
				$startRecord = CashInRecord::getStartRecord($custNo);
				if($data = Yii::$app->bank->getPaylist($custNo, $startRecord, '10')){
					if($data['returnRecords'] == 0){
						Yii::$app->bank->accessed($custNo);
					}else{
						if($data['returnRecords'] < 10){
							Yii::$app->bank->accessed($custNo);
						}
						if(!$result = CashInRecord::findOne(['HOSTFLW' => $value['HOSTFLW']])){
							foreach($data['dataList']['list'] as $key => $value){
								$rows[] = [$custNo, $value['subAccNo'], '0', $value['HOSTFLW'], $value['OPPBANKNO'], $value['XTSFAM'], $value['TRANAMT'], $value['TRANDATE'], $value['TRANTIME'], $value['RESUME'], $value['OPPACCNAME'], $value['OPPACCNO'], $value['CRYTYPE'], $value['OPPBRANCHNAME'], $value['TRANTYPE'], $value['CDFG']];
							}
							Yii::$app->db->createCommand()->batchInsert(CashInRecord::tableName(), ['custNo', 'subAccNo', 'status', 'HOSTFLW', 'OPPBANKNO', 'XTSFAM', 'TRANAMT', 'TRANDATE', 'TRANTIME', 'RESUME', 'OPPACCNAME', 'OPPACCNO', 'CRYTYPE', 'OPPBRANCHNAME', 'TRANTYPE', 'CDFG'], $rows)->execute();
						}
					}
				}
			}
		}
		Yii::$app->cache->delete(__METHOD__);
		exit(0);
	}

	/**
	 * actionCashIn()
	 * 入金
	 * --------------
	 * @author Verdient。
	 */
	public function actionCashIn(){
		set_time_limit(0);
		$status = Yii::$app->cache->get(__METHOD__);
		if($status == 1){
			exit(1);
		}
		Yii::$app->cache->set(__METHOD__, 1);
		if($result = CashInRecord::find()->select(['custNo', 'TRANAMT', 'OPPACCNO', 'status'])->where(['status' => 0])->limit(10)->orderBy(['created_at' => SORT_ASC])->all()){
			foreach($result as $row){
				if(!$userInfo = User::findOne(['enterprise_id' => $row->custNo])){
					return Yii::error(['enterprise_id' => $row->custNo, 'message' => '账户信息不存在'], __METHOD__);
				}
				$model = new Daybook;
				$model->user_id = $userInfo->id;
				$model->type = Daybook::TYPE_CASH_IN;
				$model->account = $row->OPPACCNO;
				$model->amount = $row->TRANAMT;
				if(Yii::$app->blockChain->cashIn($userInfo->address, $userInfo->id, $model->amount)){
					$model->status = Daybook::STATUS_REGULAR;
					Yii::$app->bank->updateBalance($row->custNo);
				}else{
					$model->status = Daybook::STATUS_BLOCK_FAILED;
				}
				$row->status = 1;
				$transaction = Yii::$app->db->beginTransaction();
				try{
					$model->save(false);
					$row->save(false);
					$transaction->commit();
				}catch(\Exception $e){
					$transaction->rollback();
					Yii::error($e, __METHOD__);
				}
				usleep(1000000);
			}
		}
		Yii::$app->cache->delete(__METHOD__);
	}
}