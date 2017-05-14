<?php
namespace api\v1\controllers;

use Yii;
use api\v1\components\ApiController;
use api\v1\components\Exception;
use api\v1\models\AuthenticationRecord;
use api\v1\models\Bill;
use api\v1\models\Enterprise;
use api\v1\models\Daybook;
use api\v1\models\StatisticsRealTime;
use api\v1\models\TradeRecord;
use api\v1\models\User;

/**
 * Property controller
 * 资金控制器
 * -------------------
 * @version 1.0.0
 * @author Verdient。
 */
class PropertyController extends ApiController
{
	/**
	 * actionBalance()
	 * 账户余额
	 * ---------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionBalance(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		if(!$result = Yii::$app->blockChain->getBalance($userInfo->address)){
			throw new Exception(30034);
		}
		return $this->sendResponse($result);
	}

	/**
	 * actionCashIn()
	 * 充值
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionCashIn(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		if(!Yii::$app->bank->updateCashInAccount($userInfo->enterprise_id)){
			throw new Exception(30055);
		}
		return $this->sendResponse(['message' => '充值提交成功，等待系统确认']);
	}

	/**
	 * actionCashOut()
	 * 提款
	 * ---------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionCashOut(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Daybook;
		$model->setScenario('cashOut');
		$model->load(['Daybook' => Yii::$app->request->post()]);
		$model->user_id = $userInfo->id;
		$model->type = Daybook::TYPE_CASH_OUT;
		if($model->validate()){
			if(!Yii::$app->bank->cashOut($userInfo->enterprise_id, $model->amount, $model->trade_password)){
				$model->status = Daybook::STATUS_BANK_FAILED;
				$model->save(false);
				throw new Exception(30056);
			}
			if(!Yii::$app->blockChain->cashOut($model->signature, $model->instruction_id, $userInfo->address, $userInfo->bankCardNumber, $userInfo->id, $model->amount)){
				$model->status = Daybook::STATUS_BLOCK_FAILED;
				$model->save(false);
				throw new Exception(30043);
			}
			$model->status = Daybook::STATUS_REGULAR;
			$model->save(false);
			return $this->sendResponse(['message' => '提现成功']);
		}
		$model->handleError();
	}

	/**
	 * actionPayment()
	 * 付款
	 * ---------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionPayment(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('payment');
		$model->investor = $userInfo->id;
		$model->load(['Bill' => Yii::$app->request->post()]);
		if($model->validate()){
			if($to = User::find()->select('enterprise_id')->where(['id' => $model->instance->possessor])->asArray()->one()){
				if(Yii::$app->bank->payFreeze($userInfo->enterprise_id, $to['enterprise_id'], $model->instance->financing_amount, $model->trade_password)){
					$transaction = Yii::$app->db->beginTransaction();
					try{
						Daybook::addRecord(Daybook::TYPE_PAYMENT, Daybook::STATUS_REGULAR, $model->instance);
						$model->instance->status = Bill::STATUS_WAITING_POSSESSOR_ASSIGNMENT;
						$model->instance->save(false);
						StatisticsRealTime::updateRecord(StatisticsRealTime::OPERATION_PAYMENT, $model->instance->investor, $model->instance->type, $model->instance->financing_amount);
						TradeRecord::addTradeRecord(TradeRecord::TYPE_PAYMENT, $model->instance);
						$transaction->commit();
						return $this->sendResponse(['message' => '付款成功']);
					}catch(\Exception $e){
						$transaction->rollback();
						$this->catchException($e);
					}
				}
				throw new Exception(30037);
			}
			throw new Exception(30040);
		}
		$model->handleError();
	}

	/**
	 * actionCollection()
	 * 收款
	 * ------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionCollection(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('collection');
		$model->possessor = $userInfo->id;
		$model->load(['Bill' => Yii::$app->request->post()]);
		if($model->validate()){
			if(Yii::$app->bank->unfreeze($userInfo->enterprise_id, $model->instance->id, $model->instance->financing_amount, $model->trade_password)){
				$transaction = Yii::$app->db->beginTransaction();
				try{
					Daybook::addRecord(Daybook::TYPE_COLLECTION, Daybook::STATUS_REGULAR, $model->instance);
					StatisticsRealTime::updateRecord(StatisticsRealTime::OPERATION_SELL, $model->instance->possessor, $model->instance->type, $model->instance->face_amount, $model->instance->is_traded);
					StatisticsRealTime::updateRecord(StatisticsRealTime::OPERATION_BUY, $model->instance->investor, $model->instance->type, $model->instance->face_amount);
					StatisticsRealTime::updateRecord(StatisticsRealTime::OPERATION_COLLECTION, $model->instance->possessor, $model->instance->type, $model->instance->financing_amount);
					TradeRecord::addTradeRecord(TradeRecord::TYPE_COLLECTION, $model->instance);
					$model->instance->status = Bill::STATUS_HOLDING;
					$model->instance->possessor = $model->instance->investor;
					$model->instance->investor = null;
					$model->instance->financing_amount = null;
					$model->instance->wechselspesen = null;
					$model->instance->annualized_rate = null;
					$model->instance->investor_contract_path = null;
					$model->instance->possessor_contract_path = null;
					$model->instance->is_traded = 1;
					$model->instance->save(false);
					$transaction->commit();
					return $this->sendResponse(['message' => '收款成功']);
				}catch(\Exception $e){
					$transaction->rollback();
					$this->catchException($e);
				}
			}
			throw new Exception(30038);
		}
		$model->handleError();
	}

	/**
	 * actionRefund()
	 * 退款
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionRefund(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('refund');
		$model->possessor = $userInfo->id;
		$model->load(['Bill' => Yii::$app->request->post()]);
		if($model->validate()){
			if($to = User::find()->select('enterprise_id')->where(['id' => $model->instance->investor])->asArray()->one()){
				if(Yii::$app->bank->payUnfreeze(Yii::$app->OAuth2->userInfo->enterprise_id, $to['enterprise_id'], $model->instance->financing_amount, $model->trade_password)){
					$transaction = Yii::$app->db->beginTransaction();
					try{
						Daybook::addRecord(Daybook::TYPE_REFUND, Daybook::STATUS_REGULAR, $model->instance);
						TradeRecord::addTradeRecord(TradeRecord::TYPE_REFUND, $model->instance);
						TradeRecord::addTradeRecord(TradeRecord::TYPE_REIMBURSE, $model->instance);
						StatisticsRealTime::updateRecord(StatisticsRealTime::OPERATION_REFUND, $model->instance->investor, $model->instance->type, $model->instance->financing_amount);
						$model->instance->investor = null;
						$model->instance->investor_contract_path = null;
						$model->instance->possessor_contract_path = null;
						$model->instance->status = Bill::STATUS_LISTING;
						$model->instance->save(false);
						$transaction->commit();
						return $this->sendResponse(['message' => '退款成功']);
					}catch(\Exception $e){
						$transaction->rollback();
						$this->catchException($e);
					}
				}
				throw new Exception(30041);
			}
			throw new Exception(30040);
		}
		$model->handleError();
	}

	/**
	 * actionWaitingAuthRemitList()
	 * 待认证打款企业列表
	 * ----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionWaitingAuthRemitList(){
		$userInfo = $this->checkPermission([User::TYPE_ADMIN]);
		$model = new Enterprise;
		$model->setScenario('waitingRemitList');
		$model->load(['Enterprise' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = Enterprise::find()->where(['status' => Enterprise::STATUS_WAITING_REMIT]);
			return $this->sendResponse($model->waitingRemitList($query));
		}
		$model->handleError();
	}

	/**
	 * actionAuthRemit()
	 * 确认打款
	 * -----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionAuthRemitConfirm(){
		$userInfo = $this->checkPermission([User::TYPE_ADMIN]);
		$model = new Enterprise();
		$model->setScenario('remitConfirm');
		$model->load(['Enterprise' => Yii::$app->request->post()]);
		if($model->validate()){
			$model->instance->status = Enterprise::STATUS_WAITING_AUTHENTICATION;
			$transaction = Yii::$app->db->beginTransaction();
			try{
				AuthenticationRecord::addRecord($model->instance->enterprise_id, AuthenticationRecord::TYPE_REMIT_SUCCESS, $model->instance->amount);
				$model->instance->save(false);
				$transaction->commit();
				return $this->sendResponse(['message' => '打款确认成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException();
			}
		}
		$model->handleError();
	}

	/**
	 * actionRemitFailed()
	 * 打款失败
	 * -------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionAuthRemitFailed(){
		$userInfo = $this->checkPermission([User::TYPE_ADMIN]);
		$model = new Enterprise();
		$model->setScenario('remitConfirm');
		$model->load(['Enterprise' => Yii::$app->request->post()]);
		if($model->validate()){
			$model->instance->status = Enterprise::STATUS_REMIT_FAILED;
			$transaction = Yii::$app->db->beginTransaction();
			try{
				AuthenticationRecord::addRecord($model->instance->enterprise_id, AuthenticationRecord::TYPE_REMIT_FAILED);
				$model->instance->save(false);
				$transaction->commit();
				return $this->sendResponse(['message' => '打款确认成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException();
			}
		}
		$model->handleError();
	}

	/**
	 * actionDaybookList()
	 * 资金流水
	 * -------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionDaybookList(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Daybook;
		$model->setScenario('daybookList');
		$model->load(['Daybook' => Yii::$app->request->get()]);
		$model->user_id = $userInfo->id;
		if($model->validate()){
			$query = Daybook::find()
				->where(['user_id' => $model->user_id])
				->andFilterWhere(['type' => $model->type])
				->andFilterWhere(['between', 'created_at', $model->startTime, $model->endTime]);
			return $this->sendResponse($model->daybookList($query));
		}
		$model->handleError();
	}

	// /**
	//  * _getMobile(String / Array $userId)
	//  * 获取手机号码
	//  * ----------------------------------
	//  * @param String / Array $userId 用户ID
	//  * ------------------------------------
	//  * @return String / Array
	//  * @author Verdient。
	//  */
	// protected function _getMobile($userId){
	// 	if(is_array($userId)){
	// 		$user = User::getActiveRecordList(['id' => $userId],[
	// 			'select' => ['id', 'enterprise_id as contact_number'],
	// 			'countNumber' => false,
	// 			'link' => [BaseCompany::className(), ['enterprise_id' => 'contact_number'], ['contact_number' => 'contact_number']]
	// 		]);
	// 		foreach($user as $key => $value){
	// 			$user[$value['id']] = $value['contact_number'];
	// 			unset($user[$key]);
	// 		}
	// 		foreach($userId as $key => $value){
	// 			$mobile[$value] = isset($user[$value]) ? $user[$value] : null;
	// 		}
	// 	}else{
	// 		$user = User::getActiveRecordInformation(['id' => $userId], true, ['enterprise_id as contact_number'], [
	// 			[BaseCompany::className(), ['enterprise_id' => 'contact_number'], ['contact_number' => 'contact_number']]
	// 		]);
	// 		$mobile = isset($user['contact_number']) ? $user['contact_number'] : null;
	// 	}
	// 	return $mobile;
	// }
}
