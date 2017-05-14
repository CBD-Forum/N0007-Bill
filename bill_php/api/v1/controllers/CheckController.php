<?php
namespace api\v1\controllers;

use Yii;
use yii\helpers\Json;
use api\v1\components\ApiController;
use api\v1\components\Exception;
use api\v1\models\AuthenticationRecord;
use api\v1\models\Bill;
use api\v1\models\Check;
use api\v1\models\CheckRecord;
use api\v1\models\Enterprise;
use api\v1\models\PublicKey;
use api\v1\models\StatisticsRealTime;
use api\v1\models\TradeRecord;
use api\v1\models\User;

/**
 * Check Controller
 * 检查 控制器
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class CheckController extends ApiController
{
	/**
	 * actionBill()
	 * 审核票据
	 * ------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionBill(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_ADMIN]);
		$model = new Check;
		$model->setScenario('check');
		$model->load(['Check' => Yii::$app->request->post()]);
		if($model->validate()){
			$model->instance->annualized_rate_suggest = $model->isFinanceOrReview($userInfo) ? $model->annualized_rate_suggest : null;
			$fieldStatusMap = $model->instance->fieldStatusMap;
			foreach($model->field_status as $key => $value){
				if($value === 1 && !($model->instance->field_status & $fieldStatusMap[$key])){
					$model->instance->field_status += $fieldStatusMap[$key];
				}
			}
			$passFieldStatus = 0;
			foreach($fieldStatusMap as $key => $value) {
				$passFieldStatus += $value;
			}
			$model->instance->status = $model->instance->field_status == $passFieldStatus ? Bill::STATUS_HOLDING : Bill::STATUS_CHECK_FAILED;
			if($model->instance->status == Bill::STATUS_HOLDING){
				if(!$owner = PublicKey::find()->select('public_key')->where(['user_id' => $model->instance->possessor, 'status' => PublicKey::STATUS_REGULAR])->asArray()->one()){
					throw new Exception(19024);
				}
				if(!$model->createBill($owner['public_key'], $userInfo)){
					throw new Exception(19023);
				}
			}
			$transaction = Yii::$app->db->beginTransaction();
			try{
				if($model->instance->status == Bill::STATUS_HOLDING){
					$model->instance->block_created_at = date('Y-m-d H:i:s');
					StatisticsRealTime::updateRecord(StatisticsRealTime::OPERATION_ENTERING, $model->instance->possessor, $model->instance->type, $model->instance->face_amount);
					TradeRecord::addTradeRecord(TradeRecord::TYPE_TYPE_IN, $model->instance, $model->instruction_id);
				}
				CheckRecord::addCheckRecord($userInfo->id, $model->instance);
				$model->instance->save(false);
				$transaction->commit();
				return $this->sendResponse(['message' => '票据审核成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$errors = $model->getFirstErrors();
		throw new Exception(reset($errors));
	}

	/**
	 * actionEnterpriseConfirm()
	 * 企业审核通过
	 * -------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionEnterpriseConfirm(){
		$userInfo = $this->checkPermission([User::TYPE_ADMIN]);
		$model = new Enterprise();
		$model->setScenario('auditConfirm');
		$model->load(['Enterprise' => Yii::$app->request->post()]);
		if($model->validate()){
			$model->instance->amount = $model->generateAmount();
			$model->instance->status = Enterprise::STATUS_WAITING_REMIT;
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$model->instance->save(false);
				AuthenticationRecord::addRecord($model->instance->enterprise_id, AuthenticationRecord::TYPE_ADUIT_SUCCESS);
				$transaction->commit();
				return $this->sendResponse(['message' => '审核成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionEnterpriseFailed()
	 * 企业审核失败
	 * ------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionEnterpriseFailed(){
		$userInfo = $this->checkPermission([User::TYPE_ADMIN]);
		$model = new Enterprise();
		$model->setScenario('auditFailed');
		$model->load(['Enterprise' => Yii::$app->request->post()]);
		if($model->validate()){
			$model->instance->status = Enterprise::STATUS_AUDIT_FAILED;
			$transaction = Yii::$app->db->beginTransaction();
			try{
				AuthenticationRecord::addRecord($model->instance->enterprise_id, AuthenticationRecord::TYPE_ADUIT_FAILED);
				$model->instance->save(false);
				$transaction->commit();
				return $this->sendResponse(['message' => '审核成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionBillWaitingList()
	 * 待审核票据列表
	 * -------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionBillWaitingList(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_ADMIN]);
		$model = new Bill;
		$model->setScenario('waitingCheckList');
		$model->load(['Bill' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = Bill::find()
				->where(['status' => Bill::STATUS_CHECK_PENDING])
				->andFilterWhere(['between', 'acceptance_at', $model->startTime, $model->endTime])
				->andFilterWhere(['or', ['like', 'bill_number', $model->query], ['like', 'acceptor', $model->query], ['like', 'face_amount', $model->query]]);
			switch($userInfo->type){
				case User::TYPE_FINANCE:
					if(!empty($userInfo->members)){
						$query->andFilterWhere(['possessor' => $userInfo->members]);
					}else{
						return $this->sendResponse(['data' => [], 'count' => 0]);
					}
					break;
				case User::TYPE_REVIEW:
					$query->andFilterWhere(['possessor' => $userInfo->parent]);
					break;
				case User::TYPE_ADMIN:
					$query->andFilterWhere(['possessor' => $userInfo->externals]);
					break;
			}
			return $this->sendResponse($model->waitingCheckList($query));
		}
		$model->handleError();
	}

	/**
	 * actionEnterpriseWaitingList()
	 * 待审核企业列表
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionEnterpriseWaitingList(){
		$userInfo = $this->checkPermission([User::TYPE_ADMIN]);
		$model = new Enterprise;
		$model->setScenario('waitingCheckList');
		$model->load(['Enterprise' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = Enterprise::find()->where(['status' => Enterprise::STATUS_WAITING_AUDIT]);
			return $this->sendResponse($model->waitingCheckList($query));
		}
		$model->handleError();
	}

	/**
	 * actionBillHistory()
	 * 票据审核历史
	 * ---------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionBillHistory(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_ADMIN]);
		$model = new CheckRecord();
		$model->setScenario('checkRecordList');
		$model->load(['CheckRecord' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = CheckRecord::find()
				->select(['id', 'status', 'apply_at', 'face_amount', 'acceptor', 'bill_number', 'bill_type', 'acceptance_at', 'created_at', 'bill_info'])
				->where(['user_id' => $userInfo->id])
				->andFilterWhere(['bill_type' => $model->bill_type])
				->andFilterWhere(['status' => $model->status])
				->andFilterWhere(['between', 'acceptance_at', $model->startTime, $model->endTime])
				->andFilterWhere(['or', ['like','bill_number', $model->query],['like', 'acceptor', $model->query], ['like', 'face_amount', $model->query]]);
			return $this->sendResponse($model->checkRecordList($query));
		}
		$model->handleError();
	}

	/**
	 * actionBillHistoryInfo()
	 * 票据审核历史信息
	 * -----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionBillHistoryInfo(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_ADMIN]);
		$model = new CheckRecord();
		$model->setScenario('billHistoryInfo');
		$model->load(['CheckRecord' => Yii::$app->request->get()]);
		if($model->validate()){
			$billInfo = json_decode($model->instance->bill_info, true);
			$model2 = new Bill;
			$model2->checkInfoDecode($billInfo);
			$typeMap = eval(Bill::TYPE_MAP);
			$acceptorTypeMap = eval(Bill::ACCEPTOR_TYPE_MAP);
			return $this->sendResponse([
				'bill_number' => $billInfo['bill_number'],
				'type' => isset($typeMap[$billInfo['type']]) ? $typeMap[$billInfo['type']] : 'Unknown',
				'drawer' => $billInfo['drawer'],
				'taker' => $billInfo['taker'],
				'acceptor' => $billInfo['acceptor'],
				'acceptor_type' => isset($acceptorTypeMap[$billInfo['acceptor_type']]) ? $acceptorTypeMap[$billInfo['acceptor_type']] : 'Unknown',
				'face_amount' => $billInfo['face_amount'],
				'issue_at' => $billInfo['issue_at'],
				'acceptance_at' => $billInfo['acceptance_at'],
				'bill_front_path' => Yii::$app->staticResource->path2url($billInfo['bill_front_path']),
				'bill_back_path' => Yii::$app->staticResource->path2url($billInfo['bill_back_path']),
				'bill_number_status' => $billInfo['bill_number_status'],
				'drawer_status' => $billInfo['drawer_status'],
				'acceptor_status' => $billInfo['acceptor_status'],
				'face_amount_status' => $billInfo['face_amount_status'],
				'acceptance_at_status' => $billInfo['acceptance_at_status'],
				'type_status' => $billInfo['type_status'],
				'taker_status' => $billInfo['taker_status'],
				'acceptor_type_status' => $billInfo['acceptor_type_status'],
				'issue_at_status' => $billInfo['issue_at_status']
			]);
		}
		$model->handleError();
	}

	/**
	 * acitonEnterpriseHistory()
	 * 企业审核历史
	 * -------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionEnterpriseHistory(){
		$userInfo = $this->checkPermission([User::TYPE_ADMIN]);
		$model = new AuthenticationRecord();
		$model->setScenario('authenticationHistory');
		$model->load(['AuthenticationRecord' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = AuthenticationRecord::find();
			return $this->sendResponse($model->authenticationList($query));
		}
		$model->handleError();
	}
}